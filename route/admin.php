<?php
namespace route;
use QPHP\core\route\Route;
// 接口
//获取token
//$route = Route::instance();

Route::get('admin/age/1','admin/index/age')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();

Route::get('admin/name/1','admin/index/name');
Route::get('admin/index','admin/user/index')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;
Route::get('admin/rcache','admin/user/rCache')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;
Route::get('admin/mcache','admin/user/mCache')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;
Route::get('admin/test','admin/user/testValidate')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;

Route::get('admin/testRsa','admin/index/testRsa')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;
Route::get('admin/testJwt','admin/index/testJwt')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;

Route::get('admin/login','admin/login/login')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;
Route::get('admin/shortToken','admin/login/getShortToken')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;
Route::get('admin/testError','admin/index/testError')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;

Route::middleware('admin\middleware\LoginMiddleware')->middleware('admin\middleware\IndexMiddleware')->middleware('admin\middleware\UserMiddleware')->get('admin/testMiddleware','admin/index/testMiddleware')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
;
//分组1
Route::middleware('admin\middleware\UserMiddleware')->group('admin/',function(){
    Route::get('age','admin/index/age');
//    Route::get('name','admin/index/name');
    Route::get('userAdd','admin/user/add');
    Route::get('userEdit','admin/user/edit');
    Route::get('userDel','admin/user/del');
})->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
//分组2
Route::middleware('admin\middleware\LoginMiddleware')->group('admin1/',function(){
    Route::get('name','admin/index/name');
})->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
