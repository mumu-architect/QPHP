<?php
namespace index\Action;

use index\Util\ActionMiddleware;
use index\Util\lib\JsonUtil;
use index\Util\lib\JwtTokenUtil;
use Exception;

class CommonAction extends ActionMiddleware
{
    public function getUserId():array {
        $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        $json = new JsonUtil();
        if(empty($token)){
            $json->echoJson(false,0,'token 错误');
        }
        try{
            $jwt = new JwtTokenUtil();
            $arr = $jwt->verifyToken($token);
            return $arr['data'];
        }catch (Exception $e){
            $json->echoJson(false,0,'token 错误');
        }
        return array();
    }
}



