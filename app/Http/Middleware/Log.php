<?php

namespace App\Http\Middleware;

use App\Jobs\WriteSystemLog;
use App\Repository\Admin\LogRepository;
use Carbon\Carbon;
use Closure;

class Log
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = '')
    {
        $data = [];
        if ($guard !== '') {
            $user = auth()->guard($guard)->user();
            if ($user) {
                $data['user_id'] = $user->id;
                $data['user_name'] = $user->name;
            }
        }
        $data['url'] = $request->url();
        $data['ua'] = $request->userAgent();
        $data['ip'] = (string) $request->getClientIp();
        $input = $request->all();

        if (isset($input['password'])) {
            $input['password'] = '******';
        }

        $data['data'] = http_build_query($input, false);

        if (config('light.log_async_write')) {
            $data['updated_at'] = $data['created_at'] = Carbon::now();
            dispatch(new WriteSystemLog($data));
        } else {
            LogRepository::add($data);
        }

        return $next($request);
    }
}
