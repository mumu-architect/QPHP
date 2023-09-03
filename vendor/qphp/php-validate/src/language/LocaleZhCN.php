<?php
namespace qphp\Validate\language;


class LocaleZhCN
{
    private static array $messages = [
        //验证错误提示
        'error_msg' => [
            'require' => '[:attribute]不能为空',
            'strLength' => '[:attribute]长度必须在:1-:2范围内',
            'number' => '[:attribute]必须为数字',
            'array' => '[:attribute]必须为数组',
            'float' => '[:attribute]必须为浮点数',
            'boolean' => '[:attribute]必须为布尔值',
            'email' => '[:attribute]必须为正确的邮件地址',
            'url' => '[:attribute]必须为正确的url格式',
            'ip' => '[:attribute]必须为正确的ip地址',
            'timestamp' => '[:attribute]必须为正确的时间戳格式',
            'date' => '[:attribute]必须为正确的日期格式',
            'regex' => '[:attribute]格式不正确',
            'in' => '[:attribute]必须在:range内',
            'notIn' => '[:attribute]必须不在:range内',
            'between' => '[:attribute]必须在:1-:2范围内',
            'notBetween' => '[:attribute]必须不在:1-:2范围内',
            'max' => '[:attribute]最大值为:1',
            'min' => '[:attribute]最小值为:1',
            'length' => '[:attribute]长度必须为:1',
            'confirm' => '[:attribute]和:1不一致',
            'gt' => '[:attribute]必须大于:1',
            'lt' => '[:attribute]必须小于:1',
            'egt' => '[:attribute]必须大于等于:1',
            'elt' => '[:attribute]必须小于等于:1',
            'eq' => '[:attribute]必须等于:1',
        ],
        //系统默认错误提示
        'systemErrorMsg' => [
            'error' => '不知道的错误',
            'dataError' => '验证的数据必须为数组',
            'dataFiledError'=>'字段 [:attribute] 不在验证数据中',
            'ruleMessage' => '[:attribute] 的语言信息没找到',
            'funFilterError'=>'[:attribute] 自定义函数过滤失败',
            'regexFilterError'=>'[:attribute] 正则表达式过滤失败',
            'ruleFilterError'=>'[:attribute] 系统规则过滤失败'
        ]
    ];


    public static function getMessages()
    {
        return self::$messages;
    }


}
