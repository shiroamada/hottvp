<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;
use App\Repository\Admin\HuobiRepository;
use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\AssortRepository;
use Illuminate\Support\Facades\Auth;

class HuobiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $condition = $this->request->only(['id', 'user_id', 'event', 'money', 'status', 'date2']);

        if (isset($condition['date2']) && !empty($condition['date2'])) {
            $times = array_map('trim', explode(" to ", $condition['date2']));
            $condition['startTime'] = $times[0];
            $condition['endTime'] = isset($times[1]) ? $times[1] : $times[0];
        }
        unset($condition['date2']);

        if (isset($condition['status']) && $condition['status'] == 1) {  // 充入火币
            if (Auth::guard('admin')->user()->id == 1) {
                $own_where = ['status' => 1, 'type' => 1];
            } else {
                $own_where = ['user_id' => Auth::guard('admin')->user()->id, 'status' => 1, 'type' => 1];
            }
            $data = HuobiRepository::listByWhere($own_where, $condition);
        } elseif (isset($condition['status']) && $condition['status'] == 2) { // 为下级充值
            if (Auth::guard('admin')->user()->id == 1) {
                $xiaji_where = ['status' => 1, 'type' => 2];
            } else {
                $xiaji_where = ['user_id' => Auth::guard('admin')->user()->id, 'status' => 1, 'type' => 2];
            }
            $data = HuobiRepository::listByWhere($xiaji_where, $condition);
        } elseif (isset($condition['status']) && $condition['status'] == 3) { // 生成授权码
            if (Auth::guard('admin')->user()->id == 1) {
                $own_code_where = ['status' => 0, 'type' => 2];
            } else {
                $own_code_where = ['user_id' => Auth::guard('admin')->user()->id, 'status' => 0, 'type' => 2];
            }
            $data = HuobiRepository::listByWhere($own_code_where, $condition);
        } elseif (isset($condition['status']) && $condition['status'] == 4) { // 下级产生的利润
            if (Auth::guard('admin')->user()->id == 1) {
                $xiaji_code_where = ['status' => 0, 'type' => 1];
            } else {
                $xiaji_code_where = ['user_id' => Auth::guard('admin')->user()->id, 'status' => 0, 'type' => 1];
            }
            $data = HuobiRepository::listByWhere($xiaji_code_where, $condition);
        } else {
            unset($condition['status']);
            if (Auth::guard('admin')->user()->id != 1) {
                $condition['user_id'] = ['=', Auth::guard('admin')->user()->id];
            }
            $data = HuobiRepository::defaultList($condition);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            trans('huobi.id'),
            trans('general.create'),
            trans('huobi.event'),
            trans('huobi.money'),
        ];
    }

    public function map($value): array
    {
        $details = AdminUserRepository::find($value['own_id']);
        $assort = AssortRepository::find($value['assort_id']);
        $event = "";

        if ($value['status'] == 1 && $value['type'] == 2) {
            if (isset($details->name)) {
                $event = trans('adminUser.by') . $details->name . trans('adminUser.lower');
            } else {
                $event = trans('adminUser.lower');
            }
        } elseif ($value['status'] == 1 && $value['type'] == 1) {
            $event = trans('adminUser.myself');
        } elseif ($value['status'] == 0 && $value['type'] == 1) {
            if (isset($details->account) && $details->account == $value['user_account']) {
                $event = $details->name . trans('general.generate') . $assort->assort_name;
            } else {
                $event = (isset($details->name) ? $details->name : '') . trans('general.as_lower') . $value['user_account'] . trans('general.generate') . $assort->assort_name;
            }
        } elseif ($value['status'] == 0 && $value['type'] == 2) {
            if (isset($details->name)) {
                $event = $details->name . trans('general.generate') . $assort->assort_name;
            } else {
                $event = trans('general.generate') . $assort->assort_name;
            }
        }

        $money = ($value['type'] == 2 ? '-' : '+') . number_format($value['money'], 2);

        return [
            $value['id'],
            $value['created_at'],
            $event,
            $money,
        ];
    }
}