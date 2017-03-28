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
    private $_timeout = 60;

    function __construct(){
        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, $this->_timeout);
    }

    public function close() {
        curl_close($this->_curl);
    }

    public function setHttps($set=true)
    {
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }

    public function setAgent($agent)
    {
        $this->_agent = $agent;
        curl_setopt($this->_curl, CURLOPT_USERAGENT, $agent);
    }

    public function post($url, $data)
    {
        $SSL = substr($url, 0, 8) == "https://" ? true : false;

        curl_setopt($this->_curl, CURLOPT_POST, 1);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
        if ($SSL) {
            curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书,
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 检查证书中是否设置域名
        }
        
        $result = curl_exec($this->_curl);
        if ($result === false) {
            throw new Exception(curl_error($this->_curl));
        }

        return $result;
    }

    public function get($url, $data)
    {
        $SSL = substr($url, 0, 8) == "https://" ? true : false;

        $url = $url . '?' . http_build_query($data);
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        curl_setopt($this->_curl, CURLOPT_POST, 0);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);

        if ($SSL) {
            curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书,
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 检查证书中是否设置域名
        }

        $result = curl_exec($this->_curl);
        if ($result === false) {
            throw new Exception(curl_error($this->_curl));
        }

        return $result;
    }

}

