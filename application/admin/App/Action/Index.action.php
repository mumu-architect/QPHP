<?php
namespace admin\Action;

use admin\Util\lib\JwtUtil;
use admin\Util\lib\RsaUtil;
use QPHP\core\lang\Lang;


class IndexAction extends CommonAction
{
    public function index(){


        //http://www.qphp.com/index/index/?user_id=1
        echo $this->input['id'];
        echo 'hello index';
        $name = 'yzm';
        $age = 5;
        $data = array(
            'arr'=>array('name'=>'QPHP','age'=>1),
            'name'=>$name,
            'age'=>$age
        );
        if(true){
            //throw new Exception('错误了',-1);
        }

        print_r(Lang::lang('name'));

        $this->display('index/index.html',$data);
    }
    /**
     * 测试系统异常和自定义错误
     * 系统异常优先抛出，系统异常解决后，才会抛出用户自定义错误信息
     */
    public function testError(){
        //抛出用户自定义错误信息
        trigger_error("错误信息", E_USER_ERROR);
        //系统异常优先抛出
        $a;
        //$a
    }
    /**
     * 测试加密解密，加签验签
     * @throws \Exception
     */
    public function testRsa(){
        //使用例子：
        $RSA = new RsaUtil();
        //对数据公钥加密及私钥解密
        $string = '快乐程序员';

        $pubString = $RSA->pubEncrypt($string);
        echo '用公钥加密后数据:'.$pubString .'<br/>';

        $priDeString = $RSA->privDecrypt($pubString);
        echo '用私钥解密数据:'.$priDeString .'<br/>';

        //实现对数据私钥加签及公钥验签

        $sign = $RSA->sign($string);
        echo '用私钥加签后得到签名:'.$sign .'<br/>';
        $result = $RSA->verify($string,$sign);
        echo '验证签名是否正确:<br/>';
        var_dump($result);
    }
    /**
     *
     */
    public function testJwt(){
        //自己使用测试begin
        $payload_test=array('iss'=>'mumu','iat'=>time(),'exp'=>time()+7200,'nbf'=>time()-3,'sub'=>'www.qphp.com','jti'=>md5(uniqid('JWT').time()),'data'=>'uid');
        $token_test=JwtUtil::getToken($payload_test);
        echo "<pre>";
        echo $token_test;

        //对token进行验证签名
        $getPayload_test=JwtUtil::verifyToken($token_test);
        echo "<br><br>";
        var_dump($getPayload_test);
        echo "<br><br>";
        //自己使用时候end
    }

    //memcache
    public function mem(){

        dump('eee');
       // setVar('name','yzm');
        //echo getVar('name');
        delVal('name');
    }

    //redis
    public function redis_test(){
        $redis = R();
        //$redis->set('name','good');
        $name = $redis->get('name');
        var_dump($name);
        //$redis->lpush('sql','select * from user');
        $sql =$redis->rpop('sql');

        var_dump($sql);
    }

    public function age(){
        echo $this->input['id'];
        echo 'index/ages';
    }

    public function name(){
        echo 'GET '.'index/name';
    }


    public function addName(){
        echo 'POST '.'index/addName';
    }

    public function delName(){
        echo 'DELETE '.'index/addName';
    }
    public function putName(){
        echo 'PUT'.'index/addName';
    }
}
