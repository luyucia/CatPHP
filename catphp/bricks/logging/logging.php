<?php

/**
* 日志记录类
*/
// 
class Logging
{
    private $date_format = 'Y-m-d H:i:s';
    private $log_file    = false;
    private $log_level  = array();

    const OFF   = 0;
    const TRACE = 1;
    const DEBUG = 2;
    const WARN  = 3;
    const ERROR = 4;
    const FATAL = 5;
    const ALL   = 6;

    function __construct($file=false,$level=Logging::ALL)
    {
        $this->log_file  = $file;
        $this->log_level = $level;
    }

    public function setLevel($level){
        if ($level=='OFF') {
            $this->log_level = Logging::OFF;
        }else if($level=='TRACE'){
            $this->log_level = Logging::TRACE;
        }else if($level=='DEBUG'){
            $this->log_level = Logging::DEBUG;
        }else if($level=='WARN'){
            $this->log_level = Logging::WARN;
        }else if($level=='ERROR'){
            $this->log_level = Logging::ERROR;
        }else if($level=='FATAL'){
            $this->log_level = Logging::FATAL;
        }else if($level=='ALL'){
            $this->log_level = Logging::ALL;
        }
        
    }

    public function setLogFile($file=false) {
        $this->log_file = $file;
        // 检查文件存在
    }

    public function trace($content , $tag='') {
        if ($this->log_level >= Logging::TRACE) {
            if(is_array($content)){
                $message = date($this->date_format)."\tTRACE\t".$tag."\t".print_r($content,true) ."\n";
            }else{
                $message = date($this->date_format)."\tTRACE\t".$tag."\t".$content."\n";
            }
            if ($this->log_file) {
                error_log($message , 3 , $this->log_file);
            }else{
                error_log($message);
            }
        }
    }

    public function debug($content , $tag='') {
        if ($this->log_level >= Logging::DEBUG) {
            if(is_array($content)){
                $message = date($this->date_format)."\tDEBUG\t".$tag."\t".print_r($content,true) ."\n";
            }else{
                $message = date($this->date_format)."\tDEBUG\t".$tag."\t".$content."\n";
            }
            if ($this->log_file) {
                error_log($message , 3 , $this->log_file);
            }else{
                error_log($message);
            }
        }
    }

    public function warn($content , $tag='') {
        if ($this->log_level >= Logging::WARN) {
            if(is_array($content)){
                $message = date($this->date_format)."\tWARN\t".$tag."\t".print_r($content,true) ."\n";
            }else{
                $message = date($this->date_format)."\tWARN\t".$tag."\t".$content."\n";
            }
            if ($this->log_file) {
                error_log($message , 3 , $this->log_file);
            }else{
                error_log($message);
            }
        }
    }

    public function error($content , $tag='') {
        if ($this->log_level >= Logging::ERROR) {
            if(is_array($content)){
                $message = date($this->date_format)."\tERROR\t".$tag."\t".print_r($content,true) ."\n";
            }else{
                $message = date($this->date_format)."\tERROR\t".$tag."\t".$content."\n";
            }
            if ($this->log_file) {
                error_log($message , 3 , $this->log_file);
            }else{
                error_log($message);
            }
        }
    }

    public function fatal($content , $tag='') {
        if ($this->log_level >= Logging::FATAL) {
            if(is_array($content)){
                $message = date($this->date_format)."\tFATAL\t".$tag."\t".print_r($content,true) ."\n";
            }else{
                $message = date($this->date_format)."\tFATAL\t".$tag."\t".$content."\n";
            }
            if ($this->log_file) {
                error_log($message , 3 , $this->log_file);
            }else{
                error_log($message);
            }
        }
    }

    public function setDateFormat($format){
        $this->date_format = $format;
    }
}

// $log = new Logging();
// $log->setLevel(Logging::ALL);
// $t = microtime();
// for ($i=0; $i < 10000; $i++) { 
//     $log->debug(array(1,2,4),"sdfdsf");
//     $log->fatal('error',"sdfdsf");
// }
// $log->trace(microtime() - $t,'耗时');



?>