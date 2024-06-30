<?php
namespace QPHP\core\model;

use Exception;
use QPHP\core\model\intf\IModelFactory;

class ModelFactory implements IModelFactory
{
    public function createModel($dbType,$table,$key){
        if(!empty($dbType)) {
            try{
                $model=CurrentExecution::createObj($dbType,$table,$key);
            }catch (Exception $e){
                throw new Exception($e) ;
            }
            return $model;
        }else{
            throw new Exception("The model type is empty");
        }
    }
}
