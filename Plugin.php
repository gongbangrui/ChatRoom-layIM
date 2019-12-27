<?php
/**
 * layim-聊天室
 *
 * @package ChatRoom
 * @author  高彬展
 * @version 1.0.1
 * @link    https://www.gaobinzhan.com
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
// 时间区域
date_default_timezone_set('Asia/Shanghai');

class ChatRoom_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('ChatRoom_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('ChatRoom_Plugin', 'footer');
        return "聊天室启动成功";
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $title = new Typecho_Widget_Helper_Form_Element_Text('title', null, _t('ChatRoom'), _t('主面板最小化后显示的名称'));
        $form->addInput($title);


        $min = new Typecho_Widget_Helper_Form_Element_Radio('min', [
            '0' => '否',
            '1' => '是'
        ], '0', _t('用于设定主面板是否在页面打开时，始终最小化展现'));
        $form->addInput($min);

        $right = new Typecho_Widget_Helper_Form_Element_Text('right', null, _t('0px'), _t('用于设定主面板右偏移量。该参数可避免遮盖你页面右下角已经的bar 如：0px'));
        $form->addInput($right);


        $minRight = new Typecho_Widget_Helper_Form_Element_Text('minRight', null, _t(''), _t('用户控制聊天面板最小化时、及新消息提示层的相对right的px坐标。
如：200px'));
        $form->addInput($minRight);

        $initSkin = new Typecho_Widget_Helper_Form_Element_Text('initSkin', null, _t(''), _t('设置初始背景，默认不开启。可设置./css/modules/layim/skin目录下的图片文件名
如：initSkin: 5.jpg'));
        $form->addInput($initSkin);

        $isAudio = new Typecho_Widget_Helper_Form_Element_Radio('isAudio', [
            '0' => '否',
            '1' => '是'
        ], '0', _t('是否开启聊天工具栏音频'));
        $form->addInput($isAudio);

        $isVideo = new Typecho_Widget_Helper_Form_Element_Radio('isVideo', [
            '0' => '否',
            '1' => '是'
        ], '0', _t('是否开启开启聊天工具栏视频'));
        $form->addInput($isVideo);

        $notice = new Typecho_Widget_Helper_Form_Element_Radio('notice', [
            '0' => '否',
            '1' => '是'
        ], '0', _t('是否开启桌面消息提醒，即在浏览器之外的提醒'));
        $form->addInput($notice);

        $voice = new Typecho_Widget_Helper_Form_Element_Text('voice', null, _t('default.mp3'), _t('设定消息提醒的声音文件（所在目录：./layui/css/modules/layim/voice/）
若不开启，设置 false 即可'));
        $form->addInput($voice);

        $isFriend = new Typecho_Widget_Helper_Form_Element_Radio('isFriend', [
            '0' => '否',
            '1' => '是'
        ], '0', _t('是否开启好友'));
        $form->addInput($isFriend);

        $isGroup = new Typecho_Widget_Helper_Form_Element_Radio('isGroup', [
            '0' => '否',
            '1' => '是'
        ], '0', _t('	是否开启群组'));
        $form->addInput($isGroup);

        $maxLength = new Typecho_Widget_Helper_Form_Element_Text('maxLength', null, _t(3000), _t('可允许的消息最大字符长度'));
        $form->addInput($maxLength);

        $copyright = new Typecho_Widget_Helper_Form_Element_Radio('copyright', [
            '0' => '否',
            '1' => '是'
        ], '0', _t('	是否授权。如果非授权获得，或将LayIM应用在第三方，建议保留，即不设置。'));
        $form->addInput($copyright);

        $username = new Typecho_Widget_Helper_Form_Element_Text('username', null, _t('游客'), _t('游客昵称前缀'));
        $form->addInput($username);

        $sign = new Typecho_Widget_Helper_Form_Element_Text('sign', null, _t('在深邃的编码世界，加油努力！'), _t('游客个性签名'));
        $form->addInput($sign);

        $avatar = new Typecho_Widget_Helper_Form_Element_Text('avatar', null, _t('http://qiniu.gaobinzhan.com/2019/11/03/fe452030092f5.jpg'), _t('游客默认头像url连接'));
        $form->addInput($avatar);

        $groupName = new Typecho_Widget_Helper_Form_Element_Text('groupName', null, _t('技术交流群'), _t('群组名'));
        $form->addInput($groupName);

        $groupAvatar = new Typecho_Widget_Helper_Form_Element_Text('groupAvatar', null, _t('http://qiniu.gaobinzhan.com/2019/11/05/d702b8af262a3.jpeg'), _t('群组头像url连接'));
        $form->addInput($groupAvatar);

    }

    public static function deactivate()
    {
        // TODO: Implement deactivate() method.
        return "聊天室禁用成功";
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
        // TODO: Implement personalConfig() method.
    }

    public static function header()
    {
        $path = Helper::options()->pluginUrl . '/ChatRoom/';
        echo '<link rel="stylesheet" type="text/css" href="' . $path . 'css/layui.css" />';
    }

    public static function makeConfig()
    {
        $options = Helper::options()->plugin('ChatRoom');
        $config = [
            'title'     => $options->title,
            'min'       => (boolean)$options->min,
            'right'     => $options->right,
            'minRight'  => $options->minRight,
            'initSkin'  => $options->initSkin,
            'isAudio'   => (boolean)$options->isAudio,
            'isVideo'   => (boolean)$options->isVideo,
            'notice'    => (boolean)$options->notice,
            'voice'     => $options->voice,
            'isfriend'  => (boolean)$options->isFriend,
            'isgroup'   => (boolean)$options->isGroup,
            'maxLength' => $options->maxLength,
            'copyright' => (boolean)$options->copyright,
            'init'      => [
                'mine'  => [
                    'username'  => $options->username,
                    'sign'      => $options->sign,
                    'status'    => 'online',
                    'avatar'    => $options->avatar,
                ],
                'group' => [
                    [
                        'id'        => '101',
                        'groupname' => $options->groupName,
                        'avatar' => $options->groupAvatar
                    ]
                ]
            ]
        ];
        return json_encode($config);
    }

    public static function getUrl(){
        $url = [
            'dev' => [
                'ws_url'        => 'ws://127.0.0.1:9501',
                'images_url'    => 'http://127.0.0.1:9501/util/images/upload',
                'upload_url'    => 'http://127.0.0.1:9501/util/images/upload'
            ],
            'prod' => [
                'ws_url'        => 'wss://chat.gaobinzhan.com/websocket',
                'images_url'    => 'https://chat.gaobinzhan.com/util/images/upload',
                'upload_url'    => 'https://chat.gaobinzhan.com/util/images/upload'
            ]
        ];
        return json_encode($url['prod']);
    }

    public static function footer()
    {
        $path = Helper::options()->pluginUrl . '/ChatRoom/';
        $config = self::makeConfig();
        $url = self::getUrl();
        echo '<script type="text/javascript" src="' . $path . 'layui.js"></script>';
        echo <<<EOF
<script type="text/javascript">
;layui.use('layim',function(layim){let Science=$url;let version='0.1.1';let host=window.location.host;let id=Math.floor(Math.random()*10000+1);let sign=Base64.encode('host='+host+'&v='+version+'&id='+id);let status=null;let config=$config;config.init.mine.id=id;config.init.mine.username+=id;config.members={url:'',type:'get',data:{}},config.uploadImage={url:Science.images_url,type:'post'},config.uploadFile={url:Science.upload_url,type:'post'},config.tool=[{alias:'code',title:'代码',icon:'&#xe64e;'}],layim.on('tool(code)',function(insert,send,obj){layer.prompt({title:'插入代码',formType:2,shade:0},function(text,index){layer.close(index);insert('[pre class=layui-code]'+text+'[/pre]')})});layim.config(config);var socket=new WebSocket(Science.ws_url+'?sign='+sign);socket.onopen=function(){status='200';layer.msg('聊天室连接成功！')};layim.on('sendMessage',function(res){if(status!=200)layer.msg('消息发送失败！！！');res.sign=sign;socket.send(JSON.stringify({type:'chatMessage',data:res}))});socket.onmessage=function(res){data=JSON.parse(res.data);layim.getMessage(data)};socket.onclose=function(res){status=res.code;layer.msg(res.code+':'+res.reason)}});var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};;
</script>
EOF;

    }
}