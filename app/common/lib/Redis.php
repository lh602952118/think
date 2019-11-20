<?php

namespace app\common\lib;
/**
 * @Author: MrLi
 * @Date: 2019/11/20 10:23
 * @Desc: 获取Redis
 */
class Redis
{
    static $testKey = "test_";
    public $redis = null;
    protected static $instance;

    protected function __clone()
    {
    } //禁止被克隆

    function __construct($host="127.0.0.1",$port=6379)
    {
        $this->redis = new \Swoole\Coroutine\Redis();
        $this->redis->connect($host,$port);
    }
    /**
     * 单例
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(config("redis.host"),config("redis.port"));
        }
        return self::$instance;
    }
    function set($key,$val,$time = 0){
        if(empty($key)){
            return "";
        }
        if(is_array($val)){
            $val = json_encode($val);
        }
        if(!$time){
            return $this->redis->set($key,$val);
        }
        return $this->redis->set($key,$val,$time);

    }
    function get($key){
        if(empty($key)){
            return "";
        }
        return$this->redis->get($key);
    }
}