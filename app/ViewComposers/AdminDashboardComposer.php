<?php

namespace App\ViewComposers;

use App\User;
use App\Models\Role;
use App\Models\Permission;

class AdminDashboardComposer
{
    public static function dashboardCount()
    {
        view()->composer('admin.dashboard.includes.count-block', function ($view) {
            $usercount = User::get()->count();
            $rolecount = Role::get()->count();
            $permissioncount = Permission::get()->count();
            $view->with(compact('usercount','rolecount','permissioncount'));
        });
    }

    public static function activeRoles()
    {
        view()->composer('admin.page.sidebar', function ($view) {
            $activeRoles = Role::where('status',1)->where('name','!=','admin')->get();
            $view->with(compact('activeRoles'));
        });
    }

    public static function latesUser()
    {
        view()->composer('admin.dashboard.index', function ($view) {
            $latesUser = User::orderBy('id','DESC')->get()->take(8);
            $view->with(compact('latesUser'));
        });
    }
}