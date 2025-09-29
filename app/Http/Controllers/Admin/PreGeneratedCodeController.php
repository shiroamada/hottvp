<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssortLevel;
use App\Models\PreGeneratedCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class PreGeneratedCodeController extends Controller
{
    public static function middleware(): array
    {
        // Only allow superadmin (level_id == 3) per CheckLevel middleware
        return [
            new ControllerMiddleware('check.level'),
        ];
    }

    public function index(Request $request)
    {
        $query = PreGeneratedCode::query();

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->input('code') . '%');
        }

        if ($request->filled('status')) {
            if ($request->input('status') == 'available') {
                $query->whereNull('requested_at');
            } elseif ($request->input('status') == 'requested') {
                $query->whereNotNull('requested_at');
            }
        }
cc cc
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('imported_at')) {
            $times = array_map('trim', explode(" to ", $request->input('imported_at')));
            if (count($times) == 2) {
                $query->whereBetween('imported_at', [$times[0], $times[1]]);
            }
        }

        $query->with(['importer', 'requester']);

        $codes = $query->latest('imported_at')->paginate(15);

        return view('admin.pre_generated_codes.index', [
            // Keep existing key used by current blade templates
            'lists' => $codes,
            // Provide 'codes' key for tests and clearer semantics
            'codes' => $codes,
            'types' => PreGeneratedCode::TYPES,
        ]);
    }

    public function create()
    {
        return view('admin.pre_generated_codes.create', [
            'types' => PreGeneratedCode::TYPES,
            'vendors' => PreGeneratedCode::VENDORS,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'codes' => 'required|string',
            'type' => 'required|string',
            'vendor' => 'required|string',
            'remark' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $codes = preg_split('/\r\n|\r|\n/', $request->input('codes'));
            $user = Auth::guard('admin')->user();
            $now = now();
            $insertData = [];

            foreach ($codes as $code) {
                $code = trim($code);
                if (empty($code)) {
                    continue;
                }

                // Avoid duplicates
                if (!PreGeneratedCode::where('code', $code)->exists()) {
                    $insertData[] = [
                        'code' => $code,
                        'type' => $request->input('type'),
                        'vendor' => $request->input('vendor'),
                        'remark' => $request->input('remark'),
                        'assort_level_id' => null,
                        'imported_by' => $user->id,
                        'imported_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($insertData)) {
                PreGeneratedCode::insert($insertData);
            }

            DB::commit();

            $count = count($insertData);
            return redirect()->back()->with('success', trans('messages.pre_generated_codes.import_success', ['count' => $count]));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Code import failed: ' . $e->getMessage());
            return redirect()->back()->with('error', trans('messages.pre_generated_codes.import_error'));
        }
    }
}
