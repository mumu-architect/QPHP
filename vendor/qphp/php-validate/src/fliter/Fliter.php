<?php
namespace qphp\Validate\fliter;

use qphp\Validate\message\Message;

class Fliter
{

    /**
     * 系统规则过滤
     * @param $filterRuleSystemRule
     * @param $fieldValue
     * @param $ruleName
     * @param $fieldName
     * @param $message
     * @param $data
     * @return array
     */
    public static function systemFilter($filterRuleSystemRule,$fieldValue, $ruleName,$fieldName,$message,&$data)
    {
        $errorInfoArray = [];
        $rules = explode('|', $filterRuleSystemRule);
        foreach ($rules as $i => $rule) {
            $result = self::systemFilterRule($rule, $fieldValue);
            if($result===false){
                $messageKey = 'fliter.regex.'.$rule;
                $error_info = Message::getMessage($message,$ruleName,$messageKey,'ruleFilterError');
                $errorInfoArray[]= $error_info;
            }else {
                $data[$fieldName] = $result;
            }
        }
        return $errorInfoArray;
    }

    /**
     * 自定义正则过滤
     * @param $filterRegex
     * @param $fieldValue
     * @param $ruleName
     * @param $fieldName
     * @param $message
     * @param $data
     * @return array
     */
    public static function regexFilter($filterRegex,$fieldValue,$ruleName,$fieldName,$message,&$data){
        $errorInfoArray = [];
        foreach ($filterRegex as $k => $regexRule){
            $result = self::regexFilterRule($regexRule,$fieldValue);
            if($result===false){
                $messageKey = 'fliter.regex.'.$k;
                $error_info = Message::getMessage($message,$ruleName,$messageKey,'regexFilterError');
                $errorInfoArray[]= $error_info;
            }else {
                $data[$fieldName] = $result;
            }
        }
        return $errorInfoArray;
    }

    /**
     * 自定义方法过滤
     * @param $filterFunc
     * @param $fieldValue
     * @param $ruleName
     * @param $fieldName
     * @param $message
     * @param $data
     * @return array
     */
    public static function funcFilter($filterFunc,$fieldValue,$ruleName,$fieldName,$message,&$data,$obj){
        $errorInfoArray = [];
        foreach ($filterFunc as $k => $methodName){
            $result=self::callUserFunc($methodName,array(
                $fieldValue
            ),$obj);
            if($result===false){
                $messageKey = 'fliter.func.'.$methodName;
                $error_info = Message::getMessage($message,$ruleName,$messageKey,'funFilterError');
                $errorInfoArray[]= $error_info;
            }else{
                $data[$fieldName] = $result;
            }
        }
        return $errorInfoArray;
    }

    /**
     * 系统单条规则过滤
     * @param string $rule
     * @param $validateData
     * @return false|int|mixed|string
     */
    private static function systemFilterRule(string $rule, $validateData):mixed
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
                return call_user_func_array(['qphp\Validate\fliter\Fliter', $rule], [$rule, $validateData]);
        }
    }

    /**
     * 正则过滤函数
     * @param $rule
     * @param $data
     * @return bool
     */
    private static function regexFilterRule($rule, $data):mixed
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
    private static function callUserFunc($method, $param,$obj=null):mixed
    {
        if (!(isset($obj)&&is_object($obj))) {
            $obj = 'qphp\Validate\fliter\Fliter';
        }
        //return call_user_func_array(array($className, $method), array($dbKey,$dbType));
        return call_user_func_array([$obj, $method], $param);
    }
}
