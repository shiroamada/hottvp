<?php

namespace App\Exports;

use App\Models\AuthCode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicenseCodesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $user = Auth::guard('admin')->user();
        $query = AuthCode::where('is_try', 1)->with('assort');

        if ($user->id != 1) {
            $query->where('user_id', $user->id);
        }

        // Apply filters from the request
        if ($this->request->filled('auth_code')) {
            $query->where('auth_code', 'like', '%' . $this->request->input('auth_code') . '%');
        }
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->input('status'));
        }
        if ($this->request->filled('assort_id')) {
            $query->where('assort_id', $this->request->input('assort_id'));
        }
        if ($this->request->filled('created_at')) { // Use created_at for consistency
            $dates = explode(' - ', $this->request->input('created_at'));
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        return $query->latest()->get();
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
