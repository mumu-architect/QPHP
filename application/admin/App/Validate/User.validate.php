<?php
namespace admin\Validate;

use QPHP\core\lang\Lang;

class UserValidate extends CommonValidate
{
    public array $rule = [
        [
            'ruleName' => 'rule1',
            'fieldName' => 'username',
            'validationRule' => [
                'systemRule' => 'require|email|strLength:5,20',
                'regex' => ['regexUsername' => '/^[0-3]+$/', 'regexUsername2' => '/^[a-z]+$/'],
                'func' => ['checkUsername', 'checkUsername2'],
            ]
        ],
        [
            'ruleName' => 'rule2',
            'fieldName' => 'username',
            'validationRule' => ['systemRule' => 'require'],
            'fliter' => [
                'systemFilter' => 'float|number',
                'regex' => ['regexFilterUsername' => '/^[0-8]+/', 'regexFilterUsername2' => '/[a-c]+/'],
                'func' => ['filterUsername','filterUsername2']
            ]
        ],
        [
            'ruleName' => 'rule3',
            'fieldName' => 'name',
            'validationRule' => [
                'systemRule' => 'require|email|in:7,8,9|max:10|min:6|between:6,8|length:2'
            ]
        ],
        [
            'ruleName' => 'rule4',
            'fieldName' => 'test',
            'validationRule' => [
                'systemRule' => 'require'
            ]
        ]
    ];

    public array $message = [
        'rule1' => [
            'ruleName.rule1' => 'ruleName.rule1',
            'fieldName.username' => 'fieldName.username',
            'validationRule.func.checkUsername' => 'validationRule.func.checkUsername',
            'validationRule.func.checkUsername2' => 'validationRule.func.checkUsername2',
            'validationRule.regex.regexUsername' => 'validationRule.regex.regexUsername',
            'validationRule.regex.regexUsername2' => 'validationRule.regex.regexUsername2',
            'validationRule.systemRule.require' => 'validationRule.systemRule.require',
            'validationRule.systemRule.number' => 'validationRule.systemRule.number',
            'validationRule.systemRule.max' => 'validationRule.systemRule.max',
            'validationRule.systemRule.min' => 'validationRule.systemRule.min'
        ],
        'rule2' => [
            'ruleName.rule2' => 'ruleName.rule2',
            'fieldName.username' => 'fieldName.username',
            'validationRule.systemRule.require' => 'validationRule.systemRule.require'
        ],
        'rule3' => [
            'ruleName.rule3' => 'ruleName.rule3',
            'fieldName.name' => 'fieldName.name',
            'validationRule.systemRule.require' => 'validationRule.systemRule.require',
            'validationRule.systemRule.number' => 'validationRule.systemRule.number',
            'validationRule.systemRule.max' => 'validationRule.systemRule.max',
            'validationRule.systemRule.min' => 'validationRule.systemRule.min',
        ],
        'rule4' => [
            'ruleName.rule4' => 'ruleName.rule4',
            'fieldName.test' => 'fieldName.test',
            'validationRule.systemRule.require' => 'validationRule.systemRule.require',
        ]
    ];
    public array $scene = [
        'update' => ['rule1'],
        'insert' => ['rule1','rule2','rule4'],
        'delete' => ['rule3'],
        'select' => ['rule1', 'rule3'],
    ];

    public function __construct(array $data = [], array $rules = [], array $translates = [], string $scene = '', bool $startValidate = false)
    {
        $this->analysisMessage();
        //var_dump(Lang::lang("name"));
        //var_dump(Lang::lang("validationRule.regex.regexUsername2"));
        parent::__construct();
        //parent::__construct($data, $rules, $translates, $scene, $startValidate);
    }

    /**
     * 解析消息语言
     */
    public function analysisMessage(){
        array_walk_recursive($this->message, function (&$item, $key) {
            $item = Lang::lang($item)?Lang::lang($item):$item;
        });
    }



    public function checkUsername($username){
        if(strlen($username)>6){
            return true;
        }
        return false;
    }

    public function checkUsername2($username){
        if(strlen($username)>10){
            return true;
        }
        return false;
    }
    public function filterUsername($username){
        if(!empty($username)){

            settype($username,'float');
            return $username;
        }
        return false;
    }
    public function filterUsername2($username){
        return !empty($username)?intval($username):false;
    }

}
