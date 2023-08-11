<?php
namespace QPHP\core\cache\memcache;

use Memcache;

/**
 * +------------------------------------------------------------------------------
 * Yzm Framework Memcache操作类
  * +------------------------------------------------------------------------------
 * @date    18-08
 * @author Jimmy Wang <1105235512@qq.com>
 * @version 1.0
  * +------------------------------------------------------------------------------
 */
class MmCache {

    public $mem = null;    //Memcache对象
    public $expire = 300;     //过期时间(5分钟)
    public $connected = false;   //连接标识
    public $compressed = false;   //是否启用数据压缩-暂时停止使用
    public $compressed_new = true;   //是否启用数据压缩
    public $prefix = 'yzm_';      //缓存键前缀

    /**
      +----------------------------------------------------------
     * 类的构造子
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */

    public function __construct($host = '127.0.0.1', $port = '11211') {
        if (!class_exists('Memcache')) {
            die('Not Support : Memcache');
        }
        $this->mem = new Memcache();
        $this->host = $host;
        $this->port = $port;
    }

    /**
      +----------------------------------------------------------
     * 类的析构方法(负责资源的清理工作)
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    public function __destruct() {
        $this->close();
        $this->mem = null;
        $this->expire = null;
        $this->connected = null;
        $this->compressed = null;
        $this->compressed_new = null;
        $this->prefix = null;
    }

    /**
      +----------------------------------------------------------
     * 打开Memcache连接
      +----------------------------------------------------------
     * @access private
      +----------------------------------------------------------
     */
    private function connect() {
        if (!$this->connected) {
            $this->connected = $this->mem->pconnect($this->host, $this->port);
            if (!$this->connected){
                die("连接Memcache失败");
            }
            $host = $port = null;
        }
    }

    /**
      +----------------------------------------------------------
     * 关闭Memcache连接
      +----------------------------------------------------------
     * @access private
      +----------------------------------------------------------
     */
    private function close() {
        if ($this->connected) {
            $this->mem->close();
            $this->connected = null;
        }
    }

    /**
      +----------------------------------------------------------
     * 写入缓存
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string  $key     缓存键值
     * @param mixed   $value   被缓存的数据
     * @param mixed   $expire  缓存时间
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public function set($key, $value, $expire = 0) {
        $data = serialize($value);
        if ($this->compressed_new && function_exists('gzcompress')) {
            if (!empty($data)){
                $data = gzcompress($data, 3);
            }
        }
        $expire = $expire > 0 ? $expire : $this->expire;
        if (!$this->connected || !isset($this->connected)){
            $this->connect();
        }
        return $this->mem->set(md5($this->prefix . $key), $data, 0, $expire);
    }

    /**
      +----------------------------------------------------------
     * 读取缓存
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $key 缓存键值
      +----------------------------------------------------------
     * @return mixed
      +----------------------------------------------------------
     */
    public function get($key) {
        if (!$this->connected || !isset($this->connected)){
            $this->connect();
        }
        $data = $this->mem->get(md5($this->prefix . $key));

        if (empty($data)){
            return '';
        }
        if ($this->compressed_new && function_exists('gzcompress')) {
            $data = gzuncompress($data);
        }
        return unserialize($data);
    }

    /**
      +----------------------------------------------------------
     * 删除缓存
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param  string $key 缓存键值
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public function remove($key) {
        if ($this->connected == null || !isset($this->connected)){
            $this->connect();
        }
        return $this->mem->delete(md5($this->prefix . $key));
    }

    /**
      +----------------------------------------------------------
     * 清除缓存(删除所有缓存数据)
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public function clear() {
        if ($this->connected == null || !isset($this->connected)){
            $this->connect();
        }
        return $this->mem->flush();
    }

}

?>
