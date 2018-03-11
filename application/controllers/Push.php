<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('push_model');
    }

    public function single()
    {
        if (!$this->_isAdmin()) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "仅管理员可以进行此操作"
                )
            );
            return false;
        }

        $cid = $this->input->get('cid', true);
        $msg = $this->input->get('msg', true);
        if (!$cid || !$msg) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "请输入推送用户的设备ID与要推送的内容"
                )
            );
            return false;
        }

        $result = $this->push_model->single($cid, $msg);
        if ($result) {
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => "推送成功过!"
                )
            );
        } else {
            echo json_encode(
                array(
                    'error_cdoe' => $this->push_model->error_code,
                    'error_msg' => "推送失败".$this->push_model->error_msg
                )
            );
            return false;
        }
    }

    public function toAll()
    {
        if (!$this->_isAdmin()) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "仅管理员可以进行此操作"
                )
            );
            return false;
        }
        $msg = $this->input->get('msg', true);
        if (!$msg) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "请输入推送的内容"
                )
            );
            return false;
        }

        $result = $this->push_model->toAll($msg);
        if ($result) {
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => "推送成功过!"
                )
            );
        } else {
            echo json_encode(
                array(
                    'error_cdoe' => $this->push_model->error_code,
                    'error_msg' => "推送失败".$this->push_model->error_msg
                )
            );
            return false;
        }
    }

    public function _isAdmin()
    {
        return TRUE;
    }

}
?>
