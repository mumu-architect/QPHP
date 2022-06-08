<?php
// 接口
//获取token
$route = Route::instance();
$route->get('admin/age','admin/IndexAction/age');

$route->get('admin/name','admin/IndexAction/name');

