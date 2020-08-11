<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
    public function permissionDenied()
    {
        // 如果当前用户有权限访问后台,直接跳转访问
        if (\config('administrator.permission')()) {
            return \redirect(url(\config('administrator.uri')), 302);
        }
        // 否则使用视图
        return view('permission_denied');
    }
}
