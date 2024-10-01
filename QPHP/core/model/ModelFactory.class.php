<?php
namespace QPHP\core\model;

use Exception;
use QPHP\core\model\intf\IModelFactory;

class ModelFactory implements IModelFactory
{
    public function createModel($dbType,$table,$key){
        if(!empty($dbType)) {
            try{
                $className="QPHP\core\model\\".strtolower($dbType)."\\".ucfirst($dbType)."M";
                $model= call_user_func_array(array($className, "newClass"), array($dbType,$table,$key));
            }catch (Exception $e){
                throw new Exception($e) ;
            }
            return $model;
        }else{
            throw new Exception("The model type is empty");
        }
    }
}
