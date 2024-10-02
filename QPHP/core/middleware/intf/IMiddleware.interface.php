<?php
namespace QPHP\core\middleware\intf;

interface IMiddleware
{
    /**
     * 中间件助手函数
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public static function handle(array $input);
}
