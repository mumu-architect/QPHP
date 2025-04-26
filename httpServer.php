<?php
namespace index;


use Swoole\Http\Server;


$http = new Server('0.0.0.0', 9501);
$http->set([
    'worker_num' => 2
]);
$http->on('Request', function ($request, $response) {
    // 模拟一个ThinkPHP的响应
    ob_start(); // 开启输出缓冲
    $_SERVER = [];
    foreach ($request->header as $key => $val) {
        $_SERVER[strtoupper($key)] = $val;
    }
    $_SERVER['PATH_INFO'] = $request->server['path_info'];
    $_SERVER['HTTP_METHOD'] = $request->server['request_method'];
    $_GET = $request->get;
    $_POST = $request->post;
    $_COOKIE = $request->cookie;

    echo 11111111;
//    $trace='';
//    $message='';
//    try {
//        //[ 应用入口文件 ]
//        //加载基础文件
//        require __DIR__ . '/QPHP/base.php';
//        //支持事先使用静态方法设置Request对象和Config对象
//        //执行应用并响应
//        Base::run();
//    }catch (\Exception $e){
//        $trace = $e->getTrace();
//        $message=$e->getMessage();
//    }


    $content = ob_get_contents(); // 获取输出缓冲内容
    ob_end_clean(); // 清空并关闭输出缓冲



    $response->header('Content-Type', 'text/html; charset=utf-8');
    $response->end($content.'<h1>Hello Swoole. #' . rand(1000, 9999) .'</h1>'.$trace.$message);
});

$http->start();




