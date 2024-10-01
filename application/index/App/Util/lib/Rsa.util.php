<?php
namespace admin\Util\lib;
/**
 * Class Rsa
 * 公钥用于对数据进行加密，私钥用于对数据进行解密；
 * 私钥用于对数据进行签名，公钥用于对签名进行验证。
 */
class RsaUtil
{
    /**
     * private key
     */
    private $_privKey;

    /**
     * public key
     */
    private $_pubKey;

    /**
     * the keys saving path
     */
    private $_keyPath= APP_PATH.'keys'.DIRECTORY_SEPARATOR;


    public function __construct ()
    {
        if (empty($this->_keyPath) || !is_dir($this->_keyPath)) {
            throw new \Exception('Must set the keys save path');
        }
        //设置私钥
        $file = $this->_keyPath . 'rsa_private_key.pem';
        $prk = file_get_contents($file);
        $this->_privKey = openssl_pkey_get_private($prk);
        //设置公钥
        $file = $this->_keyPath . 'rsa_public_key.pem';
        $puk = file_get_contents($file);
        $this->_pubKey = openssl_pkey_get_public($puk);
    }

    /**
     * RSA生成公私钥
     */
    public function createRsaKey(){

        // 生成密钥对
        $config = array(
            "digest_alg" => "sha256", // 加密算法
            "private_key_bits" => 2048, // 密钥长度（位数）
        );


        // 创建并保存私钥到文件
        $privateKey = openssl_pkey_new($config);
        if (!file_put_contents('private.key', $privateKey)) {
            die("无法将私钥写入文件");
        }

        // 获取公钥
        $publicKey = openssl_pkey_get_details($privateKey)['key'];
        if (empty($pubKey)) {
            die("无法从私钥中提取公钥");
        }

        echo '私钥内容：' . PHP_EOL;
        var_dump($privateKey);

        echo '公钥内容：' . PHP_EOL;
        var_dump($publicKey);
    }


    /**
     * setup the private key
     */
    public function setupPrivKey ()
    {
        if (is_resource($this->_privKey)) {
            return true;
        }
        $file = $this->_keyPath . 'rsa_private_key.pem';
        $prk = file_get_contents($file);
        $this->_privKey = openssl_pkey_get_private($prk);
        return true;
    }

    /**
     * setup the public key
     */
    public function setupPubKey ()
    {
        if (is_resource($this->_pubKey)) {
            return true;
        }
        $file = $this->_keyPath  . 'rsa_public_key.pem';
        $puk = file_get_contents($file);
        $this->_pubKey = openssl_pkey_get_public($puk);
        return true;
    }

    /**
     * 私钥加密
     * @function
     * @param $data
     * @return string|null
     */
    public function privEncrypt ($data)
    {
        if (!is_string($data)) {
            return null;
        }

        $r = openssl_private_encrypt($data, $encrypted, $this->_privKey);
        if ($r) {
            return base64_encode($encrypted);
        }
        return null;
    }

    /**
     * 私钥解密
     * @function
     * @param $data
     * @return string|null
     */
    public function privDecrypt ($encrypted)
    {
        if (!is_string($encrypted)) {
            return null;
        }
        $encrypted = base64_decode($encrypted);
        $r = openssl_private_decrypt($encrypted, $decrypted, $this->_privKey);
        if ($r) {
            return $decrypted;
        }
        return null;
    }

    /**
     * 公钥加密
     * @function
     * @param $data
     * @return string|null
     */
    public function pubEncrypt ($data)
    {
        if (!is_string($data)) {
            return null;
        }
        $r = openssl_public_encrypt($data, $encrypted, $this->_pubKey);
        if ($r) {
            return base64_encode($encrypted);
        }
        return null;
    }

    /**
     * 公钥解密
     * @function
     * @param $data
     * @return string|null
     */
    public function pubDecrypt ($crypted)
    {
        if (!is_string($crypted)) {
            return null;
        }
        $crypted = base64_decode($crypted);

        $r = openssl_public_decrypt($crypted, $decrypted, $this->_pubKey);
        if ($r) {
            return $decrypted;
        }
        return null;
    }

    /**
     * 私钥加签
     * @function
     * @param $data
     * @return string|null
     */
    public function sign ($data)
    {
        if (!is_string($data)) {
            return null;
        }
        openssl_sign($data, $sign, $this->_privKey);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 公钥验签
     * @function
     * @param $data
     * @return string|null
     */
    public function verify($data, $sign){
        if (!is_string($data)) {
            return null;
        }
        $result = (bool)openssl_verify($data, base64_decode($sign), $this->_pubKey);
        return $result;
    }
}

//使用例子：
class Index
{
    public function index()
    {
        try {
            $RSA = new RsaUtil();
        } catch (Exception $e) {
        }

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
        dump($result);
    }

}

