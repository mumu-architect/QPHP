<?php
function dump($data=''){
    var_dump($data);
    exit;
}

/**
 * memcache
 * 设置缓冲
 * @param $key
 * @param $value
 * @param string $expire
 */
function setVar($key,$value,$expire='3600'){
    $mem = new MmCache(MEM_HOST,MEM_PORT);
    $mem->set($key,$value,$expire);
}

/**
 * memcache
 * 获取缓存
 * @param $key
 * @return mixed
 */
function getVar($key){
    $mem = new MmCache(MEM_HOST,MEM_PORT);
    return $mem->get($key);
}

/**
 * memcache
 * 删除缓存
 * @param $key
 * @return bool
 */
function delVal($key){
    $mem = new MmCache(MEM_HOST,MEM_PORT);
    return $mem->remove($key);
}

function R(){
    $redis = new QRedis(REDIS_HOST,REDIS_PORT);
    return $redis;
}
