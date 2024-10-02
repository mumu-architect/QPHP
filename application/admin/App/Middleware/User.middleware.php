<?php
namespace admin\Middleware;

use QPHP\core\middleware\intf\IMiddleware;

class UserMiddleware implements IMiddleware
{


    /**
     * 中间件助手函数
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public static function handle(array $input)
    {
        // TODO: Implement handle() method.
        echo '5555';
        //$fun();
        echo '6666';

    }
}
