<?php


class UserAction extends ActionMiddleware
{
    public function index(){
        echo 'user hello';
        $model = new UserModel();
        $data = $model->find(1);
        var_dump($data);
    }
}
