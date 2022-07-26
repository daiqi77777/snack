<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::namespace('\App\Http\Controllers')
    ->prefix(config("snack.route_prefix", "api"))
    ->group(function () {
        Route::group([''], function () {
            Route::post("auth/login", "LoginController@login");
            Route::get("/captcha", "CaptchaController@generate");
        });
        Route::group(['middleware' => config("snack.middleware.auth", ['auth:sanctum'])], function () {
            Route::get('permission-user-all', 'PermissionController@allUserPermission')->name("permission.show");
            Route::post("auth/logout", "LoginController@logout");
            Route::get('my-menu', 'MenuController@my')->name("menu.my");
            Route::patch('user-change-password', 'ChangePasswordController@changePassword')->name("user.change-password");
        });



        Route::middleware(config("snack.middleware.permission", ['auth:sanctum', 'snack.permission']))
            ->group(function ($router) {
                $router->apiResources([
                    'role' => 'RoleController',
                    'permission' => 'PermissionController',
                    'admin-user' => 'AdminUserController',
                    'permission-group' => 'PermissionGroupController',
                    'menu' => 'MenuController',
                ]);

                $router->get('role/{id}/permissions', 'RoleController@permissions')->name('role.permissions');
                $router->put('role/{id}/permissions', 'RoleController@assignPermissions')->name('role.assign-permissions');
                $router->get('guard-name-roles/{guardName}', 'RoleController@guardNameRoles')->name('role.guard-name-roles');
                $router->get('admin-user/{id}/roles/{guard}', 'AdminUserController@roles')->name('admin-user.roles');
                $router->put('admin-user/{id}/roles/{guard}', 'AdminUserController@assignRoles')->name('admin-user.assign-roles');
                $router->get('guard-name-for-permissions/{guardName}', 'PermissionGroupController@guardNameForPermissions')
                    ->name('permission-group.guard-name-for-permission');
                $router->get("permission-group-all", "PermissionGroupController@all")->name("permission-group.all");
            });
    });