<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
class UrlController extends Controller {
	private	 $appid="wx15d335404a6180fd";
  private  $appSecret="d4624c36b6795d1d99dcf0547af5443d";
  private  $WechatAuth="";
  private  $access_token="";

  public function __construct(){
    parent::__construct();
    header('Content-type:text/html;charset=utf-8;');
    if(!session('token')){
     $this->WechatAuth=new WechatAuth($this->appid,$this->appSecret);
     $WechatAuth=$this->WechatAuth;
     $token=$WechatAuth->getAccessToken();
     session(array('expire'=>$token['expires_in']));
     session('token',$token['access_token']);
     $this->access_token=$token;
   }else{
     $token=session('token');
     $this->WechatAuth=new WechatAuth($this->appid,$this->appSecret,$token);
     $this->access_token=$token;
   }

 }

 public function createCode(){
  $WechatAuth=$this->WechatAuth;
  $ticket=$WechatAuth->qrcodeCreate("10086");
  $src=$WechatAuth->showqrcode($ticket['ticket']);
  echo "<img width='70%' src=".$src.">";
  $data=$WechatAuth->shorturl($src);
  var_dump($data['short_url']);
  echo "<img width='70%' src=".$data['short_url'].">";
}

public function short(){
 $WechatAuth=$this->WechatAuth;
 $data=$WechatAuth->shorturl("https://www.baidu.com/s?wd=%E4%BA%8C%E7%BB%B4%E7%A0%81%20%20site%3Aweixin.qq.com&rsv_spt=1&rsv_iqid=0xd5fd971100012d5f&issp=1&f=8&rsv_bp=0&rsv_idx=2&ie=utf-8&tn=baiduhome_pg&rsv_enter=1&rsv_sug3=50&rsv_sug1=47&rsv_sug7=101&rsv_t=7abaVhRQjJ5QH1Gm31cGlwxbrFaVsxGW%2BMhj25yiYISF0qVkmQ8LfJjKYiyQlKoI2I0m&rsv_n=2&rsv_sug2=0&inputT=30922&rsv_sug4=31786");

 var_dump($data['short_url']);
}

}