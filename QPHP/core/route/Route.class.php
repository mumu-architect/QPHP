<?php
namespace QPHP\core\route;

use QPHP\core\func\Func;

class Route
{

    protected $rules = array();
    protected $group = false;//默认分组禁用
    protected $isGroup=false;
    protected $newRule=array();//最新一条规则
    protected $newGroupRules=array();//最新一条组规则
    protected $groupRules=array();//分组的规则
    protected $module='';//模块名称
    protected $action='';//控制器名称
    protected $mod='';//方法名称
    protected $headerSet=array();//header头数组
    protected $allowCrossDomain=false;//more跨域禁用

    private static $ins=null;
    private $func = '';//公共方法类
    public static function instance(){
        if(!self::$ins||!(self::$ins instanceof self)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    private function __construct()
    {
        $this->func=Func::instance();
    }

    public function __destruct(){
        $this->module='';
        $this->action='';
        $this->mod='';
	}

    /**
     * 加载路由配置文件
     * @throws Exception
     */
    protected function requireRouteFileUrl(){
        $conf = array(
            'RouteUrl'=>APP_PATH .'route'//前端路由文件
        );
        $this->func::requireFileDir($conf);
    }

    protected  function get($rule, $route = '', array $option = [], array $pattern = []){
        //'index/age','index/IndexAction/age
        $this->addRoute($rule, $route,'GET',$option , $pattern );
        return $this;
    }

    protected function post($rule, $route, array $option = [], array $pattern = []){
        //'index/age','index/IndexAction/age
        $this->addRoute($rule, $route,'POST',$option , $pattern );
        return $this;
    }

    protected function put($rule, $route = '', array $option = [], array $pattern = []){
        //'index/age','index/IndexAction/age
        $this->addRoute($rule, $route,'PUT',$option , $pattern );
        return $this;
    }
    protected function patch($rule, $route = '', array $option = [], array $pattern = []){
        $this->addRoute($rule, $route,'PATCH',$option , $pattern );
        return $this;
    }

    protected function delete($rule, $route = '', array $option = [], array $pattern = []){
        $this->addRoute($rule, $route,'DELETE',$option , $pattern );
        return $this;
    }

    protected function group($prefix,callable $fun){

        $this->isGroup=true;
        $this->group=true;
        $fun();
        $this->group=false;
        $rulesArr=array();
        foreach ($this->newGroupRules as $k=>&$v){
            foreach ($v as $key=>&$val){
                $rulesArr[$k][$prefix.$key] = array('route'=>$val['route'],'option'=>[]);
            }
        }
       // var_dump($rulesArr);
        //var_dump($this->rules);
        $this->newGroupRules=$rulesArr;
        $this->rules= array_merge_recursive($this->rules,$rulesArr);
        //var_dump($this->rules);
        unset($rulesArr);
        return $this;
    }

    protected function header($param='Access-Control-Allow-Origin',$value='*'){
        $this->headerSet[$param]= $value;
        return $this;
    }
    //允许跨域
    protected function allowCrossDomain(){

        $this->allowCrossDomain=true;
        $option = $this->allowCrossDomain?array('allow_cross_domain'=>$this->headerSet):[];
        $this->allowCrossDomain=false;
        if($this->isGroup){
            $this->isGroup=false;
            foreach ($this->newGroupRules as $k=>&$v){
                foreach ($v as $key=>&$val)
                    $this->newGroupRules[$k][$key]=array('route'=>$val['route'],'option'=>$option);
            }
            $this->rules=  array_merge_recursive($this->rules,$this->newGroupRules);
            $this->newGroupRules=array();
        }else{
            foreach ($this->newRule as $k=>&$v){
                foreach ($v as $key=>&$val)
                    $this->newRule[$k][$key]=array('route'=>$val['route'],'option'=>$option);
            }
           // var_dump($this->newRule);
            $this->rules= array_merge_recursive($this->rules,$this->newRule);
            $this->newRule=array();
        }

    }
    //开启跨域
    private function openCrossDomain(){
        foreach ($this->headerSet as $key=>&$val){
            header($key.':'.$val);
        }
        $this->headerSet=array();
    }

    //禁用跨域
    protected function prohibitCrossDomain(){
        foreach ($this->headerSet as $key=>&$val){
            header_remove($key);
        }
    }

    //获取最后插入的数据router
    private function getLastInsertedRoute(){
        foreach ($this->rules as $k=>&$v){
            foreach ($v as $key=>&$val)
                $this->rules[$k][$key]['route']=is_array($val['route'])?$val['route'][count($val['route'])-1]:$val['route'];
        }
    }

    /**
     * 解析出模型，控制器，方法
     */
    protected function parsePath(){
        //获取最后插入的数据router
        $this->getLastInsertedRoute();

        $url = '';
        if(isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
            if (strpos($url, '.php') != false) {
                $url = preg_replace("/\/\w*.php/", "", $url);
            }

            if(strpos($url,'?')!==false){
                //$url = preg_replace("/\?[\w=&]*/", "", $url);
                preg_match('/^([^?]*).*$/', $url, $matches);
                $url = $matches[1];
            }

            $url = preg_replace("/^\//", "", $url);
            $url = preg_replace("/\/$/", "", $url);
            //判断请求类型
            if(isset($_SERVER['REQUEST_METHOD'])&&$_SERVER['REQUEST_METHOD']){
                $method =strtolower($_SERVER['REQUEST_METHOD']);
                if(isset($this->rules[$method][$url])){
                    $url_arr = $this->rules[$method][$url];
                }
            }
        }
        if(!isset($url_arr)||empty($url_arr)||empty($url_arr['route'])){
            //清空头部设置
            $this->headerSet=array();
            return ;
        }
        $_arr=explode('/',$url_arr['route']);
        if(isset($_arr[0])&&!empty($_arr[0])){
            $module = $_arr[0];
        }
        $this->module = isset($module)&&!empty($module)?strtolower($module):'';
        if(isset($_arr[1])&&!empty($_arr[1])){
            $action = $_arr[1];
        }
        $this->action = isset($action)&&!empty($action)?ucfirst($action).'Action':'';
        if(isset($_arr[2])&&!empty($_arr[2])){
            $mod = $_arr[2];
        }
        $this->mod = isset($mod)&&!empty($mod)?$mod:'';
        //开启跨域
        if(isset($url_arr['option']['allow_cross_domain'])&&!empty($url_arr['option']['allow_cross_domain'])){
            $this->openCrossDomain();
        }

    }

    public function __get($name){
        return $this->$name;
    }

    protected function rule($rule, $route, $method = '*', array $option = [], array $pattern = []){
        //return $this->addRule($rule, $route, $method, $option, $pattern);
    }
    /**
     * 添加路由到数组
     * @param $rule
     * @param string $route
     * @param array $option
     * @param array $pattern
     */
    protected function addRoute($rule, $route = '', $method = '*',  $option = [],  $pattern = []){
        // 读取路由标识
        $method = strtolower($method);
        if($this->group){
            $this->newGroupRules[$method][$rule]=array('route'=>$route,'option'=>$option);
        }else{
            $this->newRule=array();
            $this->newRule=array($method=>array($rule=>array('route'=>$route,'option'=>$option)));
            $this->rules[$method][$rule] = array('route'=>$route,'option'=>$option);
        }
    }


    public function __call($method, $parameters)
    {
            return $this->$method(...$parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        return (self::instance())->$method(...$parameters);
    }

}
