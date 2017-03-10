<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
class MenuController extends Controller{
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
	public function customMenu(){
		$WechatAuth=$this->WechatAuth;
		$button1 = array('name' => '综合服务',
			'sub_button'=>array(
				array('name' => 'B12路线路图',
					'type' => 'click',
					'key' =>'lxt',
					),
				array('name' => '郑州行',
					'type' => 'view',
					'url' =>'http://yulu.limonplayer.cn/Home/Index/zzx_app',
					),
				array('name' => '郑州公交网站',
					'type' => 'view',
					'url' =>'http://www.zhengzhoubus.com',
					),
				array('name' => '郑州公交一公司官方微信平台',
					'type' => 'click',
					'key' =>'wxpt',
					),
				array('name' => '失物招领',
					'type' => 'view',
					'url' =>'http://yulu.limonplayer.cn/Home/LostAndFound/index',
					),
				)
			);
		$button2 = array(
			'name'=>'公交约车',
			'type' => 'click',
			'key' =>'gjyc',
			);
		$button3 = array(
			'name'=>'乘客心声',
			'type' => 'view',
			'url' =>'http://yulu.limonplayer.cn/Home/PassengerCallback/index',
			);
		$res = $WechatAuth->menuCreate($button1, $button2, $button3);
		var_dump($res);
	}
}