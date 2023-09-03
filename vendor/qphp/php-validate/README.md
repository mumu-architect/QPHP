# php-validate
generic data validate, filter library of the php


#### 1.简介
###### 1.支持多语言验证，中英文
###### 2.修改验证器的设计方案，使其扩展简单
###### 3.支持验证和过滤功能

#### 2.参考资料
###### https://getcomposer.org/doc/04-schema.md
###### https://phar.phpunit.de/
###### https://docs.phpunit.de/en/10.3/


#### 3.验证器案例代码
```php

<?php
namespace qphp\ValidateTest;

use qphp\Validate\Validator;

class  AValidate extends Validator
{

    public array $rules = [
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
            'ruleName.rule1' => '规则名rule1',
            'fieldName.username' => '用户名',
            'validationRule.func.checkUsername' => '自定义函数checkUsername验证未通过',
            'validationRule.func.checkUsername2' => '自定义函数checkUsername2验证未通过',
            'validationRule.regex.regexUsername' => '自定义正则regexUsername验证未通过',
            'validationRule.regex.regexUsername2' => '自定义正则regexUsername2验证未通过',
            'validationRule.systemRule.require' => '用户名不能为空',
            'validationRule.systemRule.number' => '必须为数字',
            'validationRule.systemRule.max' => '最大值为12',
            'validationRule.systemRule.min' => '最小值为4'
        ],
        'rule2' => [
            'ruleName.rule2' => '规则名rule2',
            'fieldName.username' => '用户名2',
            'validationRule.systemRule.require' => '名称不能为空'
        ],
        'rule3' => [
            'ruleName.rule3' => '规则名rule3',
            'fieldName.name' => '姓名',
            'validationRule.systemRule.require' => '名称不能为空',
            'validationRule.systemRule.number' => '必须为数字',
            'validationRule.systemRule.max' => '最大值为12',
            'validationRule.systemRule.min' => '最小值为4',
        ],
        'rule4' => [
            'ruleName.rule4' => '规则名rule4',
            'fieldName.test' => '测试',
            'validationRule.systemRule.require' => '测试不能为空',
        ]
    ];
    public array $scene = [
        'update' => ['rule1'],
        'insert' => ['rule1','rule2'],
        'delete' => ['rule3'],
        'select' => ['rule1', 'rule3'],
    ];


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


class ATest{
    function test1(){
        require_once "bootstrap.php";
        require_once "AValidate.php";
        $data = [
            'name' => '8gAg:',
            'username'=>'99654.78ww12et32.45fewabc',
            'test'=>'2321xxc'
        ];
        print("<pre>");
        print_r($data);
        $validate = new AValidate();

        $validateResult = $validate->check($data)->onScene('update')->Validate();
        if($validateResult !=true){
            $msg = $validate->getError();
            print("<pre>");
            print_r($msg);

            $msg2 = $validate->getAllErrors();
            print("<pre>");
            print_r($msg2);
        }
        $data1 = $validate->getData();
        print("<pre>");
        print_r($data1);

        //$validate = new AValidate();
        $validateResult = $validate->setLanguage('cn')->check($data)->onScene('select')->Validate();

        if($validateResult !=true){
            $msg = $validate->getError();
            print("<pre>");
            print_r($msg);

            $msg2 = $validate->getAllErrors();
            print("<pre>");
            print_r($msg2);
        }
        $data1 = $validate->getData();
        print("<pre>");
        print_r($data1);
    }
}
echo 222;
$a = new ATest();
$a->test1();
```
