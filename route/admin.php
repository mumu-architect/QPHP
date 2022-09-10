<?php
namespace route;
use QPHP\core\route\Route;
// 接口
//获取token
//$route = Route::instance();

Route::get('admin/age/1','admin/index/age')->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();

Route::get('admin/name/1','admin/index/name');
//
Route::group('admin/',function(){
    Route::get('age','admin/index/age');
    Route::get('name','admin/index/name');
})->header('Access-Control-Allow-Origin','*')->header('Access-Control-Allow-Credentials', 'true')->allowCrossDomain();
