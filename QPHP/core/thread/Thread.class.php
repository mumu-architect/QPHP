<?php

/**
 * TODO:测试类
 * Class Thread
 */

class Thread {
    public function __construct() {

    }

    private function _thread() {
        $fp=fsockopen($_SERVER['HTTP_HOST'],80,$errno,$errstr,5);
        if(!$fp){
            echo "$errstr ($errno)\n";
        }
        //build the post string
//        foreach($formdata AS $key => $val){
//            $poststring .= urlencode($key) . "=" . urlencode($val) . "&";
//        }
// strip off trailing ampersand
        //$poststring = substr($poststring, 0, -1);

        //send the server request
        fputs($fp, "GET /admin/user/index?flag=1 HTTP/1.1\r\n");
        fputs($fp, "Host: {$_SERVER['HTTP_HOST']}\r\n");
       // fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
       // fputs($fp, "Content-length: ".strlen($poststring)."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
      //  fputs($fp, $poststring . "\r\n\r\n");

        //loop through the response from the server
        while(!feof($fp)) {
             $a = fgets($fp, 4096);
             echo $a;
        }
        //close fp - we are done with it
        fclose($fp);
    }

    public function exec($foo,$func,$args=[]) {
       echo 11111;
        if(isset($_GET['flag'])) {
            echo 222;
            return call_user_func_array(array($foo, $func), $args);
        }else{
            $this->_thread();
        }
        echo 333;
        return null;
    }

}

