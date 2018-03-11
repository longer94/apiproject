<?php
header("Content-Type: text/html; charset=utf-8");
/**
 * 引入个推的lib
 */
$path = APPPATH.'third_party/getui';
//$path = dirname(__FILE__).'../'.'application/third_party/getui';
require_once($path . '/' . 'IGt.Push.php');
require_once($path. '/' . 'igetui/IGt.AppMessage.php');
require_once($path. '/' . 'igetui/IGt.APNPayload.php');
require_once($path. '/' . 'igetui/template/IGt.BaseTemplate.php');
require_once($path . '/' . 'IGt.Batch.php');
require_once($path . '/' . 'igetui/utils/AppConditions.php');


//http的域名
define('HOST','http://sdk.open.api.igexin.com/apiex.htm');

//https的域名
//define('HOST','https://api.getui.com/apiex.htm');


define('APPKEY','E1yZIiXTOB6l1lACJi9jR5');
define('APPID','Qf52O0NyL76k4TC1L09AK4');
define('MASTERSECRET','mLByziIrZPAXsISJ55r1O6');
define('DEVICETOKEN','');
define('Alias','请输入别名');

class Push_model extends CI_Model {

    public $error_code = "";
    public $error_msg = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function single($cid, $msg)
    {
        //$igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET,false);

        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板

//    	$template = IGtNotyPopLoadTemplateDemo();
//    	$template = IGtLinkTemplateDemo();
//    	$template = IGtNotificationTemplateDemo();
        $template = $this->_IGtTransmissionTemplateDemo($msg);

        //个推信息体
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
//	$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId(APPID);
        $target->set_clientId($cid);
//    $target->set_alias(Alias);


        try {
            $rep = $igt->pushMessageToSingle($message, $target);


        }catch(RequestException $e){
            $requstId =e.getRequestId();
            $rep = $igt->pushMessageToSingle($message, $target,$requstId);
            $this->error_code = 2001;
            $this->error_msg = $rep['result'];
            return false;

        }

        return true;
    }

    public function toAll($msg)
    {
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $template = $this->_IGtTransmissionTemplateDemo($msg);
        //$template = IGtLinkTemplateDemo();
        //个推信息体
        //基于应用消息体
        $message = new IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);

        $appIdList=array(APPID);
        $phoneTypeList=array('ANDROID');
//        $provinceList=array('浙江');
//        $tagList=array('haha');
        //用户属性
        //$age = array("0000", "0010");


        $cdt = new AppConditions();
         $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
        // $cdt->addCondition(AppConditions::REGION, $provinceList);
        //$cdt->addCondition(AppConditions::TAG, $tagList);
        //$cdt->addCondition("age", $age);

        $message->set_appIdList($appIdList);
        $message->condition = $cdt;
        //$message->set_conditions($cdt->getCondition());

        $rep = $igt->pushMessageToApp($message);
        return true;
    }

    function _IGtTransmissionTemplateDemo($msg){
        $template =  new IGtTransmissionTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent($msg);//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //APN简单推送
//        $template = new IGtAPNTemplate();
//        $apn = new IGtAPNPayload();
//        $alertmsg=new SimpleAlertMsg();
//        $alertmsg->alertMsg="";
//        $apn->alertMsg=$alertmsg;
////        $apn->badge=2;
////        $apn->sound="";
//        $apn->add_customMsg("payload","payload");
//        $apn->contentAvailable=1;
//        $apn->category="ACTIONABLE";
//        $template->set_apnInfo($apn);
//        $message = new IGtSingleMessage();

        //APN高级推送
        $apn = new IGtAPNPayload();
        $alertmsg=new DictionaryAlertMsg();
        $alertmsg->body="body";
        $alertmsg->actionLocKey="ActionLockey";
        $alertmsg->locKey="LocKey";
        $alertmsg->locArgs=array("locargs");
        $alertmsg->launchImage="launchimage";
//        IOS8.2 支持
        $alertmsg->title="Title";
        $alertmsg->titleLocKey="TitleLocKey";
        $alertmsg->titleLocArgs=array("TitleLocArg");

        $apn->alertMsg=$alertmsg;
        $apn->badge=7;
        $apn->sound="";
        $apn->add_customMsg("payload","payload");
        $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);

        //PushApn老方式传参
//    $template = new IGtAPNTemplate();
//          $template->set_pushInfo("", 10, "", "com.gexin.ios.silence", "", "", "", "");

        return $template;
    }

}
?>