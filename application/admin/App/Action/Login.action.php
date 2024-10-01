<?php
namespace admin\Action;


use admin\Model\UserModel;
use admin\Util\lib\JsonUtil;
use admin\Util\lib\JwtTokenUtil;

class LoginAction extends CommonAction
{

    public function login(){
        $username=$this->input['username'];
        $password=$this->input['password'];
        if(!isset($username)||empty($username)){
            JsonUtil::echoJson(true,200,'用户名为空',[]);
        }
        if(!isset($password)||empty($password)){
            JsonUtil::echoJson(true,200,'密码为空',[]);
        }
        $model = new UserModel();
        $data =  $model->Db('mysql_0')->table('mm_user')->where("username ='{$username}' and pwd='{$password}'")->find();
//        $model->getLastSql();
//        var_dump($data);

//        $jwt = new JwtTokenUtil();
//        $token = $jwt->getToken(array('user_id'=>$data['id']));
        $token = $this->getToken(array('user_id'=>$data['id']));
        $long_token = $this->getLongToken(array('user_id'=>$data['id']));
        $data_arr=array('token'=>$token,'long_token'=>$long_token);
        JsonUtil::echoJson(true,200,'成功',$data_arr);

    }
    /**
     * 使用长token换取短token
     */
    public function getShortToken(){
        $longToken=$this->input['longToken'];
        $longToken = isset($longToken)?$longToken:'';
        try{
            if($this->checkToken($longToken)){
                $data=$this->verifyToken($longToken);
                $token = $this->getToken($data);
                $data_arr = array('token'=>$token);
                JsonUtil::echoJson(true,200,'成功',$data_arr);
            }
        }catch (\Exception $e){
            JsonUtil::echoJson(true, 200,'重新登陆',  []);
        }

        JsonUtil::echoJson(true, 200,'重新登陆',  []);
    }


}
