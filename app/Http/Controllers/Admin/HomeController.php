<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin\Defined;
use App\Model\Admin\Equipment;
use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\AuthCodeRepository;
use App\Repository\Admin\EquipmentRepository;
use App\Repository\Admin\HuobiRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * 内容管理-内容管理
     */
    public function showAggregation(Request $request)
    {
        $utility = $request->attributes->get('utility');

        $level_id = auth()->guard('admin')->user()->level_id;

        $parent_id = $request->getParentId(auth()->guard('admin')->user()->id);

        // $parent_id = $this->getParentId(\Auth::guard('admin')->user()->id);
        // 如果级别为自定义，则从自定义里面获取数据
        if ($level_id == 8) {
            $where = ['user_id' => \Auth::guard('admin')->user()->id];
            $equipment = Defined::query()->where($where)->orderBy('assort_id')->get();
        } else {
            // 先验证该国代有没有自定义级别配置管理,如果没有则获取默认级别配置
            $guodai_where = ['user_id' => $parent_id];
            $res = EquipmentRepository::findByWhere($guodai_where);
            if ($res) {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => $parent_id];
                $equipment = Equipment::query()->where($where)->get();
            } else {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => 1];
                $equipment = Equipment::query()->where($where)->get();
            }
        }
        $month = date('m');
        $date = date('Y-m', time());
        $last_month = '0'.(date('m') - 1);
        $last = strtotime('-1 month', time());
        $last_date = date('Y-m', $last);

        if (\Auth::guard('admin')->user()->id == 1) {
            $where = ['is_try' => 1];
        } else {
            $where = ['is_try' => 1, 'user_id' => \Auth::guard('admin')->user()->id];
        }
        // 本月总计生成授权码数量
        $count_code = AuthCodeRepository::countByCode($where);
        // 本月本人生成授权码
        $month_code = AuthCodeRepository::lowerByCode($where, dates($date));
        // 上月本人生成授权码
        $last_month_code = AuthCodeRepository::lowerByCode($where, dates($last_date));
        // 现获取所有的下级id
        $all_users = AdminUserRepository::getDataByWhere([]);
        $ids = $this->get_downline($all_users, \Auth::guard('admin')->user()->id, \Auth::guard('admin')->user()->level_id);
        //        $user_where = ['pid' => \Auth::guard('admin')->user()->id];
        //        $ids = AdminUserRepository::getIdsByWhere($user_where);
        $profit_where = ['status' => 0, 'type' => 1, 'user_id' => \Auth::guard('admin')->user()->id];
        // 总计下级产生利润
        $sum_profit = HuobiRepository::lowerByAddProfit($profit_where);
        // 本月下级产生利润
        $month_profit = HuobiRepository::lowerByProfit(dates($date), $profit_where);
        // 上月下级产生利润
        $last_month_profit = HuobiRepository::lowerByProfit(dates($last_date), $profit_where);
        // 上月消耗火币
        $expend_where = ['type' => 2, 'user_id' => \Auth::guard('admin')->user()->id];
        $month_expend = HuobiRepository::expendByHuobi($expend_where, dates($last_date));
        // 获取总共的会员数
        $this->getLevel(\Auth::guard('admin')->user()->id);
        // 本月下级生成授权码个数
        $lower_month_code = HuobiRepository::lowerByCode(dates($date), $ids);
        // 上月下级生成授权码个数
        $lower_last_month_code = HuobiRepository::lowerByCode(dates($last_date), $ids);
        $locale = session('customer_lang_name');

        // 当前登录用户是否存在下级
        //        $type = 0;
        //        if (\Auth::guard('admin')->user()->level_id > 7) {
        //            // 验证该用户是不是最下级
        //            $own_money = Defined::query()->where(['user_id' => \Auth::guard('admin')->user()->id])->pluck('money');
        //            $i = 0;
        //            foreach ($own_money as $k => $v) {
        //                if (($this->list[$k] - $v) < 2) {
        //                    $i++;
        //                }
        //            }
        //            if ($i == 4) {
        //                $type = 1;
        //            }
        //        }
        //        $data = $equipment->toArray();
        //        $arrDemo = arraySequence($data,'money', 'SORT_ASC');
        return view('admin.home.content', [
            'equipment' => $equipment,
            'month_code' => $month_code,
            'last_month_code' => $last_month_code,
            'count_code' => $count_code,
            'sum_profit' => $sum_profit,
            'month_profit' => $month_profit,
            'last_month_profit' => $last_month_profit,
            'month_expend' => $month_expend,
            'user_count' => $this->count,
            'lower_month_code' => $lower_month_code,
            'lower_last_month_code' => $lower_last_month_code,
            'locale' => $locale ? $locale : 'en',
            //            'type' => $type,
        ]);
    }

    // 获取下级的个数
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
    }
}
