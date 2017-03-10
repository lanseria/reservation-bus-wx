<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
class MediaController extends Controller{
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
	public function add_News(){
		$WechatAuth=$this->WechatAuth;
		$articles['title'] = "欢迎关注B12微信公众平台";
        $articles['thumb_media_id'] = "C-Gw-hEIEEN8TwH1Sk4gITUdViNd8J05hfn5rzzqeZ0";//mediaID
        $articles['author'] = "Lanseria";
        $articles['digest'] = "欢迎关注B12微信公众平台";//摘要
        $articles['show_cover_pic'] = "1";
        $articles['content'] = "ashdjkhjdkahjkdhasjkh";
        $articles['content_source_url'] = "http://justcoding.iteye.com/blog/650843";
        echo "<pre>";
        $res = $WechatAuth->newsAddMaterial($articles);
        var_dump($res);
    }
}