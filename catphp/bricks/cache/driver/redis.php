<?php

/**
* 
*/
class redisDriver
{
    private $redis;

    function __construct($config)
    {
        $defconfig  = array(
            'host'    => '127.0.0.1',
            'port'    => '6379',
            'password'=> false,
            'timeout' => 1,
            'db' => 0
            );
        $config = array_merge($defconfig, $config); 
        if(class_exists('Redis')) {
            $this->redis = new Redis();
            // 建立连接
            $this->redis->connect($config['host'],$config['port'],$config['timeout']);
            // 如果配置了密码则验证密码
            if ($config['password']) {
                if(!$this->redis->auth($config['password'])) {
                    throw new Exception("the password of Redis is wrong!", 1);                        
                }
            }
            $this->redis->select($config['db']);
        } else {
            throw new Exception("No model Redis,please install phpredis extension", 1);
        }
    }

    function get($key) {
        return $this->redis->get($key);
    }

    /**
     * @name set
     * @example:$cache->set('user','aaa');
     */
    public function set($key,$value) {
        return $this->redis->set($key,$value);
    }

    /**
     * @name delete
     * @example:$cache->delete('user');
     */
    public function delete($key) {
        return $this->redis->delete($key);
    }

    /**
     * @name clean
     * @example:$cache->clean();
     */
    public function clean() {
        $this->redis->flushDB();
    }

    /**
     * @name keys
     * @example:$cache->keys();
     */
    public function keys($match) {
        return $this->redis->keys($match);
    }
    /**
     * @name keys
     * @example:$cache->keys();
     */
    public function getInstance() {
        return $this->redis;
    }



}


?>