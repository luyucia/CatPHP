<?php


/**
*
*/
class RedisLock
{

    function __construct($redis)
    {
        $this->redis = $redis;
    }


    public function lock($key , $expire  = 5)
    {
        $this->lockKey = 'catlock:'.$key;
        // 如果能设置,说明获取锁成功
        $unlock = $this->redis->setnx($this->lockKey,1);
        if ($unlock) {
            $this->redis->expire($this->lockKey ,$expire);
        }
        return $unlock;
    }

    public function unlock()
    {
        $this->redis->del($this->lockKey );
    }


}


?>
