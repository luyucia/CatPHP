<?php

/**
* 封装微信sdk
* author：luyu
*/
class WechatSDK
{
    private $appid  = '';
    private $secret = '';

    function __construct($appid,$secret,$cache)
    {
        $this->appid  = $appid;
        $this->secret = $secret;
        $this->cache  = $cache;
    }
    // 获取code
    public function getCode($redirect_uri)
    {
        $state = rand(100000,999999);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";
        header("Location:$url");
    }

    // 获取微信用户信息，已包含了获取access_token
    public function getUserInfo()
    {
        if (isset($_GET['code'])) {
            $code  = $_GET['code'];
            $state = $_GET['state'];
        }else{
            $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $this->getCode($redirect_uri);
        }
        // 获取access_token
        $rs = $this->https("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->secret}&code=$code&grant_type=authorization_code");
        $result = json_decode($rs,true);
        if (isset($result['errcode'])) {
            // 如果code不正确，重新获取code
            if ($result['errcode']==40029) {
                $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $this->getCode($redirect_uri);
            }
        }else{
          // 获取用户信息
            $userinfo = $this->https("https://api.weixin.qq.com/sns/userinfo?access_token={$result['access_token']}&openid={$result['openid']}&lang=zh_CN");
            return json_decode($userinfo,true);
        }
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
          "appId"     => $this->appid,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage;
  }
  // 生成随机码
  private function createNonceStr($length = 16)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket()
  {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    // $data = json_decode($this->get_php_file("jsapi_ticket.php"));
    $data = json_decode($this->cache->get('jsapi_ticket'));
    if (!$data) {
        $data['jsapi_ticket'] = '';
        $data['expire_time']  = 0;
    }
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->https($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        // $this->set_php_file("jsapi_ticket.php", json_encode($data));
        $this->cache->set('jsapi_ticket',json_encode($data));
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

  // 获取jssdk的access_token
  private function getAccessToken()
  {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    // $data = json_decode($this->get_php_file("access_token.php"));
    $data = json_decode($this->cache->get("access_token"));
    if (!$data) {
        $data['access_token'] = '';
        $data['expire_time']  = 0;
    }

    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->secret";
      $res = json_decode($this->https($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        // $this->set_php_file("access_token.php", json_encode($data));
        $this->cache->set("access_token", json_encode($data));
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
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
