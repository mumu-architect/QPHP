<?php
namespace qphp\Validate;

class Validate implements ValidateInterface {

    /**
     * 当前验证规则
     * @var array
     */
    protected array $rule = [];

    /**
     * 需要验证的参数
     * @var array
     */
    private $data = [];

    /**
     * 场景
     * @var array
     */
    protected array $scene =[];

    /**
     * 当前场景
     * @var array
     */
    private string $onScene ='';

    /**
     * 验证提示信息
     * @var array
     */
    protected array $message = [];

    /**
     * 错误信息
     * @var array
     * */
    protected array $error = [];


    //默认错误提示
    private array $error_msg = [
        'require' => ':attribute不能为空',
        'strLength' => ':attribute长度必须在:1-:2范围内',
        'number' => ':attribute必须为数字',
        'array' => ':attribute必须为数组',
        'float' => ':attribute必须为浮点数',
        'boolean' => ':attribute必须为布尔值',
        'email' => ':attribute必须为正确的邮件地址',
        'url' => ':attribute必须为正确的url格式',
        'ip' => ':attribute必须为正确的ip地址',
        'timestamp' => ':attribute必须为正确的时间戳格式',
        'date' => ':attribute必须为正确的日期格式',
        'regex' => ':attribute格式不正确',
        'in' => ':attribute必须在:range内',
        'notIn' => ':attribute必须不在:range内',
        'between' => ':attribute必须在:1-:2范围内',
        'notBetween' => ':attribute必须不在:1-:2范围内',
        'max' => ':attribute最大值为:1',
        'min' => ':attribute最小值为:1',
        'length' => ':attribute长度必须为:1',
        'confirm' => ':attribute和:1不一致',
        'gt' => ':attribute必须大于:1',
        'lt' => ':attribute必须小于:1',
        'egt' => ':attribute必须大于等于:1',
        'elt' => ':attribute必须小于等于:1',
        'eq' => ':attribute必须等于:1',
    ];

    //默认错误提示
    private array $systemErrorMsg = [
        'error' => 'Unknown error.',
        'dataError' => 'Validation data must be in array format.',
        'dataFiledError'=>'Field [:attribute] does not exist in the data.',
        'ruleMessage' => '[:attribute] does not have a language pack.',
        'funFilterError'=>'[:attribute] custom function filtering failed.',
        'regexFilterError'=>'[:attribute] regular expression filtering failed.',
        'ruleFilterError'=>'[:attribute] system rule filtering failed.'
    ];


    /**
     * TODO:加载规则数据，可以不使用直接继承
     * @param $rule
     * @return Validate
     */
    public function rule($rule): ValidateInterface
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * TODO:加载消息数据，可以不使用直接继承
     * @param array $message
     * @return Validate
     */
    public function message(array $message): ValidateInterface
    {
        $this->message = $message;
        return $this;
    }
    /**
     * TODO:加载场景数据 ，可以不使用直接继承
     * @param array $scene
     * @return Validate
     */
    public function scene(array $scene): ValidateInterface
    {
        $this->scene = $scene;
        return $this;
    }
    /**
     * 加载验证过滤数据
     * @param array $data
     * @return Validate
     */
    public function check(array $data): ValidateInterface
    {
        if(!is_array($data)) {
            array_push($this->error,  $this->systemErrorMsg['dataError']);
        }
        $this->data = $data;
        return $this;
    }


    /**
     * 加载当前场景数据
     * @param array $scene
     * @return Validate
     */
    public function onScene(string $scene): ValidateInterface
    {
        $this->onScene = $scene;
        return $this;
    }

