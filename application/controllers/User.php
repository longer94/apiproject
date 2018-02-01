<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('user_model');
    }

    public function login()
    {
        $submit = $this->input->post('submit', true);
        $name = $this->input->post('name', true);
        $pwd = $this->input->post('pwd', true);
        if (empty($submit))
        {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => '您无权限访问'
                )
            );
            return false;
        }

        if ($name === NULL || $pwd === NULL )
        {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => '姓名或密码不能为空'
                )
            );
            return false;
        }

        $rows = $this->user_model->login($name, $pwd);
        if ($rows) {
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => '登录成功'
                )
            );
            return true;
        }


    }

    /**
     * 注册方法
     * @param string $name
     * @param string $password
     */
    public function register()
    {
        $name = $this->input->post('name', true);
        $pwd = $this->input->post('pwd', true);

        if ($name === NULL || $pwd === NULL )
        {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => '姓名和密码不能为空'
                )
            );
            return false;
        }

        $result = $this->user_model->getUser($name);
        if ($result)
        {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => '该用户名已经被注册！'
                )
            );
            return false;
        }

        $rows = $this->user_model->register($name, $pwd);
        if ($rows)
        {
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => '注册成功',
                    'error_data' => $name
                )
            );
            return true;
        }


    }
}
?>
