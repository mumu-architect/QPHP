<?php
namespace qphp\Validate\message;

use qphp\Validate\language\GlobalMessage;
use qphp\Validate\language\MessageInterface;

class Message
{
    //验证错误提示
    private static array $error_msg;
    //系统默认错误提示
    private static array $systemErrorMsg;

    /**
     * 设置验证错误提示
     * @param GlobalMessage $message
     */
    private static function setErrorMsg(array $message):void
    {
        self::$error_msg=$message['error_msg'];
    }

    /**
     * 设置系统默认错误提示
     * @param GlobalMessage $message
     */
    private static function setSystemErrorMsg(array $message):void
    {
        self::$systemErrorMsg=$message['systemErrorMsg'];
    }

    /**
     * 设置错误提示信息
     * @param GlobalMessage $message
     */
    public static function setMessage(string $language='en'):void
    {
        GlobalMessage::setLanguage($language);
        $message = GlobalMessage::getMessages();
        self::setErrorMsg($message);
        self::setSystemErrorMsg($message);
    }

    public static function getErrorMsg():array
    {
        return self::$error_msg['error_msg'];
    }

    /**
     * 设置系统默认错误提示
     * @param GlobalMessage $message
     */
    public static function getSystemErrorMsg():array
    {
        return self::$error_msg['systemErrorMsg'];
    }

    /**
     * 获取系统自定义错误信息
     * @param $ruleName
     * @param string $systemErrorMsgkey
     */
    private static function getSystemErrorMsgConf($ruleName,$systemErrorMsgkey='error'){
        return preg_replace('/(:attribute)/i', $ruleName, self::$systemErrorMsg[$systemErrorMsgkey]);
    }

    /**
     * 获取当前验证规则的消息
     * @param $errorKey
     * @param $name
     * @param $systemRule
     * @return bool|mixed
     */
    public static function getMessage($message,$ruleName,$messageKey,$systemErrorMsgkey='error'){
        $error_info = self::getSystemErrorMsgConf( $ruleName, $systemErrorMsgkey);
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
    public static function getSystemRuleMessage($name, $systemRule,$message,$ruleName)
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
        if (isset(self::$error_msg[$error_key])) {
            return str_replace([':attribute', ':range', ':1', ':2'], [$name, $range, $value1, $value2], self::$error_msg[$error_key]);
        }
        return false;
    }

    /**
     * 判断rule规则是否合规
     * @param $rule
     * @return bool
     */
    public static function isRuleCorrect(array $rule):bool
    {
        if(!(array_key_exists('fieldName',$rule)&&!empty($rule['fieldName']))){
            return false;
        }
        if(!(array_key_exists('ruleName',$rule)&&!empty($rule['ruleName']))){
            return false;
        }
        return true;
    }


    /**
     * 判断数据是否存在某字段，合法,并返回错误信息
     * @param $data
     * @param $fieldName
     * @return string
     */
    public static function isDataCorrect($data,$fieldName):string
    {
        if(!(array_key_exists($fieldName,$data))){
            $error_info = self::getSystemErrorMsgConf( $fieldName, 'dataFiledError');
            return $error_info;
        }
        return '';
    }
}
