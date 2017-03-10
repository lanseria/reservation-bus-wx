<?php
namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
class IndexController extends Controller{
    public function index($id = ''){
        try{
            $appid = 'wx15d335404a6180fd';
            $token = 'weixin';
            $crypt = '22XI2vwYAKV5Cwm6PFxse5tXqLN8QWqT6zgVKRK02mr'; 
            $wechat = new Wechat($token, $appid, $crypt);
            /* 获取请求信息 */
            $data = $wechat->request();
            if($data && is_array($data)){
                /**
                 * 你可以在这里分析数据，决定要返回给用户什么样的信息
                 * 接受到的信息类型有10种，分别使用下面10个常量标识
                 * Wechat::MSG_TYPE_TEXT       //文本消息
                 * Wechat::MSG_TYPE_IMAGE      //图片消息
                 * Wechat::MSG_TYPE_VOICE      //音频消息
                 * Wechat::MSG_TYPE_VIDEO      //视频消息
                 * Wechat::MSG_TYPE_SHORTVIDEO //视频消息
                 * Wechat::MSG_TYPE_MUSIC      //音乐消息
                 * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
                 * Wechat::MSG_TYPE_LOCATION   //位置消息
                 * Wechat::MSG_TYPE_LINK       //连接消息
                 * Wechat::MSG_TYPE_EVENT      //事件消息
                 *
                 * 事件消息又分为下面五种
                 * Wechat::MSG_EVENT_SUBSCRIBE    //订阅
                 * Wechat::MSG_EVENT_UNSUBSCRIBE  //取消订阅
                 * Wechat::MSG_EVENT_SCAN         //二维码扫描
                 * Wechat::MSG_EVENT_LOCATION     //报告位置
                 * Wechat::MSG_EVENT_CLICK        //菜单点击
                 */
                //记录微信推送过来的数据
                $this->Add_log("m", json_encode($data));
                //file_put_contents('./Public/Log/data.json', json_encode($data));
                /* 响应当前请求(自动回复) */
                //$wechat->response($content, $type);

                /**
                 * 响应当前请求还有以下方法可以使用
                 * 具体参数格式说明请参考文档
                 * 
                 * $wechat->replyText($text); //回复文本消息
                 * $wechat->replyImage($media_id); //回复图片消息
                 * $wechat->replyVoice($media_id); //回复音频消息
                 * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
                 * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
                 * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
                 * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
                 * 
                 */
                
                //执行Demo
                $this->demo($wechat, $data);
            }
        } catch(\Exception $e){
            $this->Add_log("e", json_encode($e->getMessage()));
        }
        
    }

