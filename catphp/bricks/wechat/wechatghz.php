<?php


class WeChatGongZhong
{
    public $token;
    public $logger;

    public $FromUserName;
    public $ToUserName;

    public function __construct($appID,$appsecret,$token,$redis)
    {
        $this->appID     = $appID;
        $this->appsecret = $appsecret;
        $this->token     = $token;
        $this->redis     = $redis;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function validate()
    {
        if($this->signRight())
        {
            echo $_GET['echostr'];
        }else{

        }
    }

    public function signRight()
    {
        $signature = $_GET['signature'];
        $echostr   = $_GET['echostr'];
        $timestamp = $_GET['timestamp'];
        $nonce     = $_GET['nonce'];

        $tmparr = [$this->token,$timestamp,$nonce];
        sort($tmparr,SORT_STRING);
        $caculatehash = sha1(implode($tmparr));

        // $this->logger->debug($caculatehash,$signature);

        if ($caculatehash === $signature) {
            return true;
        }else{
            return false;
        }

    }

    public function getMessage()
    {
        $receiveData = $GLOBALS["HTTP_RAW_POST_DATA"];
        // $this->logger->debug($receiveData);
        if (!empty($receiveData)){
            $msgObject = simplexml_load_string($receiveData, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->FromUserName = $msgObject->FromUserName;
            $this->ToUserName   = $msgObject->ToUserName;
            // $MsgType      = $msgObject->MsgType;
            // $keyword      = trim($msgObject->Content);
        }
        // $this->logger->debug($msgObject);
        return $msgObject;
    }

    public function reply($message)
    {
        $data['ToUserName']   = $this->FromUserName;
        $data['FromUserName'] = $this->ToUserName;
        $data['Content']      = $message;
        $data['MsgType']      = 'text';
        $response = $this->response($data);

        echo $response;
        exit;
    }

    public function response($data)
    {
        // $xml = new XmlWriter();
        // $xml->openMemory();
        // $xml->startDocument();

        // foreach ($data as $key => $value) {
        //     $xml->startElement($key);
        //     $xml->writeCData($value);
        //     $xml->endElement();
        // }

        // $xml->writeElement('CreateTime',time());
        // return $xml->outputMemory(true);

        $xml = "<xml>";
        foreach ($data as $key => $value) {
            $xml.="<$key><![CDATA[$value]]></$key>";
        }
        $xml.="<CreateTime>".time()."<CreateTime>";
        $xml.="</xml>";

        return $xml;

    }
    public function sendTemplateMessage($touser,$template_id,$data,$detail_url=false,$miniprogram=false)
    {
        $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$this->access_token}";
        $post_data['touser']                  = $touser;
        $post_data['template_id']             = $template_id;
        if($url)
        {
            $post_data['url']                     = $detail_url;
        }
        if($miniprogram)
        {
            $post_data['miniprogram'] = $miniprogram;
            // $post_data['miniprogram']['appid']    = '';
            // $post_data['miniprogram']['pagepath'] = '';
        }
        $post_data['data'] = $data;
        // echo json_encode($post_data);
        $response = $this->https($url,json_encode($post_data));
        return $response;
    }
    // 获取accesstoken
    public function getAccessToken()
    {
        $key = 'wx:gzh:access_token'.$this->appID;
        $token = $this->redis->get($key);
        if($token)
        {
            $token_cache = json_decode($token,true);
            if(time()<$token_cache['expire_time'])
            {
                $this->access_token = $token_cache['access_token'];
                return $this->access_token;
            }
        }

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appID}&secret={$this->appsecret}";
        $rs = $this->https($url);
        if($rs){
            $decoded_data = json_decode($rs,true);

            $data_to_store['access_token'] = $decoded_data['access_token'];
            $data_to_store['expire_time'] = time()+$decoded_data['expires_in'];
            $this->redis->set($key,json_encode($data_to_store));
            $this->access_token = $data_to_store['access_token'];
            return $this->access_token;

        }
    }

    // 发送请求
    private function https($url,$data = array(),$timeout = 30)
    {
        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        $ch  = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);

        if ($SSL) {
            // todo
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书,
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 检查证书中是否设置域名
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $ret = curl_exec($ch);

        echo curl_error($ch);
        curl_close($ch);
        return $ret;
    }
}