    /**
     * 判断是否是系统函数
     * @param $func
     * @return bool
     */
    private function isInternal($func):bool
    {
        $func_arr = get_defined_functions();
        if (in_array($func, $func_arr['internal'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 正则验证函数
     * @param $rule
     * @param $data
     * @return bool
     */
    private function regexValidateRule($rule, $data):bool
    {
        return filter_var($data, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => $rule]]);

    }

    /**
     * 正则过滤函数
     * @param $rule
     * @param $data
     * @return bool
     */
    private function regexFilterRule($rule, $data):mixed
    {
        return preg_replace($rule, '', $data);
    }

    /**
     * 调用当前类的自定义方法
     * @param $method
     * @param $param
     * @param null $obj
     * @return bool
     */
    private function callUserFunc($method, $param,$obj=null):mixed
    {
        if (!(isset($obj)&&is_object($obj))) {
            $obj = $this;
        }
        //return call_user_func_array(array($className, $method), array($dbKey,$dbType));
        return call_user_func_array([$obj, $method], $param);
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private  function strLength($rule, $data):bool
    {
        $rule = explode(',', $rule);
        return strlen($data) >= $rule[0] && strlen($data) <= $rule[1];
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private function in($rule, $data):bool
    {
        if (!is_array($rule)) {
            $rule = explode(',', $rule);
        }
        return in_array($data, $rule);
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private function notIn($rule, $data):bool
    {
        return !$this->in($data, $rule);
    }


    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private  function between($rule, $data):bool
    {
        $rule = explode(',', $rule);
        return $data >= $rule[0] && $data <= $rule[1];
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private function notBetween($rule, $data):bool
    {
        return !$this->between($rule, $data);
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private  function max($rule, $data):bool
    {
        return $data <= $rule;
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private  function min($rule, $data):bool
    {
        return $data >= $rule;
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private  function length($rule, $data):bool
    {
        $length = is_array($data) ? count($data) : strlen($data);
        return $length == $rule;
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private  function confirm($rule, $data):bool
    {
        return isset($this->data[$rule]) && $data == $this->data[$rule];
    }

    private  function gt($rule, $data):bool
    {
        return $data > $rule;
    }

    private  function lt($rule, $data):bool
    {
        return $data < $rule;
    }

    private  function egt($rule, $data):bool
    {
        return $data >= $rule;
    }

    private  function elt($rule, $data):bool
    {
        return $data <= $rule;
    }

    private  function eq($rule, $data):bool
    {
        return $data == $rule;
    }

    /**
     * 返回第一条错误信息
     * @return mixed|null
     */
    public function getError():string
    {
        return count($this->error) > 0 ? $this->error[0] : '';
    }

    /**
     * 返回所有的错误信息
     * @return array
     */
    public function getAllErrors():array
    {
        return $this->error;
    }

    /**
     * 获取当前数据
     * @return array
     */
    public function getData():array
    {
        return $this->data;
    }

    /**
     * 获取系统自定义错误信息
     * @param $ruleName
     * @param string $systemErrorMsgkey
     */
    private function getSystemErrorMsg($ruleName,$systemErrorMsgkey='error'){
        return preg_replace('/(:attribute)/i', $ruleName, $this->systemErrorMsg[$systemErrorMsgkey]);
    }

    /**
     * 获取当前验证规则的消息
     * @param $errorKey
     * @param $name
     * @param $systemRule
     * @return bool|mixed
     */
    private function getMessage($message,$ruleName,$messageKey,$systemErrorMsgkey='error'){
        $error_info = $this->getSystemErrorMsg( $ruleName, $systemErrorMsgkey);
        if(array_key_exists($messageKey,$message[$ruleName])){
            $error_info=$message[$ruleName][$messageKey];
        }
        return $error_info;
    }

    /**
     * [getSystemRuleMessage 获取系统验证验证失败的信息]
     * @param  [type] $name [字段名]
     * @param  [type] $systemRule [系统验证规则]
     * @return [type]       [string OR fail false]
     */
    private function getSystemRuleMessage($name, $systemRule,$message,$ruleName)
    {
        $value1 = '';
        $value2 = '';
        $range = '';
        $error_key = $systemRule;
        if (strpos($systemRule, ':')) {
            $exp_arr = explode(':', $systemRule);
            $error_key = $exp_arr[0];
            $range = $exp_arr[1];
            $message_value = explode(',', $exp_arr[1]);
            $value1 = isset($message_value[0]) ? $message_value[0] : '';
            $value2 = isset($message_value[1]) ? $message_value[1] : '';
        }
        //有自定义的消息优先使用
        $messageKey = 'validationRule.systemRule.'.$error_key;
        if(array_key_exists($messageKey,$message[$ruleName])){
            return $error_info=$message[$ruleName][$messageKey];
        }
        //没有返回系统自定义的
        if (isset($this->error_msg[$error_key])) {
            return str_replace([':attribute', ':range', ':1', ':2'], [$name, $range, $value1, $value2], $this->error_msg[$error_key]);
        }
        return false;
    }

    /**
     * 判断rule规则是否合规
     * @param $rule
     * @return bool
     */
    private function isRuleCorrect($rule){
        if(!(array_key_exists('fieldName',$rule)&&!empty($rule['fieldName']))){
            return false;
        }
        if(!(array_key_exists('ruleName',$rule)&&!empty($rule['ruleName']))){
            return false;
        }
        return true;
    }

    /**
     * 判断数据是否存在某字段，合法
     * @param $data
     * @param $fieldName
     * @return bool
     */
    private function isDataCorrect($data,$fieldName){
        if(!(array_key_exists($fieldName,$data))){
            $error_info = $this->getSystemErrorMsg( $fieldName, 'dataFiledError');
            array_push($this->error,  $error_info);
            return false;
        }
        return true;
    }

    /**
     * 系统规则验证
     * @param $validationRuleSystemRule
     * @param $fieldValue
     * @param $ruleName
     * @param $message
     * @param $messageFieldName
     */
    private function checkSystemValidation($validationRuleSystemRule,$fieldValue, $ruleName,$message,$messageFieldName)
    {
        $rules = explode('|', $validationRuleSystemRule);
        foreach ($rules as $i => $rule) {
            $result = $this->checkSystemRule($rule, $fieldValue);
            if (!$result) {
                $error_info = $this->getSystemRuleMessage($messageFieldName, $rule,$message,$ruleName);
                if ($error_info) {
                    array_push($this->error, $error_info);
                }
            }
        }
    }
    /**
     * 系统单条规则验证规则验证
     * @param string $rule
     * @param $validate_data
     * @return bool|mixed
     */
    public function checkSystemRule(string $rule,$validateData)
    {

        switch ($rule) {
            case 'require':
                return $validateData != '';
                break;
            case 'number':
                return filter_var($validateData, FILTER_VALIDATE_INT);
                break;
            case 'array':
                return is_array($validateData);
                break;
            case 'float':
                return filter_var($validateData, FILTER_VALIDATE_FLOAT);
                break;
            case 'boolean':
                return filter_var($validateData, FILTER_VALIDATE_BOOLEAN);
                break;
            case 'email':
                return filter_var($validateData, FILTER_VALIDATE_EMAIL);
                break;
            case 'url':
                return filter_var($validateData, FILTER_VALIDATE_URL);
            case 'ip':
                return filter_var($validateData, FILTER_VALIDATE_IP);
                break;
            case 'timestamp':
                return strtotime(date('Y-m-d H:i:s', $validateData)) == $validateData;
                break;
            case 'date': //2017-11-17 12:12:12
                return strtotime($validateData)?true:false;
                break;
            default:
                if (strpos($rule, ':')) {
                    $rule_arr = explode(':', $rule);
                    $func_name = substr($rule, strpos($rule, ':') + 1);
                    return call_user_func_array([$this, $rule_arr[0]], [$func_name, $validateData]);
                } else {
                    return call_user_func_array([$this, $rule], [$rule, $validateData]);
                }
        }
    }

    /**
     * 自定义正则验证
     * @param $validationRuleRegex
     * @param $fieldValue
     * @param $ruleName
     * @param $message
     */
    private function regexValidate($validationRuleRegex,$fieldValue,$ruleName,$message){
        foreach ($validationRuleRegex as $k => $regexRule){
            $res = $this->regexValidateRule($regexRule,$fieldValue);
            $messageKey = 'validationRule.regex.'.$k;
            $error_info = $this->getMessage($message,$ruleName,$messageKey,'ruleMessage');
            if(!$res){
                array_push($this->error, $error_info);
            }
        }
    }

    /**
     * 自定义方法验证
     * @param $validationRuleFunc
     * @param $fieldValue
     * @param $ruleName
     * @param $message
     */
    private function funcValidate($validationRuleFunc,$fieldValue,$ruleName,$message){
        foreach ($validationRuleFunc as $k => $methodName){
            $res=$this->callUserFunc($methodName,array(
                $fieldValue
            ));
            $messageKey = 'validationRule.func.'.$methodName;
            $error_info = $this->getMessage($message,$ruleName,$messageKey,'ruleMessage');
            if(!$res){
                array_push($this->error, $error_info);
            }
        }
    }

    /**
     * 系统单条规则过滤
     * @param string $rule
     * @param $validateData
     * @return false|int|mixed|string
     */
    private function systemFilterRule(string $rule, $validateData):mixed
    {
        switch ($rule) {
            case 'number':
                return filter_var($validateData, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'float':
                return filter_var($validateData, FILTER_SANITIZE_NUMBER_FLOAT);
                break;
            case 'string':
                return filter_var($validateData, FILTER_SANITIZE_STRING);
                break;
            case 'url':
                return filter_var($validateData, FILTER_SANITIZE_URL);
            case 'email':
                return filter_var($validateData, FILTER_SANITIZE_EMAIL);
                break;
            case 'encoded':
                return filter_var($validateData, FILTER_SANITIZE_ENCODED);
                break;
            case 'timestamp':
                return strtotime(date('Y-m-d H:i:s', $validateData));
                break;
            case 'date': //2017-11-17 12:12:12
                return date('Y-m-d H:i:s', $validateData);
                break;
            default:
                    return call_user_func_array([$this, $rule], [$rule, $validateData]);
        }
    }

    /**
     * 系统规则过滤
     * @param $filterRuleSystemRule
     * @param $fieldValue
     * @param $ruleName
     * @param $fieldName
     * @param $message
     */
    private function systemFilter($filterRuleSystemRule,$fieldValue, $ruleName,$fieldName,$message)
    {
        $rules = explode('|', $filterRuleSystemRule);
        foreach ($rules as $i => $rule) {
            $result = $this->systemFilterRule($rule, $fieldValue);
            if($result===false){
                $messageKey = 'fliter.regex.'.$rule;
                $error_info = $this->getMessage($message,$ruleName,$messageKey,'ruleFilterError');
                if ($error_info) {
                    array_push($this->error, $error_info);
                }
            }else {
                $fieldValue = $result;
            }
        }
        $this->data[$fieldName]=$fieldValue;
    }
    /**
     * 自定义正则过滤
     * @param $filterRegex
     * @param $fieldValue
     * @param $ruleName
     * @param $fieldName
     * @param $message
     */
    private function regexFilter($filterRegex,$fieldValue,$ruleName,$fieldName,$message){
        foreach ($filterRegex as $k => $regexRule){
            $result = $this->regexFilterRule($regexRule,$fieldValue);
            if($result===false){
                $messageKey = 'fliter.regex.'.$k;
                $error_info = $this->getMessage($message,$ruleName,$messageKey,'regexFilterError');
                array_push($this->error, $error_info);
            }else {
                $fieldValue = $result;
            }
        }
        $this->data[$fieldName]=$fieldValue;
    }

    /**
     * 自定义方法过滤
     * @param $filterFunc
     * @param $fieldValue
     * @param $ruleName
     * @param $fieldName
     * @param $message
     */
    private function funcFilter($filterFunc,$fieldValue,$ruleName,$fieldName,$message){

        foreach ($filterFunc as $k => $methodName){
            $result=$this->callUserFunc($methodName,array(
                $fieldValue
            ));
            $messageKey = 'fliter.func.'.$methodName;
            $error_info = $this->getMessage($message,$ruleName,$messageKey,'funFilterError');
            if($result===false){
                array_push($this->error, $error_info);
            }else{
                $fieldValue=$result;
            }
        }
        $this->data[$fieldName]=$fieldValue;
    }


    /**
     * 获取当前场景对应的规则
     * @return array
     */
    private function getSceneRules():array
    {
        $onScene = $this->onScene;
        $data = $this->data;
        $rules = $this->rule;
        $sceneRules = [];

        if(array_key_exists($onScene,$this->scene)&&!empty($this->scene[$onScene])){
            $sceneArr = $this->scene[$onScene];
            foreach ($rules as &$item){
                //判断规则是否合法
                if(!$this->isRuleCorrect($item)){
                    continue;
                }
                //判断数据是否合法
                if(!$this->isDataCorrect($data,$item['fieldName'])){
                    continue;
                }
                $ruleName=$item['ruleName'];
                if(in_array($ruleName,$sceneArr)){
                    $sceneRules[] = $item;
                }
            }
        }
        return $sceneRules;
    }

    /**
     * 主验证方法
     * @return bool
     */
    public function Validate(): bool
    {
        //$data = $this->data;
        $rules = $this->rule;
        $message = $this->message;
        //获取当前场景规则
        $sceneRules = $this->getSceneRules();

        //执行验证
        foreach ($sceneRules as $key => &$item) {
            //判断规则是否合法
            if(!$this->isRuleCorrect($item)){
                continue;
            }
            //判断数据是否合法
            if(!$this->isDataCorrect($this->data,$item['fieldName'])){
                continue;
            }
            $fieldValue = $this->data[$item['fieldName']];
            //系统规则验证
            if(array_key_exists('validationRule',$item)&&array_key_exists('systemRule',$item['validationRule'])&&!empty($item['validationRule']['systemRule'])){
                $ruleName=$item['ruleName'];
                $validationRuleSystemRule=$item['validationRule']['systemRule'];
                $messageFieldName=$item['ruleName'].'.'.$item['fieldName'];
                $messageFieldNameKey = 'fieldName.'.$item['fieldName'];
                if(array_key_exists($messageFieldNameKey,$message[$item['ruleName']])){
                    $messageFieldName=$message[$item['ruleName']][$messageFieldNameKey];
                }
                $this->checkSystemValidation($validationRuleSystemRule,$fieldValue, $ruleName,$message,$messageFieldName);
            }
            //自定义正则验证
            if(array_key_exists('validationRule',$item)&&array_key_exists('regex',$item['validationRule'])&&!empty($item['validationRule']['regex'])){
                $ruleName=$item['ruleName'];
                $validationRuleRegex=$item['validationRule']['regex'];
                $this->regexValidate($validationRuleRegex,$fieldValue,$ruleName,$message);
            }
            //自定义方法验证
            if(array_key_exists('validationRule',$item)&&array_key_exists('func',$item['validationRule'])&&!empty($item['validationRule']['func'])){
                $ruleName=$item['ruleName'];
                $validationRulefunc=$item['validationRule']['func'];
                $this->funcValidate($validationRulefunc,$fieldValue,$ruleName,$message);
            }

            /**
             * 过滤
             */
            //系统规则过滤
            $fieldValue = $this->data[$item['fieldName']];
            if(array_key_exists('fliter',$item)&&array_key_exists('systemFilter',$item['fliter'])&&!empty($item['fliter']['systemFilter'])){
                $ruleName=$item['ruleName'];
                $fieldName=$item['fieldName'];
                $filterSystem=$item['fliter']['systemFilter'];
                $this->systemFilter($filterSystem,$fieldValue,$ruleName,$fieldName,$message);
            }

            //自定义正则过滤
            $fieldValue = $this->data[$item['fieldName']];
            if(array_key_exists('fliter',$item)&&array_key_exists('regex',$item['fliter'])&&!empty($item['fliter']['regex'])){
                $ruleName=$item['ruleName'];
                $fieldName=$item['fieldName'];
                $filterRegex=$item['fliter']['regex'];
                $this->regexFilter($filterRegex,$fieldValue,$ruleName,$fieldName,$message);
            }
            //自定义方法过滤
            $fieldValue = $this->data[$item['fieldName']];
            if(array_key_exists('fliter',$item)&&array_key_exists('func',$item['fliter'])&&!empty($item['fliter']['func'])){
                $ruleName=$item['ruleName'];
                $fieldName=$item['fieldName'];
                $filterFunc=$item['fliter']['func'];
                $this->funcFilter($filterFunc,$fieldValue,$ruleName,$fieldName,$message);
            }
        }
        return  empty($this->error)?true:false;
    }
}



