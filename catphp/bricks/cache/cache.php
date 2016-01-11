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
            $type = $config['type'];
            // include 'driver/'.$type.'.php';
            $className = $type.'Driver';
            $this->_instance = new $className($config);
            // if ( === 'memcache') {
            //     include 'driver/memcache.php';
            //     $_instance = new memcacheDriver($config);
            // }else if ($config['type'] === 'redis') {
            //     $_instance = new redisDriver($config);
            // }
        }
    }

    /**
     * @name get
     * @example:$cache->get('user');
     */
    public function get($key) {
        return $this->_instance->get($key);
    }

    /**
     * @name set
     * @example:$cache->set('user','aaa');
     */
    public function set($key,$value) {
        return $this->_instance->set($key,$value);
    }

    /**
     * @name delete
     * @example:$cache->delete('user');
     */
    public function delete($key) {
        return $this->_instance->delete($key);
    }

    /**
     * @name clean
     * @example:$cache->clean();
     */
    public function clean() {
        return $this->_instance->clean();
    }

    /**
     * @name keys
     * @example:$cache->keys();
     */
    public function keys($match='*') {
        return $this->_instance->keys($match);
    }

    /**
     * @name keys
     * @example:$cache->getInstance();
     */
    public function getInstance() {
        return $this->_instance->getInstance();
    }

    public function cacheSql($sql,$db)
    {
        $key = md5($sql);
        $value = $this->_instance->get($key);
        if ($value) {
            return $value;
        }else{
            $value = $db->query($sql);
            $this->_instance->set($key,$value);
            return $value;
        }
    }
}



?>
