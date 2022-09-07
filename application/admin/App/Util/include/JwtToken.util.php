<?php
namespace admin\Util\lib;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtTokenUtil
{
    //获取公钥私钥
    private $privateKeyFile = APP_PATH.'keys/privkey.pem';
    private $publicKeyFile = APP_PATH.'keys/pubkey.pem';
    private $publicKey='';
    private $privateKey='';
    /*
[
   * 'iss'=>'jwt_admin', //该JWT的签发者
   * 'iat'=>time(), //签发时间
   * 'exp'=>time()+7200, //过期时间
   * 'nbf'=>time()+60, //该时间之前不接收处理该Token
   * 'sub'=>'www.admin.com', //面向的用户
   * 'jti'=>md5(uniqid('JWT').time()) //该Token唯一标识
 ]
 * */
    private $payload =array();

    public function __construct(){
        $this->publicKey  = file_get_contents($this->publicKeyFile);
        $this->privateKey = file_get_contents($this->privateKeyFile);
        $this->payload=array(
            'iss'=>'jwt_QPHP',
            'iat'=>time(),
            'exp'=>time()+7200,
            'nbf'=>time(),
            'sub'=>'www.qphp.com',
            'jti'=>md5(uniqid('JWT').time())
        );

    }
    public function getToken($userData=array()){
        $this->payload['data']=$userData;
        $jwt = JWT::encode($this->payload, $this->privateKey, 'RS256');
        return $jwt;
    }

    public function verifyToken($jwt){
        $decoded = JWT::decode($jwt, new Key($this->publicKey, 'RS256'));
        $decoded_array = (array) $decoded;
        return $decoded_array;
    }
}
