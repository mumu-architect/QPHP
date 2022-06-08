<?php
// 接口
//获取token
$route = Route::instance();
$route->get('index/age','index/index/age');

$route->get('index/name','index/index/name');
$route->post('index/name','index/index/addName');
$route->delete('index/name','index/index/delName');
$route->put('index/name','index/index/putName');
