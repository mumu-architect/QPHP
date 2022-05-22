<?php

/**
 * Class UserModel类
 */
class UserModel extends OracleModel
{
    public $table='mm_user';//数据表
    public $key='id';//主键

    public function __construct()
    {
        parent::__construct('oracle');
    }
}
