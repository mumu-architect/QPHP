<?php
namespace admin\Middleware;

use QPHP\core\middleware\intf\IMiddleware;

class LoginMiddleware implements IMiddleware
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
        echo '111111111111';
        //$fun();
        echo '2222222222222';

    }
}
