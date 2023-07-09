<?php
namespace QPHP\core\input;
/**
  +------------------------------------------------------------------------------
 * Run Framework 接受$_GET、$_POST、$_FILES参数类
  +------------------------------------------------------------------------------
 */
class Input {

    //保存$_GET、$_POST、$_FILES数组
    private $dataInput = array();

    public function __construct() {

    }

    /**
      +----------------------------------------------------------
     * 类的析构方法(负责资源的清理工作)
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function __destruct() {
        $this->dataInput = null;
    }

    /**
      +----------------------------------------------------------
     * 属性访问器(读)
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }

    /**
     * 设置数据类型
     */
    private function initDataType(){
        $this->dataInput['isGet'] = false;
        $this->dataInput['isPost'] = false;
        $this->dataInput['isPut'] = false;
        $this->dataInput['isDelete'] = false;
    }

    /**
     * get解析
     */
    private function getParse():void {
        if (is_array($_GET)) {
            foreach ($_GET as $k => $v) {
                if (is_array($_GET[$k])) {
                    foreach ($_GET[$k] as $k2 => $v2) {
                        $this->dataInput[$k][$this->cleanKey($k2)] = $this->cleanValue($v2);
                    }
                } else {
                    $this->dataInput[$this->cleanKey($k)] = $this->cleanValue($v);
                }
            }

        }
        $this->dataInput['isGet'] = isset($_GET)&&!empty($_GET)?true:false;
    }

    /**
     * post解析
     */
    private function postParse():void {
        if (is_array($_POST)) {
            foreach ($_POST as $k => $v) {
                if (is_array($_POST[$k])) {
                    foreach ($_POST[$k] as $k2 => $v2) {
                        $this->dataInput[$k][$this->cleanKey($k2)] = $this->cleanValue($v2);
                    }
                } else {
                    $this->dataInput[$this->cleanKey($k)] = $this->cleanValue($v);
                }
            }
        }
        $this->dataInput['isPost'] = isset($_POST)&&!empty($_POST)?true:false;
    }

    /**
     * put解析
     */
    private function putParse():void {
        //put
        $_PUT =array();
        if (isset($_SERVER['REQUEST_METHOD'])&&$_SERVER['REQUEST_METHOD']=='PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);
            foreach ($_PUT as $k => $v) {
                if (is_array($_PUT[$k])) {
                    foreach ($_PUT[$k] as $k2 => $v2) {
                        $this->dataInput[$k][$this->cleanKey($k2)] = $this->cleanValue($v2);
                    }
                } else {
                    $this->dataInput[$this->cleanKey($k)] = $this->cleanValue($v);
                }
            }
            $this->dataInput['isPut'] = true;
        }
    }


    /**
     * delete解析
     */
    private function deleteParse():void {
        //delete
        $_DELETE =array();
        if (isset($_SERVER['REQUEST_METHOD'])&&$_SERVER['REQUEST_METHOD']=='DELETE') {
            parse_str(file_get_contents('php://input'), $_DELETE);
            foreach ($_DELETE as $k => $v) {
                if (is_array($_DELETE[$k])) {
                    foreach ($_DELETE[$k] as $k2 => $v2) {
                        $this->dataInput[$k][$this->cleanKey($k2)] = $this->cleanValue($v2);
                    }
                } else {
                    $this->dataInput[$this->cleanKey($k)] = $this->cleanValue($v);
                }
            }
            $this->dataInput['isDelete'] = true;
        }
    }

    /**
     * flie解析
     */
    private function fileParse():void {
        if (is_array($_FILES)) {
            foreach ($_FILES as $k => $v) {
                if (is_array($_FILES[$k])) {
                    foreach ($_FILES[$k] as $k2 => $v2) {
                        $this->dataInput[$k][$k2] = $v2;
                    }
                } else {
                    $this->dataInput[$k] = $v;
                }
            }
        }
    }

    /**
      +----------------------------------------------------------
     * 解析GET，POST，PUT,DELETE,FILES请求，并做数据过滤处理
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @return array()
      +----------------------------------------------------------
     */
    public function parse() {
        $this->initDataType();
        $this->getParse();
        $this->postParse();
        $this->putParse();
        $this->deleteParse();
        $this->fileParse();
        return $this->dataInput;
    }

    /**
      +----------------------------------------------------------
     * 过滤数组键
      +----------------------------------------------------------
     * @access private
      +----------------------------------------------------------
     * @param string $key
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    private function cleanKey($key) {
        if ($key == ""){
            return "";
        }
        $key = preg_replace("/\.\./", "", $key);
        $key = preg_replace("/\_\_(.+?)\_\_/", "", $key);
        $key = preg_replace("/^([\w\.\-\_]+)$/", "$1", $key);

        return $key;
    }

    /**
      +----------------------------------------------------------
     * 过滤数组值
      +----------------------------------------------------------
     * @access private
      +----------------------------------------------------------
     * @param string $val
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    private function cleanValue($val) {
        if ($val == ""){
            return "";
        }
        $val = str_replace("&#032;", " ", $val);
        $val = str_replace("&", "&amp;", $val);
        $val = str_replace("<!--", "&#60;&#33;--", $val);
        $val = str_replace("-->", "--&#62;", $val);
        $val = preg_replace("/<script/i", "&#60;script", $val);
        $val = str_replace(">", "&gt;", $val);
        $val = str_replace("<", "&lt;", $val);
        $val = str_replace("\"", "&quot;", $val);
        $val = preg_replace("/\n/", "<br>", $val);
        $val = preg_replace("/\\\$/", "&#036;", $val);
        $val = preg_replace("/\r/", "", $val);
        $val = str_replace("!", "&#33;", $val);
        $val = str_replace("'", "&#39;", $val);
        if (get_magic_quotes_gpc()) {
            $val = stripslashes($val);
        }
        $val = preg_replace("/\\\(?!&amp;#|\?#)/", "&#092;", $val);
        return $val;
    }

}

?>
