<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'third_party/sms/smsapi.class.php';

class Sms extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('user_model');
        $this->load->model('sms_model');
    }

    public function  send()
    {
        $submit = $this->input->get('submit', true);
        if (empty($submit)) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "您无权访问"
                )
            );
            return false;
        }

        $uid = $this->input->get('uid', true);
        //$content = $this->input->get('content', true);

        if (empty($uid)) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "发送用户名和内容不能为空！"
                )
            );
            return false;
        }

        $userInfo = $this->user_model->getUserById($uid);

        //接口账号
        $sms_uid = 'longer18';

        //登录密码
        $sms_pwd = 'tu317855';
        $sms = new SmsApi($sms_uid,$sms_pwd);
        $mobile = $userInfo['mobile'];
        //变量模板ID
        $template = '100006';
        $code = $sms->randNumber(4);

        //短信内容参数
        $contentParam = array(
            'code'		=> $code
        );

        //发送变量模板短信
        $result = $sms->send($mobile,$contentParam,$template);

        if($result['stat']=='100')
        {
            //记录短信发送状态
            $sms_id = $this->sms_model->insertRecord($uid, $code, $template);
            if (!$sms_id) {
                echo json_encode(
                    array(
                        'error_code' => 2001,
                        'error_msg' => "消息发送成功，但发送记录失败"
                    )
                );
                return false;
            }
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => "消息发送成功，并把数据记录到数据库",
                    'data' => $mobile
                )
            );
            return false;

        }
        else
        {
            echo '发送失败:'.$result['stat'].'('.$result['message'].')';
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => '发送失败:'.$result['stat'].'('.$result['message'].')'
                )
            );
            return false;
        }

    }

}
?>
