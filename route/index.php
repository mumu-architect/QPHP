<?php
// 接口
//获取token
$route = Route::instance();
$route->get('index/age','index/IndexAction/age');

$route->get('index/name','index/IndexAction/name');

