<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipaddress extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Ip_model');
    }

    public function getIp()
    {
        $ip = $this->input->get('ip', true);
        if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP)) {
            echo json_encode(
                array(
                    'error' => -5001,
                    'errmsg' => "请传递正确的ip地址"
                )
            );
            return FALSE;
        }

        //调用model，查ip归属地
        $ips = $this->Ip_model->get(trim($ip));
        if($ip) {
            echo json_encode(
                array(
                    'error_code' => 0,
                    'error_msg' => "",
                    'data' => $ips
                ));
        } else {
            echo json_encode(
                array(
                    'error_code' => $this->Ip_model->error_code,
                    'error_msg' => $this->Ip_model->error_msg

                ));
        }
        return TRUE;
    }

}
