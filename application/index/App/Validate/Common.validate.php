<?php
namespace index\Validate;


use Inhere\Validate\AbstractValidation;
use Inhere\Validate\Validation;

class CommonValidate extends AbstractValidation
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

    /**
     * Get the last error message
     *
     * @param bool $onlyMsg
     *
     * @return array|string
     */
    public function lastError(bool $onlyMsg = true): array
    {
        // TODO: Implement lastError() method.
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        // TODO: Implement getMessages() method.
    }

    /**
     * @param bool $asObject
     *
     * @return array|object
     */
    public function getSafeData(bool $asObject = false): object
    {
        // TODO: Implement getSafeData() method.
    }
}
