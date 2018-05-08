<?php
session_start();

include_once('config.php');
include_once('saetv2.ex.class.php');

$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);

if (isset($_REQUEST['code'])) {
    $keys                 = array();
    $keys['code']         = $_REQUEST['code'];
    $keys['redirect_uri'] = WB_CALLBACK_URL;
    try {
        $token = $o->getAccessToken('code', $keys);
    } catch (OAuthException $e) {
    }
}

// 根据token 可以获取用户的相关信息
if ($token) {
    $_SESSION['token'] = $token;
    setcookie('weibojs_' . $o->client_id, http_build_query($token));
    
    $c  = new SaeTClientV2(WB_AKEY, WB_SKEY, $_SESSION['token']['access_token']);
    $ms = $c->home_timeline();  // 获取当前登录用户及其所关注用户的最新微博消息。
    
    $uid_get = $c->get_uid();
    $uid     = $uid_get['uid'];
    $user_info = $c->show_user_by_id($uid);
    print_r($user_info);
    die;
//    $data = array(
//            'access_token'=>$token,
//            'title'=>'测试',
//            'width'=>'360',
//            'height'=>'480',
//    );
// $ret = $o->post('https://api.weibo.com/2/proxy/live/create',$data);
// var_dump($ret);die;
//
 

?>
    授权完成,<a href="weibolist.php">进入你的微博列表页面</a><br/>
    <?php
} else {
    ?>
    授权失败。
    <?php
}
?>
