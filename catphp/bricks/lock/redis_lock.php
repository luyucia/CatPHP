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
        // $expireTime  = time()+$expire;
        // $lock = $this->redis->setnx($this->lockKey,$expireTime);

        // if ($lock) {
        //     $this->redis->expire($this->lockKey ,$expire);
        //     return true;
        // }

        while (1) {
            $expireTime  = time()+$expire;
            // 如果能设置,说明获取锁成功
            $lock = $this->redis->setnx($this->lockKey,$expireTime);

            if ($lock) {
                // 得到锁
                $this->redis->expire($this->lockKey ,$expire);
                return true;
            }else{
                // 没得到
                $lockExpireTime = $this->redis->get($this->lockKey);
                if(time() > $lockExpireTime){
                    // 如果过期了 主动解锁
                    $this->unlock();
                }else{
                    // 没过期则继续等待
                    usleep(10);
                }
            }
        }
    }

    public function unlock()
    {
        $this->redis->del($this->lockKey );
    }


}


?>
