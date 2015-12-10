<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => 'auth', 'namespace' => 'Web'], function () {

    Route::resource('group.work-orders', 'Work\GroupWorkOrderController', [
        'names' => [
            'index' => 'host.work.group.work-order.index',
            'create' => 'host.work.group.work-order.create'
        ]
    ]);

    Route::resource('group', 'Work\GroupController', [
        'names' => [
            'index'  => 'host.work.group.index',
            'create' => 'host.work.group.create',
            'edit'   => 'host.work.group.edit',
            'update' => 'host.work.group.update',
            'show'   => 'host.work.group.show',
        ],
    ]);
});

Route::controller('/', 'Web\HostController',
    [
        'getIndex'    => 'host.index',
        'getLogin'    => 'host.login',
        'getLogout'   => 'host.logout',
        'getRegister' => 'host.register',
    ]);