    /**
     * DEMO
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function demo($wechat, $data){
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
            switch ($data['Event']) {
                case Wechat::MSG_EVENT_SUBSCRIBE:
                $title = "欢迎关注B12微信公众平台";
                $discription = "约车服务";
                $url = "http://www.topthink.com";
                //http://img.mukewang.com/577f7b830001bf8d12000460.jpg
                //http://wxtp.limonplayer.cn/Public/wel_img/index_img.jpg
                $picurl = "http://img.mukewang.com/577f7b830001bf8d12000460.jpg";
                $wechat->replyNewsOnce($title, $discription, $url, $picurl);
                break;

                case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                break;

                case Wechat::MSG_EVENT_CLICK:
                switch ($data['EventKey']) {
                    case 'lxt':
                    $wechat->replyImage('C-Gw-hEIEEN8TwH1Sk4gIWDlDeKwiL0VT1M-1kPpp7o');
                    break;
                    case 'wxpt':
                    $wechat->replyImage('C-Gw-hEIEEN8TwH1Sk4gITUdViNd8J05hfn5rzzqeZ0');
                    case 'gjyc':
                    $wechat->replyText('这是公交约车');
                    default:
                        # code...
                    break;
                }
                break;

                case Wechat::MSG_EVENT_SCAN:
                switch ($data['EventKey']) {
                    case '10086':
                    $wechat->replyText("欢迎使用10086");
                    break;
                    
                    default:
                    $wechat->replyText("欢迎使用...额。。。其他");
                    break;
                }

                default:
                $wechat->replyText("欢迎访问B12公众平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}，Latitude：{$data['Latitude']}，Longitude{$data['Longitude']}，Precision{$data['Precision']}");
                break;
            }
            break;

            case Wechat::MSG_TYPE_TEXT:
            switch ($data['Content']) {
                case '文本':
                $wechat->replyText('欢迎访问B12公众平台，这是文本回复的内容！');
                break;
                case 'my':
                $this->users($wechat, $data);
                case '图片':
                $media_id = $this->upload('image');
                //$media_id = '1J03FqvqN_jWX6xe8F-VJr7QHVTQsJBS6x4uwKuzyLE';
                $wechat->replyImage($media_id);
                break;
                case '语音':
                $media_id = $this->upload('voice');
                //$media_id = '1J03FqvqN_jWX6xe8F-VJgisW3vE28MpNljNnUeD3Pc';
                $wechat->replyVoice($media_id);
                break;

                case '视频':
                $media_id = $this->upload('video');
                //$media_id = '1J03FqvqN_jWX6xe8F-VJn9Qv0O96rcQgITYPxEIXiQ';
                $wechat->replyVideo($media_id, '视频标题', '视频描述信息。。。');
                break;

                case '音乐':
                $thumb_media_id = $this->upload('thumb');
                //$thumb_media_id = '1J03FqvqN_jWX6xe8F-VJrjYzcBAhhglm48EhwNoBLA';
                $wechat->replyMusic(
                    'Wakawaka!', 
                    'Shakira - Waka Waka, MaxRNB - Your first R/Hiphop source', 
                    'http://wechat.zjzit.cn/Public/music.mp3', 
                    'http://wechat.zjzit.cn/Public/music.mp3', 
                    $thumb_media_id
                        ); //回复音乐消息
                break;

                case 'tw':
                $wechat->replyNewsOnce(
                    "全民创业蒙的就是你，来一盆冷水吧！",
                    "全民创业已经如火如荼，然而创业是一个非常自我的过程，它是一种生活方式的选择。从外部的推动有助于提高创业的存活率，但是未必能够提高创新的成功率。第一次创业的人，至少90%以上都会以失败而告终。创业成功者大部分年龄在30岁到38岁之间，而且创业成功最高的概率是第三次创业。", 
                    "http://www.topthink.com/topic/11991.html",
                    "http://img.mukewang.com/577f7b830001bf8d12000460.jpg"
                        ); //回复单条图文消息
                break;

                case 'dtw':
                $news = array(
                    "全民创业蒙的就是你，来一盆冷水吧！",
                    "全民创业已经如火如荼，然而创业是一个非常自我的过程，它是一种生活方式的选择。从外部的推动有助于提高创业的存活率，但是未必能够提高创新的成功率。第一次创业的人，至少90%以上都会以失败而告终。创业成功者大部分年龄在30岁到38岁之间，而且创业成功最高的概率是第三次创业。", 
                    "http://www.topthink.com/topic/11991.html",
                    "http://mmbiz.qpic.cn/mmbiz/YnEByvYyb1JNy0hOjxZegGiaXw8ZbhAdT01jIMYRvQCTCp6siaJAiamsF02ZnGTM9SEibuBicenxJgdehC3tKthww9A/0?wx_fmt=jpeg"
                        ); //回复单条图文消息

                $wechat->replyNews($news, $news, $news, $news, $news);
                break;

                default:
                $wechat->replyText("欢迎访问B12公众平台！您输入的内容是：{$data['Content']}");
                break;
            }
            break;
            
            default:
                # code...
            break;
        }
    }
    private function users($wechat, $data){
        $openid = $data['FromUserName'];
        $appid     = 'wx15d335404a6180fd';
        $appsecret = 'd4624c36b6795d1d99dcf0547af5443d';
        $token = session("token");
        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }
        $user = $auth->userInfo($openid);
        $text = "你的openid是".$user['openid']."\n你的昵称是".$user['nickname']."\n性别是".$user['sex']."\n你的城市是".$user['city']."\n你的所在的国家是".$user['country']."\n你所在省份".$user['province']."\n";
        $this->Add_log("m", "发送用户的信息".$text);
        $wechat->replyText($text);
    }
    public function webusers(){
        $appid     = 'wx15d335404a6180fd';
        $appsecret = 'd4624c36b6795d1d99dcf0547af5443d';

        $token = session("token");
        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }
        if(I('get.iscode', 0)){
            $url = "http://wxtp.limonplayer.cn/Home/Index/webusers";
            $res = $auth->getRequestCodeURL($url);
            header("Location:{$res}");
            //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx15d335404a6180fd&redirect_uri=http%3A%2F%2Fwxtp.limonplayer.cn%2FHome%2FIndex%2Fwebusers&response_type=code&scope=snsapi_userinfo#wechat_redirect
            //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
        }
        else if(I('get.code', 0)){
            header('Content-type:text/html;charset=utf-8');
            $msg = $auth->getAccessToken('code', I('get.code'));
            $openid = $msg['openid'];
            $users = $auth->getUserInfo($openid);
            $Users = D('users');
            $data['openid'] = $users['openid'];
            $data['nickname'] = $users['nickname'];
            $data['sex'] = $users['sex'];
            $data['language'] = $users['language'];
            $data['city'] = $users['city'];
            $data['province'] = $users['province'];
            $data['country'] = $users['country'];
            $data['headimgurl'] = $users['headimgurl'];
            $r = $Users->add($data);
            if($r){
                $text = "你的openid是".$user['openid']."\n你的昵称是".$user['nickname']."\n性别是".$user['sex']."\n你的城市是".$user['city']."\n你的所在的国家是".$user['country']."\n你所在省份".$user['province']."\n";
            }
            var_dump($users);
        }
    }
    /**
     * 资源文件上传方法
     * @param  string $type 上传的资源类型
     * @return string       媒体资源ID
     */
    private function upload($type){
        $appid     = 'wx15d335404a6180fd';
        $appsecret = 'd4624c36b6795d1d99dcf0547af5443d';

        $token = session("token");

        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }

        switch ($type) {
            case 'image':
            $filename = './Public/image.jpg';
            $media    = $auth->materialAddMaterial($filename, $type);
            break;

            case 'voice':
            $filename = './Public/voice.mp3';
            $media    = $auth->materialAddMaterial($filename, $type);
            break;

            case 'video':
            $filename    = './Public/video.mp4';
            $discription = array('title' => '视频标题', 'introduction' => '视频描述');
            $media       = $auth->materialAddMaterial($filename, $type, $discription);
            break;

            case 'thumb':
            $filename = './Public/music.jpg';
            $media    = $auth->materialAddMaterial($filename, $type);
            break;
            
            default:
            return '';
        }

        if($media["errcode"] == 42001){ //access_token expired
            session("token", null);
            $this->upload($type);
        }

        return $media['media_id'];
    }
    private function Add_log($type, $content){
        $file_Size = 1000000;
        if($type=="e"){
            $log = "./Public/Log/error.json";
        }elseif($type=="m"){
            $log = "./Public/Log/data.json";
        }else{
            $log = "./Public/Log/data.json";
        }
        if(file_exists($log) && filesize($log) > $file_Size){
            unlink($log);
        }
        file_put_contents($log, date('Y-m-d H:i:s')." ".$content."\n", FILE_APPEND);
    }
}
