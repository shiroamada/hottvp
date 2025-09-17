<?php

namespace App\Exports;

use App\Models\AuthCode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class LastBatchExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = Auth::guard('admin')->user();
        $where = ['user_id' => $user->id, 'is_try' => 1];
        $info = AuthCode::query()->where($where)->orderBy('id', 'desc')->first();

        $list = collect();
        if ($info && $info->num > 0) {
            $list = AuthCode::query()->where($where)->orderBy('id', 'desc')->limit($info->num)->with('assort')->get();
        }
        return $list;
    }

    public function headings(): array
    {
        return [
            trans('authCode.id'),
            trans('authCode.auth_code'),
            trans('authCode.code_func'),
            trans('authCode.status'),
            trans('authCode.remark'),
            trans('authCode.expire_at'),
            trans('general.create'),
        ];
    }

    public function map($code): array
    {
        $status = '';
        if ($code->status == 0) {
            $status = trans('authCode.status_unused');
        } elseif ($code->status == 1) {
            $status = trans('authCode.status_have_used');
        } elseif ($code->status == 2) {
            $status = trans('authCode.status_was_due');
        }

        return [
            $code->id,
            $code->auth_code,
            $code->assort->assort_name ?? 'N/A',
            $status,
            $code->remark,
            $code->expire_at ? \Carbon\Carbon::parse($code->expire_at)->format('Y-m-d') : '',
            $code->created_at,
        ];
    }
}
