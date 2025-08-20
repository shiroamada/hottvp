<?php

namespace App\Services;

use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\CostRepository;
use App\Repository\Admin\RetailRepository;
use Illuminate\Http\Request;

class AdminUtilityService
{
    protected $idss = [];
    protected $ids_list_all = [];
    protected $ids_list_all_tt = [];
    protected $lowers = [];
    protected $ids = [];
    protected $ids_list = [];
    protected $count = 0;

    public function reset(): void
    {
        $this->idss = [];
        $this->ids_list_all = [];
        $this->ids_list_all_tt = [];
        $this->lowers = [];
        $this->ids = [];
        $this->ids_list = [];
        $this->count = 0;
    }

    public function getParentId($uid)
    {
        $where = ['id' => $uid];
        $info = AdminUserRepository::findByWhere($where);
        if ($info->pid > 1) {
            return $this->getParentId($info->pid);
        }
        return $info['id'];
    }

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

    public function getLowerIdsByAll($uid, $level_id = 0)
    {
        $ids = [];

        if ($level_id > 0) {
            $users = AdminUserRepository::getListByWhere(['pid' => $uid, 'level_id' => $level_id]);

            foreach ($users as $user) {
                $ids[] = $user->id;
                $this->getAllChildrenIdsFiltered($user->id, $ids, $level_id);
            }
        } else {
            $this->getAllChildrenIds($uid, $ids);
        }

        return $ids;
    }

    private function getAllChildrenIdsFiltered($id, array &$ids, $level_id): void
    {
        $children = AdminUserRepository::getListByWhere(['pid' => $id, 'level_id' => $level_id]);

        foreach ($children as $child) {
            $ids[] = $child->id;
            $this->getAllChildrenIdsFiltered($child->id, $ids, $level_id);
        }
    }

    private function getAllChildrenIds($id, array &$ids): void
    {
        $children = AdminUserRepository::getListByWhere(['pid' => $id]);
        foreach ($children as $child) {
            $ids[] = $child->id;
            $this->getAllChildrenIds($child->id, $ids);
        }
    }

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

    public function get_downline($members, $mid, $level = 0)
    {
        $arr = [];
        foreach ($members as $v) {
            if ($v['pid'] == $mid) {
                $v['level'] = $level + 1;
                $arr[] = $v->id;
                $arr = array_merge($arr, $this->get_downline($members, $v['id'], $level + 1));
            }
        }
        return $arr;
    }

    public function isAjax(Request $request): void
    {
        if (! $request->ajax()) {
            abort(403, 'Only AJAX requests are allowed.');
        }
    }

    public function getRetail($parent_id)
    {
        $retail_where = ['user_id' => $parent_id];
        $retailList = RetailRepository::getMoneys($retail_where);
        return $retailList->toArray();
    }

    public function getLevelCost($parent_id)
    {
        $retail_where = ['user_id' => $parent_id];
        $retailList = CostRepository::findByList($retail_where);
        return $retailList->toArray();
    }

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
