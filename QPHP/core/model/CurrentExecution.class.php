<?php


namespace QPHP\core\model;

/**
 * 当前执行类
 * Class CurrentExecution
 * @package QPHP\core\model
 */
class CurrentExecution
{
    public static function createObj($dbType,$table,$key){
         $className="QPHP\core\model\mysql\\".ucfirst($dbType)."M";
         return call_user_func_array(array($className, "newClass"), array($dbType,$table,$key));

    }

}
