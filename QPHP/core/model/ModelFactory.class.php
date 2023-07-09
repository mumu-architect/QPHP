<?php
namespace QPHP\core\model;

use Exception;
use QPHP\core\model\intf\IModelFactory;
use QPHP\core\model\mysql\MysqlM;
use QPHP\core\model\oracle\OracleM;

class ModelFactory implements IModelFactory
{
    public function createModel($dbType,$table,$key){
        if(!empty($dbType)) {
            try{
                if($dbType==='mysql'){
                    $model=new MysqlM($table,$key);
                }elseif ($dbType==='oracle') {
                    $model=new OracleM($table,$key);
                }else{
                    throw new Exception("Model type error");
                }
            }catch (Exception $e){
                throw new Exception($e) ;
            }
            return $model;
        }else{
            throw new Exception("The model type is empty");
        }
    }
}
