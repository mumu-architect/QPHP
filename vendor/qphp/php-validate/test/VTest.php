<?php
namespace qphp\ValidateTest;

class ATest{
    function test1(){
        $data = [
            'name' => '8gAg:',
            'username'=>'99654.78ww12et32.45fewabc',
            'test'=>'2321xxc'
        ];
        $validate = new AValidate();
        $validateResult = $validate->rule($validate->rules)->message($validate->message)->check($data)->onScene('select')->Validate();
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
