<?php
/**
 * Created by PhpStorm.
 * User: wuzhe
 * Date: 14-7-17
 * Time: 上午11:02
 */
class MongoDriver
{

    public $mongo;
    public $curr_db;
    public $error;
    static private $_conf;

    private static $_instance;

    private function __construct($config = array(), $connect = true, $auto_balance = true)
    {
        try {
            if(!empty($config)){
                $mongo_server = "mongodb://".$config['username'].":".$config['password']."@".$config['host'].":".$config['port']."/".$config['database'];
//                $mongo_server = "mongodb://cube:cube.xoyo.com@10.234.10.22:27017/cube";
                $this->mongo = new MongoClient($mongo_server, array('connect' => $connect));
            }else{
                $this->mongo = new MongoClient();
            }
        } catch (MongoConnectionException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public static function getInstance($config = array())
    {
        if (!(self::$_instance instanceof self) || $config != self::$_conf) {
            self::$_conf = $config;
            self::$_instance = new self($config);
        }

        return self::$_instance;
    }

    public function connect()
    {
        try {
            $this->mongo->connect();
            return true;
        } catch (MongoConnectionException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function selectDb($db)
    {
        $this->curr_db = $db;
    }

    public function insert($collection, $record)
    {
        $db = $this->curr_db;
        try {
            $this->mongo->$db->$collection->insert($record, array('w' => true));
            return true;
        } catch (MongoCursorException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function count($collection, $query = array())
    {
        $db = $this->curr_db;
        return $this->mongo->$db->$collection->count($query);
    }

    public function update($collection, $condition, $newdata, $options = array())
    {
        $db = $this->curr_db;
        $options['w'] = true;
        if (!isset($options['multiple'])) {
            $options['multiple'] = 0;
        }
        try {
            $this->mongo->$db->$collection->update($condition, $newdata, $options);
            return true;
        } catch (MongoCursorException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function remove($collection, $condition, $options = array())
    {
        $db = $this->curr_db;
        $options['w'] = true;
        try {
            $this->mongo->$db->$collection->remove($condition, $options);
            return true;
        } catch (MongoCursorException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function find($collection, $query_condition = array(), $result_condition = array(), $fields = array())
    {
        $db = $this->curr_db;
        $cursor = $this->mongo->$db->$collection->find($query_condition, $fields);
        if (!empty($result_condition['start'])) {
            $cursor->skip($result_condition['start']);
        }
        if (!empty($result_condition['limit'])) {
            $cursor->limit($result_condition['limit']);
        }
        if (!empty($result_condition['sort'])) {
            $cursor->sort($result_condition['sort']);
        }
        $result = array();
        try {
            while ($cursor->hasNext()) {
                $result[] = $cursor->getNext();
            }
        } catch (MongoConnectionException $e) {
            $this->error = $e->getMessage();
            return false;
        } catch (MongoCursorTimeoutException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return $result;
    }

    public function findOne($collection, $condition = array(), $fields = array())
    {
        $db = $this->curr_db;
        return $this->mongo->$db->$collection->findOne($condition, $fields);
    }

}