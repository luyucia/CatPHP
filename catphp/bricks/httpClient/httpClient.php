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


    private function _send()
    {
        $ch = curl_init();
        $timeout = 5;

        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if ($this->_method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_data);
        }

        if (!empty($this->_agent))
            curl_setopt($ch, CURLOPT_USERAGENT, $this->_agent);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function setAgent($agent)
    {
        $this->_agent = $agent;
    }

    public function post($url, $data)
    {
        $this->_method = "post";
        $this->_url = $url;
        $this->_data = $data;
        return $this->_send();
    }

    public function get($url, $data)
    {
        $this->_method = "get";
        $this->_url = $url . '?' . http_build_query($data);
        return $this->_send();
    }

}

