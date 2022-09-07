<?php
namespace QPHP\core\model;

interface IModelBase
{
    /**
     * 释放链式操作数据
     * @param $field
     * @return $this
     */
    public function free();
}
