<?php
/**
 * Created by Chongyi.
 * Date: 2015/12/10 0010
 * Time: 下午 12:49
 */

namespace App\Http\Controllers\Web;


use App\WorkOrderManagement\Work\Group;

trait BaseDataBootstrap
{
    protected function bootBaseData()
    {
        if (\Auth::check()) {
            $groups = Group::enable()->orderBy('created_at', 'desc')->get();
        } else {
            $groups = [];
        }

        view()->share('enableGroups', $groups);
        view()->share('currentRouteName', \Route::currentRouteName());
    }
}