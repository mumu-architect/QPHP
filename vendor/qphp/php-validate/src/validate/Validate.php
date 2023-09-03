<?php
namespace qphp\Validate\validate;

use qphp\Validate\message\Message;

class Validate
{
    private static array $data=[];

    public static function setData(array $data)
    {
        self::$data=$data;
    }
    /**
     * 系统规则验证
     * @param $validationRuleSystemRule
     * @param $fieldValue
     * @param $ruleName
     * @param $message
     * @param $messageFieldName
     */
    public static function checkSystemValidation($validationRuleSystemRule,$fieldValue, $ruleName,$message,$messageFieldName):array
    {
        $errorInfoArray = [];
        $rules = explode('|', $validationRuleSystemRule);
        foreach ($rules as $i => $rule) {
            $result = self::checkSystemRule($rule, $fieldValue);
            if (!$result) {
                $error_info = Message::getSystemRuleMessage($messageFieldName, $rule,$message,$ruleName);
                $errorInfoArray[]= $error_info;
            }
        }
        return $errorInfoArray;
    }


    /**
     * 自定义正则验证
     * @param $validationRuleRegex
     * @param $fieldValue
     * @param $ruleName
     * @param $message
     */
    public static function regexValidate($validationRuleRegex,$fieldValue,$ruleName,$message):array
    {
        $errorInfoArray = [];
        foreach ($validationRuleRegex as $k => $regexRule){
            $res = self::regexValidateRule($regexRule,$fieldValue);
            if(!$res){
                $messageKey = 'validationRule.regex.'.$k;
                $error_info = Message::getMessage($message,$ruleName,$messageKey,'ruleMessage');
                $errorInfoArray[]= $error_info;
            }
        }
        return $errorInfoArray;
    }

    /**
     * 自定义方法验证
     * @param $validationRuleFunc
     * @param $fieldValue
     * @param $ruleName
     * @param $message
     */
    public static function funcValidate($validationRuleFunc,$fieldValue,$ruleName,$message,$obj):array
    {
        $errorInfoArray = [];
        foreach ($validationRuleFunc as $k => $methodName){
            $res=self::callUserFunc($methodName,array(
                $fieldValue
            ),$obj);
            if(!$res){
                $messageKey = 'validationRule.func.'.$methodName;
                $error_info = Message::getMessage($message,$ruleName,$messageKey,'ruleMessage');
                $errorInfoArray[]= $error_info;
            }
        }
        return $errorInfoArray;
    }

    /**
     * 系统单条规则验证规则验证
     * @param string $rule
     * @param $validate_data
     * @return bool|mixed
     */
    private static function checkSystemRule(string $rule,$validateData)
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
                    return call_user_func_array(['qphp\Validate\validate\Validate', $rule_arr[0]], [$func_name, $validateData]);
                } else {
                    return call_user_func_array(['qphp\Validate\validate\Validate', $rule], [$rule, $validateData]);
                }
        }
    }


    /**
     * 正则验证函数
     * @param $rule
     * @param $data
     * @return bool
     */
    private static function regexValidateRule($rule, $data):bool
    {
        return filter_var($data, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => $rule]]);

    }

    /**
     * 调用当前类的自定义方法
     * @param $method
     * @param $param
     * @param null $obj
     * @return bool
     */
    private static function callUserFunc($method, $param,$obj=null):mixed
    {
        if (!(isset($obj)&&is_object($obj))) {
            $obj = 'qphp\Validate\validate\Validate';
        }
        //return call_user_func_array(array($className, $method), array($dbKey,$dbType));
        return call_user_func_array([$obj, $method], $param);
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
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private static  function strLength($rule, $data):bool
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
    private static function in($rule, $data):bool
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
    private static function notIn($rule, $data):bool
    {
        return !self::in($data, $rule);
    }


    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private static function between($rule, $data):bool
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
    private static function notBetween($rule, $data):bool
    {
        return !self::between($rule, $data);
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private static function max($rule, $data):bool
    {
        return $data <= $rule;
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private static function min($rule, $data):bool
    {
        return $data >= $rule;
    }

    /**
     * [in description]
     * @param  [type] $rule [验证规则]
     * @param  [type] $data [需要验证的数据]
     * @return [type]       [boolean]
     */
    private static function length($rule, $data):bool
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
    private static function confirm($rule, $data):bool
    {
        return isset(self::$data[$rule]) && $data == self::$data[$rule];
    }

    private static function gt($rule, $data):bool
    {
        return $data > $rule;
    }

    private static function lt($rule, $data):bool
    {
        return $data < $rule;
    }

    private static function egt($rule, $data):bool
    {
        return $data >= $rule;
    }

    private static function elt($rule, $data):bool
    {
        return $data <= $rule;
    }

    private static function eq($rule, $data):bool
    {
        return $data == $rule;
    }
}
