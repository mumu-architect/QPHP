<?php
namespace admin\Validate;

use Inhere\Validate\Validation;

class CommonValidate extends Validation
{
    # 进行验证前处理,返回false则停止验证,但没有错误信息,可以在逻辑中调用 addError 增加错误信息
    public function beforeValidate(): bool
    {
        return true;
    }
    # 进行验证后处理,该干啥干啥
    public function afterValidate(): bool
    {
        return true;
    }

}
