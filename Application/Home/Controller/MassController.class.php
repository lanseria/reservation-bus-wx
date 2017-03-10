<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
class MassController extends Controller {
    private  $appid = "wx15d335404a6180fd";
    private  $appSecret = "d4624c36b6795d1d99dcf0547af5443d";
    private  $WechatAuth = null;
    private  $access_token = null;

    public function __construct(){
        parent::__construct();
        header('Content-type:text/html;charset=utf-8;');
        if(!session('token')){
            $this->WechatAuth = new WechatAuth($this->appid,$this->appSecret);
            $auth = $this->WechatAuth;
            $token = $auth->getAccessToken();
            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
            $this->access_token = $token;
        }
        else{
            $token = session('token');
            $this->access_token = $token;
            $this->WechatAuth = new WechatAuth($this->appid,$this->appSecret,$token);
        }
    }

    //预览群发消息
    public function previewSend(){
    	$auth = $this->WechatAuth;
        $msgtype = "mpnews";
    	$openid = "ovd2HwIbxMcius4niLlWKgtkLbgc";
    	$content = "C-Gw-hEIEEN8TwH1Sk4gIYbP5NMETrlpsxtMXqsCLCQ";
    	$arr = $auth->messagePreviewSend($msgtype, $openid, $content);
    	var_dump($arr);
    }
    //模版发送
    public function SendTemplateMsg(){
        $openid = 'ovd2HwIbxMcius4niLlWKgtkLbgc';
        $template_id = 'RdrCFcQqRqqjFE3HYtNTw6vLOQiRl7NzM1difudOJ2s';
        $url = 'http://www.qq.com';
        $name = '感谢您使用此项服务';
        $money = '10元';
        $auth = $this->WechatAuth;
        $arr = $auth->messageTemplateSend($openid, $template_id, $url, $name, $money);
        var_dump($arr);
    }

}