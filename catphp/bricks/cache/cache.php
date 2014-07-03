<?php

/**
* @author luyucia@gmail.com
*/
class Cache
{
    
    private $_instance;

    function __construct($config)
    {
        if(is_array($config)) {
            if ($config['type'] === 'memcache') {
                include 'driver/memcache.php';
                $_instance = new memcacheDriver($config);
            }else if ($config['type'] === 'redis') {
                $_instance = new redisDriver($config);
            }
        }
    }

    /**
     * @name get
     * @example:$cache->get('user');
     */
    public function get() {
        echo 'get';
    }

    /**
     * @name set
     * @example:$cache->set('user','aaa');
     */
    public function set() {

    }

    /**
     * @name delete
     * @example:$cache->delete('user');
     */
    public function delete() {

    }

    /**
     * @name clean
     * @example:$cache->clean();
     */
    public function clean() {

    }

    /**
     * @name keys
     * @example:$cache->keys();
     */
    public function keys() {

    }
}



?>