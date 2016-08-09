<?php
/**
*
*/
class ThirdWeixin
{

    private $appid;
    private $secret;
    private $openid;
    private $access_token;

    function __construct($appid,$secret)
    {
        $this->appid  = $appid;
        $this->secret = $secret;
    }

    // 文档:https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419317851&token=&lang=zh_CN
    public function getAccessToken($code)
    {
        $client = new HttpClient();
        $client->setHttps();
        $data = array(
            'appid'      => $this->appid,
            'secret'     => $this->secret,
            'code'       => $code,
            'grant_type' => 'authorization_code',
            );
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $rtn_json = $client->get($url,$data);
        $rtn = json_decode($rtn_json,true) ;

        if (isset($rtn['errcode'])) {
            echo $rtn_json;
            return false;
        }else{
            $this->openid       = $rtn['openid'];
            $this->access_token = $rtn['access_token'];
        }
    }

    public function getUserInfo($code)
    {
        $tokenInfo = $this->getAccessToken($code);
        if ($tokenInfo) {
            $client = new HttpClient();
            $client->setHttps();
            $data = array(
                'access_token' => $this->access_token,
                'openid'       => $this->openid,
                );
            $url = 'https://api.weixin.qq.com/sns/userinfo';
            $rtn_json = $client->get($url,$data);
            $rtn = json_decode($rtn_json,true) ;
            if (isset($d['errcode'])) {
                echo $rtn_json;
                return false;
            }else{
                return $rtn;
            }
        }else{
            return false;
        }

    }
}
?>
