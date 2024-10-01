<?php
namespace admin\Action;

use admin\Util\ActionMiddleware;
use admin\Util\lib\JsonUtil;
use admin\Util\lib\JwtTokenUtil;
use admin\Util\lib\JwtUtil;
use Exception;

class CommonAction extends ActionMiddleware
{
    public function getUserId():array {
        $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        if(empty($token)){
            JsonUtil::echoJson(false,0,'token 错误');
        }
        try{
//            $jwt = new JwtTokenUtil();
//            $arr = $jwt->verifyToken($token);
            $arr=$this->verifyToken($token);
            return $arr['data'];
        }catch (Exception $e){
            JsonUtil::echoJson(false,0,'token 错误');
        }
        JsonUtil::echoJson(false,0,'token 错误');
    }
    public function md5Encrypt($key){
        return md5(md5('mumu'.$key));
    }

    //获取token
    public function getToken(array $data){
        $data['md5_user_id']=$this->md5Encrypt($data['user_id']);
        $token_arr=array('iss'=>'mumu','iat'=>time(),'exp'=>time()+60,'nbf'=>time()-3,'sub'=>'www.qphp.com','jti'=>md5(uniqid('JWT').time()),'data'=>$data);
        $token=JwtUtil::getToken($token_arr);
        //用户uid,md5存入session，最好放入redis,成功刷新过期时间
        $_SESSION[$data['md5_user_id']]=$data['user_id'];
        return $token;
    }
    //获取长token，用于刷新获取短token
    public function getLongToken($data){
        $data['md5_user_id']=$this->md5Encrypt($data['user_id']);
        $long_token_arr=array('iss'=>'mumu','iat'=>time(),'exp'=>time()+7200,'nbf'=>time()+3,'sub'=>'www.qphp.com','jti'=>md5(uniqid('JWT').time()),'data'=>$data);
        $long_token=JwtUtil::getToken($long_token_arr);
        return $long_token;
    }

    /**
     * 验证token
     * @param $token
     * @return mixed
     */
    public function verifyToken($token){
        //对token进行验证签名
        $payload_arr=JwtUtil::verifyToken($token);
        return $payload_arr['data'];
    }
    /**
     * 验证token并检测是否正确
     * @param $token
     * @return mixed
     */
    public function checkToken($token){
        //对token进行验证签名
        $payload_arr=JwtUtil::verifyToken($token);
        if($payload_arr){
            $md5_user_id=$payload_arr['data']['md5_user_id'];
            return $_SESSION[$md5_user_id]==$payload_arr['data']['user_id']?true:false;
        }
        return false;
    }
}



