<?php
/**
 * 总配置文件
 */
$config['app'] = array(
    'RPC_RUN'=>false,//是否开启rpc
    'ROUTE_PATH'=>true,//是否开启路由模式
    'APP_DEBUG'=> true,//debug//开启错误是否显示到页面
    'APP_LANG'=>true,//是否开启多语言
    //MODULE未测试通过
    'MODULE'=>array(//开启相应模块 'MYSQL_OPEN'=>true,关闭 'MYSQL_OPEN'=>false,
        'MYSQL_OPEN'=>true,
        'ORACLE_OPEN'=>true,
        'REDIS_OPEN'=>true,
        'MEMCACHE_OPEN'=>true,
        'LANGUAGE_OPEN'=>true,
    ),



    //全局数据库配置
    'mem_0' => array(
        'host' => '127.0.0.1',
        'port' => '11211'
    ),

    'redis_0' => array(
        'host' => '127.0.0.1',
        'port' => '6379'
    ),
);
