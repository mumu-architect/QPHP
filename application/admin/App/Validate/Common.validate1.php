<?php
namespace admin\Validate;

use Inhere\Validate\Validation;

class CommonValidate extends Validation
{


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
