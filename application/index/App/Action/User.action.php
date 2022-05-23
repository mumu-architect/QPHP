<?php


class UserAction extends CommonAction
{
    public function index(){
        //获取登录用户id
        //$userData= $this->getUserId();
        //var_dump($userData);

        echo 'user hello';
        $model = new UserModel();
        $data = $model->model->findAll();

        echo '<pre>';
        print_r($data);


        echo "==========================";
        //$arr2 = $model->table('mm_user')->where('id = 1')->findOne();
        $model = new UserModel();
        $arr2 = $model->model->table('"mm_user"')->asTable('u')
            ->field('u.*,ui."birthday",ui."info",a."address_info",a."is_default"')
            ->leftJoin('"mm_user_info"  ui on ui."user_id" = u."id"')
            ->leftJoin('"mm_address"  a on a."user_id" =u."id"')
            ->where('u."id" = 1')->findOne();
        echo '<pre>';
        print_r($arr2);

        $this->display('user/index.html',$data);
    }

    public function view(){
        extract($this->input);
        $id = isset($id)?$id:0;
        $model = new UserModel();
        $data = $model->model->find($id);
        $this->display('user/view.html',array(
            'data'=>$data
        ));
    }

    public function add(){

        extract($this->input);
        $username = isset($username)?$username:'';
        $password = isset($password)?$password:'';
        $age = isset($age)?$age:0;
        $address = isset($address)?$address:'';

        if($isPost){
            $model = new UserModel();
            $data =array(
                'id'=>111,
                'username'=>$username,
                'age'=>$age,
                'pwd'=>$password,
                'address'=>$address
            );
            $last_id = $model->model->add($data);

            if($last_id>0){
                $this->redireact('/admin/user/index/');
            }
        }

        $this->display('user/add.html');
    }


    public function edit(){
        extract($this->input);
        $id = isset($id)?$id:0;
        $username = isset($username)?$username:'';
        $pwd = isset($pwd)?$pwd:'';
        $age = isset($age)?$age:0;
        $address = isset($address)?$address:'';
        $model = new UserModel();
        if($isPost){
            $arr =array(
                'username'=>$username,
                'age'=>$age,
                'pwd'=>$pwd,
                'address'=>$address
            );
            $where ="where \"id\"={$id}";
            $res=$model->model->edit($arr,$where);
            if($res){
                $this->redireact('/index/user/index/');
            }
        }
        $data = $model->model->find($id);
        $this->display('user/edit.html',array(
            'data'=>$data
        ));
    }


    public function del(){
        extract($this->input);
        $id = isset($id)?$id:0;
        if($id>0){
            $model = new UserModel();
            $where ="where \"id\"={$id}";
            $res= $model->model->del($where);
            if($res){
                $this->redireact('/index/user/index/');
            }
        }
    }


    public function test(){
        echo 'test user';
        $test = '这是一个测试版本';
        $data =array(
            'test'=>$test
        );
        $this->display('user/test.html',$data);
    }
}
