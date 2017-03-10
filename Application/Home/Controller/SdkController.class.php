<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
class SdkController extends Controller {
  private	 $appid="wx15d335404a6180fd";
  private  $appSecret="d4624c36b6795d1d99dcf0547af5443d";
  private  $WechatAuth="";
  private  $access_token="";
  private  $jsapi_ticket="";
  public function __construct(){
    parent::__construct();

    if(!session('token')){
      $this->WechatAuth=new WechatAuth($this->appid,$this->appSecret);
      $WechatAuth=$this->WechatAuth;
      $token=$WechatAuth->getAccessToken();
      session(array('expire'=>$token['expires_in']));
      session('token',$token['access_token']);
      $this->access_token=$token;
    }
    else{
      $token=session('token');
      $this->WechatAuth=new WechatAuth($this->appid,$this->appSecret,$token);
      $this->access_token=$token;
    }
    if (!session('jsapi_ticket')){
      $accessToken = $this->access_token;
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $this->jsapi_ticket = $res->ticket;
      session(array('expire'=>7000));
      session('jsapi_ticket',$this->jsapi_ticket);
    }
    else{
      $this->jsapi_ticket = session('jsapi_ticket');
    }
  }

  public function index(){
    $data = $this->getSignPackage();
    $this->assign('data', $data);
    $this->display();
  }


  public function getSignPackage() {
    $jsapiTicket = $this->jsapi_ticket;
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr = $this->createNonceStr();
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

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
  }
}