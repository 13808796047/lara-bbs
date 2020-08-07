<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{

    public function handle($request, Closure $next)
    {
        // 三个判断:
        // 1: 如果用户已经登录
        // 2: 并且还未认证Email
        // 3: 并且访问的不是email验证相关URL或者退出的URL
        if ($request->user() && !$request->user()->hasVerifiedEmail() && !$request->is('email/*', 'logout')) {
            //根据用户端返回对应的内容
            return $request->expectsJson() ? abort(403, '您的邮箱没有验证!') : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
