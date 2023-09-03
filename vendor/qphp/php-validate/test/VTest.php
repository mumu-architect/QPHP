<?php
namespace qphp\ValidateTest;

class ATest{
    function test1(){
        require_once "bootstrap.php";
        require_once "AValidate.php";
        $data = [
            'name' => '8gAg:',
            'username'=>'99654.78ww12et32.45fewabc',
            'test'=>'2321xxc'
        ];
        print("<pre>");
        print_r($data);
        $validate = new AValidate();

        $validateResult = $validate->check($data)->onScene('update')->Validate();
        if($validateResult !=true){
            $msg = $validate->getError();
            print("<pre>");
            print_r($msg);

            $msg2 = $validate->getAllErrors();
            print("<pre>");
            print_r($msg2);
        }
        $data1 = $validate->getData();
        print("<pre>");
        print_r($data1);

        //$validate = new AValidate();
        $validateResult = $validate->setLanguage('cn')->check($data)->onScene('select')->Validate();

        if($validateResult !=true){
            $msg = $validate->getError();
            print("<pre>");
            print_r($msg);

            $msg2 = $validate->getAllErrors();
            print("<pre>");
            print_r($msg2);
        }
        $data1 = $validate->getData();
        print("<pre>");
        print_r($data1);
    }
}
echo 222;
$a = new ATest();
$a->test1();
