<?php

namespace App\Http\Middleware;

use App\Models\Admin\Cost;
use App\Models\Admin\Level;
use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\CostRepository;
use App\Repository\Admin\RetailRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUtilityMiddleware
{
    protected $idss = [];

    protected $ids_list_all = [];

    protected $ids_list_all_tt = [];

    protected $lowers = [];

    protected $ids = [];

    protected $ids_list = [];

    protected $count = 0;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Reset properties for each request to avoid state persistence between requests
        $this->idss = [];
        $this->ids_list_all = [];
        $this->ids_list_all_tt = [];
        $this->lowers = [];
        $this->ids = [];
        $this->ids_list = [];
        $this->count = 0;

        // Store utility methods in the request for controllers to use
        $request->attributes->set('utility', $this);

        return $next($request);
    }

    /**
     * Get parent ID by recursively finding the top-level parent
     *
     * @param  int  $uid  User ID
     * @return int Parent ID
     */
    public function getParentId($uid)
    {
        $where = ['id' => $uid];
        $info = AdminUserRepository::findByWhere($where);
        if ($info->pid > 1) {
            return $this->getParentId($info->pid);
        }

        return $info['id'];
    }

    /**
     * Get lower IDs recursively with specific condition
     *
     * @param  int  $uid  User ID
     * @return array Lower IDs
     */
    public function getLowerIdss($uid)
    {
        $this->idss[] = $uid;

        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                if ($info->is_cancel == 2) {
                    $this->idss[] = $info->id;
                }
                $this->getLowerIdss($info->id);
            }
        }

        return $this->idss;
    }

    /**
     * Get lower IDs by all with level filtering
     *
     * @param  int  $uid  User ID
     * @param  int  $level_id  Level ID
     * @return array Lower IDs
     */
    public function getLowerIdsByAll($uid, $level_id = 0)
    {
        $ids = [];

        if ($level_id > 0) {
            $users = AdminUserRepository::getListByWhere(['pid' => $uid, 'level_id' => $level_id]);

            foreach ($users as $user) {
                $ids[] = $user->id;
                // Recurse for this user's children
                $this->getAllChildrenIdsFiltered($user->id, $ids, $level_id);
            }
        } else {
            $this->getAllChildrenIds($uid, $ids);
        }

        return $ids;
    }

    /**
     * Helper function to get child IDs filtered by level
     */
    private function getAllChildrenIdsFiltered($id, array &$ids, $level_id): void
    {
        $children = AdminUserRepository::getListByWhere(['pid' => $id, 'level_id' => $level_id]);

        foreach ($children as $child) {
            $ids[] = $child->id;
            $this->getAllChildrenIdsFiltered($child->id, $ids, $level_id);
        }
    }

    /**
     * Helper function to get child IDs recursively
     */
    private function getAllChildrenIds($id, array &$ids): void
    {
        $children = AdminUserRepository::getListByWhere(['pid' => $id]);
        foreach ($children as $child) {
            $ids[] = $child->id;
            $this->getAllChildrenIds($child->id, $ids);
        }
    }

    /**
     * Get lower by IDs recursively
     *
     * @param  int  $uid  User ID
     * @return array Lower IDs
     */
    public function getLowerByIds($uid)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                if ($info->is_cancel != 2) {
                    $this->lowers[] = $info->id;
                    $this->getLowerByIds($info->id);
                }
            }
        }

        return $this->lowers;
    }

    /**
     * Get downline recursively
     *
     * @param  array  $members  Members array
     * @param  int  $mid  Member ID
     * @param  int  $level  Level
     * @return array Downline IDs
     */
    public function get_downline($members, $mid, $level = 0)
    {
        $arr = [];
        foreach ($members as $key => $v) {
            if ($v['pid'] == $mid) {  // pid为0的是顶级分类
                $v['level'] = $level + 1;
                $arr[] = $v->id;
                $arr = array_merge($arr, $this->get_downline($members, $v['id'], $level + 1));
            }
        }

        return $arr;
    }

    /**
     * Check if request is AJAX
     */
    public function isAjax(Request $request): void
    {
        if (! $request->ajax()) {
            abort(403, 'Only AJAX requests are allowed.');
        }
    }

    /**
     * Get retail data
     *
     * @param  int  $parent_id  Parent ID
     * @return array Retail data
     */
    public function getRetail($parent_id)
    {
        $retail_where = ['user_id' => $parent_id];
        $retailList = RetailRepository::getMoneys($retail_where);

        return $retailList->toArray();
    }

    /**
     * Get level cost
     *
     * @param  int  $parent_id  Parent ID
     * @return array Level cost data
     */
    public function getLevelCost($parent_id)
    {
        $retail_where = ['user_id' => $parent_id];
        $retailList = CostRepository::findByList($retail_where);

        return $retailList->toArray();
    }

    /**
     * Get lower IDs
     *
     * @param  int  $uid  User ID
     * @return array Lower IDs
     */
    public function getLowerId($uid)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                if ($info->is_cancel != 0) {
                    $this->ids[] = $info->id;
                }
                $this->getLowerId($info->id);
            }
        }

        return $this->ids;
    }

    /**
     * Get lower IDs
     *
     * @param  int  $uid  User ID
     * @return array Lower IDs
     */
    public function getLowerIds($uid)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                $this->ids_list[] = $info->id;
                $this->getLowerIds($info->id);
            }
        }

        return $this->ids_list;
    }

    /**
     * Get level count
     *
     * @param  int  $id  User ID
     * @return int Count
     */
    public function getLevel($id)
    {
        $count_where = ['pid' => $id];
        $ids = AdminUserRepository::getIdsByWhere($count_where);
        foreach ($ids as $info) {
            if (! empty($info)) {
                $this->count++;
                $this->getLevel($info);
            }
        }

        return $this->count;
    }
}
