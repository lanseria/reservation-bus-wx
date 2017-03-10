<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
class UserController extends Controller {
	private	 $appid = "wx15d335404a6180fd";
	private  $appSecret = "d4624c36b6795d1d99dcf0547af5443d";
    private  $WechatAuth = null;
    //初始化WechatAuth类
    private  $access_token = null;
    //缓存token

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
    //获取所有标签
    public function selectTags(){
        $auth = $this->WechatAuth;
        $data = $auth->tagsGet();
        var_dump($data);
    }

	//创建一个标签
    public function createTags(){
        $auth = $this->WechatAuth;
        $data = $auth->tagsCreate('其他');
        var_dump($data);
    }
    //修改标签Name
    public function updateTags(){
        $auth = $this->WechatAuth;
        $data = $auth->tagsUpdate(101, 'VIP');
        var_dump($data);
    }
    //查询获取标签下粉丝列表
    public function getUsersByTagId(){
        $tagid = 100;
        $auth = $this->WechatAuth;
        $data = $auth->getUsersByTag($tagid);
        var_dump($data);
    }
    //获取用户列表
    public function getUserlist(){
        $auth = $this->WechatAuth;
        $data = $auth->userGet();
        var_dump($data);
    }
    //批量为用户打标签
    public function batchTagging(){
        $auth = $this->WechatAuth;
        $data = $auth->userGet();
        $data = $data['data'];
        $data = $data['openid'];
        $res = $auth->batchTags(100, $data);
        var_dump($res);
    }
	//查询一下指定用户的分组
    public function findUserGroupsId(){
      $Users = D('users');
      $user = $Users->find(1);
      $openid = $user['openid'];
      $auth = $this->WechatAuth;
      $data = $auth->groupsGetid($openid);
      var_dump($data);
  }
}