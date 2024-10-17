<?php
namespace QPHP\core\middleware\implement;

use Closure;
use QPHP\core\input\Input;
use QPHP\core\middleware\intf\IMiddleware;

class Middleware implements IMiddleware
{
    private array $stack =[];
    public array $input=[];

    private function __construct()
    {
        $input = new Input();
        $this->input = $input->parse();
    }

    /**
     * 工厂产出对象
     * @param $dbType
     * @param $table
     * @param $key
     * @return MysqlM
     */
    public static function newClass(){
        return new self();
    }

    /**
     * 添加中间件类
     * @param $className 类命加命名空间
     */
    public function add(array $className){
        $this->stack=$className;
    }

    /**
     * 运行中间件类中方法handle
     * @param array $request 请求数组
     * @return Closure
     */
    public function run(array $input) {
        $stack = array_values($this->stack);
        $stackInput=[];
        $tempStack=$stack;
        array_pop($tempStack);
        while ($middleware = array_pop($stack)){
            $stackInput[]=function($input) use ($middleware,$tempStack){
                return $this->recursionHandle($input,$middleware,$tempStack);
            };
        }
        return reset($stackInput)($input);
    }

    /**
     * 递归handle函数
     * @param $input
     * @param $middleware
     * @param $tempStack
     * @return |null
     */
    private function recursionHandle($input,$middleware,$tempStack) {
        if($middleware){
            $middleware::handle($input,function($input) use($tempStack){
                if($next=array_pop($tempStack)){
                    $this->recursionHandle($input,$next,$tempStack);
                }
            });
        }
        return null;
    }


    /**
     * 中间件助手函数
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public static function handle(array $input,$next)
    {
        // TODO: Implement handle() method.
    }
}
