<?php
namespace admin\Middleware;

use QPHP\core\middleware\intf\IMiddleware;

class IndexMiddleware implements IMiddleware
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
        print_r('333333') ;
        //$fun();

        print_r('4444444');

    }
}
