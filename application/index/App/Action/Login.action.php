<?php

class LoginAction extends ActionMiddleware
{

    public function login(){
        extract($this->input);
        $username = isset($username)?$username:'';
        $password = isset($password)?$password:'';
        $model = new UserModel();
        $data = $model->table('mm_user')->where("username ='{$username}' and pwd='{$password}'")->select();
        $model->getLastSql();
        var_dump($data);


        $jwt = new JwtTokenUtil();
        $token = $jwt->getToken(array('user_id'=>$data['id']));
        $data_arr=array('token'=>$token);
        $json = new JsonUtil();
        $json->echoJson(true,200,'成功',$data_arr);

    }
}
