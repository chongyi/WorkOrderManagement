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
            'index'  => 'host.work.group.work-order.index',
            'create' => 'host.work.group.work-order.create',
        ]
    ]);

    Route::resource('work-order.messages', 'Work\WorkOrderMessageController', [
        'names' => [
            'index' => 'host.work.work-order.message.index'
        ],
        'only'  => ['index', 'store']
    ]);

    Route::resource('work-order', 'Work\WorkOrderController', [
        'names' => [
            'index'  => 'host.work.work-order.index',
            'create' => 'host.work.work-order.create',
            'show'   => 'host.work.work-order.show',
        ]
    ]);

    Route::resource('my-work-order', 'Work\MyWorkOrderController', [
        'names' => [
            'index' => 'host.work.my-work-order.index'
        ]
    ]);

    Route::controller('my-watchlist', 'Communication\MyWatchlistController', [
        'putWorkOrder' => 'host.communication.my-watchlist.work-order',
        'putGroup'     => 'host.communication.my-watchlist.group'
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

    Route::get('message/status',
        ['as' => 'host.communication.message.status', 'uses' => 'Communication\MessageController@status']);
    Route::resource('message', 'Communication\MessageController', [
        'names' => [
            'index'  => 'host.communication.message.index',
            'update' => 'host.communication.message.update'
        ]
    ]);
});

Route::get('route/{routeName}/url', [
    'as'   => 'host.resource-uri.getter',
    'uses' => function (\Illuminate\Http\Request $request, $routeName) {
        return call_user_func_array('route', [$routeName, $request->query->all()]);
    }
])->where('routeName', '[-a-zA-Z0-9]+(\.[-a-zA-Z0-9]+)*');

Route::controller('/', 'Web\HostController',
    [
        'getIndex'    => 'host.index',
        'getLogin'    => 'host.login',
        'getLogout'   => 'host.logout',
        'getRegister' => 'host.register',
    ]);