<?php
namespace qphp\Validate\language;


class LocaleEN
{
    private static array $messages = [
        //验证错误提示
        'error_msg' => [
            'require' => '[:attribute] can not be empty',
            'strLength' => 'The length of the [:attribute] must be within the range of :1-:2',
            'number' => '[:attribute] must be a number',
            'array' => '[:attribute] must be an array',
            'float' => '[:attribute] must be a floating point number',
            'boolean' => '[:attribute] must be a Boolean value',
            'email' => '[:attribute] must be the correct email address',
            'url' => '[:attribute] must be in the correct URL format',
            'ip' => '[:attribute] must be the correct IP address',
            'timestamp' => '[:attribute] must be in the correct timestamp format',
            'date' => '[:attribute] must be in the correct date format',
            'regex' => '[:attribute] incorrect format',
            'in' => '[:attribute] must be within :range',
            'notIn' => '[:attribute] must not be within :range',
            'between' => '[:attribute] must be within the range of :1-:2',
            'notBetween' => '[:attribute] must not be within the range of :1-:2',
            'max' => 'The maximum value of [:attribute] is :1',
            'min' => 'The minimum value of [:attribute] is :1',
            'length' => '[:attribute] length must be :1',
            'confirm' => '[:attribute] and :1 are inconsistent',
            'gt' => '[:attribute] must be greater than :1',
            'lt' => '[:attribute] must be less than :1',
            'egt' => '[:attribute] must be greater than or equal to :1',
            'elt' => '[:attribute] must be less than or equal to :1',
            'eq' => '[:attribute] must be equal to :1',
        ],
        //系统默认错误提示
        'systemErrorMsg' => [
            'error' => 'Unknown error.',
            'dataError' => 'Validation data must be in array format.',
            'dataFiledError'=>'Field [:attribute] does not exist in the data.',
            'ruleMessage' => '[:attribute] does not have a language pack.',
            'funFilterError'=>'[:attribute] custom function filtering failed.',
            'regexFilterError'=>'[:attribute] regular expression filtering failed.',
            'ruleFilterError'=>'[:attribute] system rule filtering failed.'
        ]
    ];

    public static function getMessages()
    {
        return self::$messages;
    }
}
