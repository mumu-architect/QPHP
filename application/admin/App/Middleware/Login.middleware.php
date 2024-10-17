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
    public static function handle(array $input,\Closure $next)
    {

        // TODO: Implement handle() method.
        print_r('LoginMiddleware before ');
        return $next($input);

    }
}
