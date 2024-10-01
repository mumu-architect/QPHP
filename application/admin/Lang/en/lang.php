<?php
/**
 * 语言配置文件
 */
return array(
    'name'=>"English ",
    'ruleName.rule1' => 'rule name rule1',
    'rule1.fieldName.username' => 'username',
    'rule1.validationRule.func.checkUsername' => 'checkUsername verification is not through the custom function',
    'rule1.validationRule.func.checkUsername2' => 'checkUsername2 verification is not through the custom function',
    'rule1.validationRule.regex.regexUsername' => 'custom regular regexUsername verification is not through',
    'rule1.validationRule.regex.regexUsername2' => 'Custom regular regexUsername2 validation failed',
    'rule1.validationRule.systemRule.require' => 'user name cannot be empty',
    'rule1.validationRule.systemRule.number' => 'must be numeric',
    'rule1.validationRule.systemRule.max' => 'maximum of 12',
    'rule1.validationRule.systemRule.min' => 'minimum value of 4',
    'ruleName.rule2' => 'rule name rule2',
    'rule2.fieldName.username2' =>'username2',
    'rule2.validationRule.systemRule.require2' =>'name cannot be empty',
    'ruleName.rule3' => 'rule name rule3',
    'rule3.fieldName.name' => 'name ',
    'rule3.validationRule.systemRule.require' => 'name cannot be empty',
    'rule3.validationRule.systemRule.number' => 'must be numeric',
    'rule3.validationRule.systemRule.max' => 'maximum value of 12',
    'rule3.validationRule.systemRule.min' => "minimum value of 4",
    'ruleName.rule4' => "rule name rule4",
    'rule4.fieldName.test4' => "test",
    'rule4.validationRule.systemRule.require4' => "test can't be empty"

);
