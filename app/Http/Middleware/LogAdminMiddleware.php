<?php

namespace App\Http\Middleware;

use App\Jobs\WriteSystemLog;
use App\Repository\Admin\LogRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;

class LogAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $data = [];
        $user = auth()->guard('admin')->user();
        if ($user) {
            $data['user_id'] = $user->id;
            $data['user_name'] = $user->name;
        }

        $data['url'] = $request->url();
        $data['ua'] = $request->userAgent();
        $data['ip'] = (string) $request->getClientIp();
        $input = $request->all();

        if (isset($input['password'])) {
            $input['password'] = '******';
        }

        $data['data'] = http_build_query($input);

        LaravelLog::info('Admin area accessed', [
            'url' => $request->url(),
            'user_id' => $user->id ?? 'guest',
        ]);

        if (config('light.log_async_write')) {
            $data['updated_at'] = $data['created_at'] = Carbon::now();
            dispatch(new WriteSystemLog($data));
        } else {
            LogRepository::add($data);
        }

        return $next($request);
    }
}
