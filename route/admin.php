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
//
Route::group('admin/',function(){
    Route::get('age','admin/index/age');
    Route::get('name','admin/index/name');
    Route::get('userAdd','admin/user/add');
    Route::get('userEdit','admin/user/edit');
    Route::get('userDel','admin/user/del');

})->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
