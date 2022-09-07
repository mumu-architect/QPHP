<?php
namespace admin\Action;

use admin\Util\ActionMiddleware;

class CommonAction extends ActionMiddleware
{
    public function getUserId(){
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
    }
}



