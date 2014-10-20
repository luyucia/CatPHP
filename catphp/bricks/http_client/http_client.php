<?php

/**
* @author i@wangxu.org
*/
class HttpClient
{
    
    private $_url;
    private $_method;
    private $_agent;
    private $_data;
    private $_curl;
    private $_timeout = 10;

    function __construct(){
        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, $this->_timeout);
    }

    public function close() {
        curl_close($this->_curl);
    }

    public function setAgent($agent)
    {
        $this->_agent = $agent;
        curl_setopt($this->_curl, CURLOPT_USERAGENT, $agent);
    }

    public function post($url, $data)
    {
        curl_setopt($this->_curl, CURLOPT_POST, 1);
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($this->_curl, CURLOPT_URL, $url);

        $result = curl_exec($this->_curl);
        if ($result === false) {
            throw new Exception(curl_error($this->_curl));
        }

        return $result;
    }

    public function get($url, $data)
    {
        $url = $url . '?' . http_build_query($data);
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        curl_setopt($this->_curl, CURLOPT_POST, 0);

        $result = curl_exec($this->_curl);
        if ($result === false) {
            throw new Exception(curl_error($this->_curl));
        }

        return $result;
    }

}

