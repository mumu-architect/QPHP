<?php

class Route
{

    public $route_arr = array();
    protected $module='';//模块名称
    protected $action='';//控制器名称
    protected $mod='';//方法名称

    private static $ins=null;
    public static function instance(){
        if(!self::$ins||!(self::$ins instanceof self)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    public function get($rule, $route = '', array $option = [], array $pattern = []){
        //'index/age','index/IndexAction/age
        $this->addRoute($rule, $route,$option , $pattern );
    }

    public function post($rule, $route, array $option = [], array $pattern = []){
        //'index/age','index/IndexAction/age
        $this->addRoute($rule, $route,$option , $pattern );
    }

    public function put($rule, $route = '', array $option = [], array $pattern = []){
        //'index/age','index/IndexAction/age
        $this->addRoute($rule, $route,$option , $pattern );
    }

    public function delete($rule, $route = '', array $option = [], array $pattern = []){
        $this->addRoute($rule, $route,$option , $pattern );
    }

    /**
     * 解析出模型，控制器，方法
     */
    public function parsePath(){

        $url = '';
        if(isset($_SERVER['REQUEST_URI'])) {

            $url = $_SERVER['REQUEST_URI'];
            if (strpos($url, '.php') != false) {
                $url = preg_replace("/\/\w*.php/", "", $url);
            }

            $url = preg_replace("/^\//", "", $url);
            $url = preg_replace("/\/$/", "", $url);
            $url = preg_replace("/\?[\w=]*/", "", $url);
            $url = isset($this->route_arr[$url])?$this->route_arr[$url]:'';


        }
        if(empty($url)){
            return ;
        }

        $_arr=explode('/',$url);
        if(isset($_arr[0])&&!empty($_arr[0])){
            $module = $_arr[0];
            if(strpos($module,'?')!==false){
                $module = preg_replace("/\?[\w=&]*/","",$module);
            }
        }
        $this->module = isset($module)&&!empty($module)?strtolower($module):'';
        if(isset($_arr[1])&&!empty($_arr[1])){
            $action = $_arr[1];
            if(strpos($_arr[1],'?')!==false){
                $action = preg_replace("/\?[\w=&]*/","",$action);
            }
        }
        $this->action = isset($action)&&!empty($action)?ucfirst($action):'';
        if(isset($_arr[2])&&!empty($_arr[2])){
            $mod = $_arr[2];
            if(strpos($_arr[2],'?')!==false){
                $mod = preg_replace("/\?[\w=&]*/","",$mod);
            }
        }
        $this->mod = isset($mod)&&!empty($mod)?$mod:'';
    }

    public function __get($name){
        return $this->$name;
    }
    /**
     * 添加路由到数组
     * @param $rule
     * @param string $route
     * @param array $option
     * @param array $pattern
     */
    protected function addRoute($rule, $route = '', array $option = [], array $pattern = []){
        $this->route_arr[$rule] = $route;
    }

    public function __call($method, $parameters)
    {
        return (new self())->$method(...$parameters);
    }

    public static function __callStatic($method,$parameters){
        return (new static())->$method(...$parameters);

    }
}
