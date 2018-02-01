<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller {

    public $error_code = 0;
    public $error_msg = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('article_model');

    }

    public function add( $artID = 0 )
    {
        if(!$this->_is_admin()) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "您无权访问该模块"
                )
            );

            return FALSE;
        }

        $title = $this->input->post('title', true);
        $contents = $this->input->post('contents', true);
        $author = $this->input->post('author', true);
        $cate = $this->input->post('cate', true);

        if ($title == '' || $contents == '' || $author =='' || $cate == '' ) {
            echo json_encode(array(
                'error_code' => 2001,
                'error_msg' => "文章标题、内容、作者不能为空！",
            ));
            return FALSE;
        }


        if ($artID === 0 )
        {
            $result = $this->article_model->add($title, $contents, $author, $cate, $artID);
            if ($result) {
                echo json_encode(array(
                    'error_code' => 1001,
                    'error_msg' => "添加文章成功!",
                    'data' => $title
                ));
                return TRUE;
            }else{
                echo json_encode(array(
                    'error_code' => 2001,
                    'error_msg' => "添加文章失败!"
                ));
                return FALSE;
            }
        }else{
            $id = $this->article_model->add($title, $contents, $author, $cate, $artID);
            if ($id) {
                echo json_encode(array(
                    'error_code' => 1001,
                    'error_msg' => "编辑文章成功!",
                    'data' => $title
                ));
                return TRUE;
            } else {
                echo json_encode(array(
                    'error_code' => 1001,
                    'error_msg' => "编辑文章失败!"
                ));
                return FALSE;
            }
        }

    }

    public function edit()
    {
        if(!$this->_is_admin()) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "您无权访问该模块"
                )
            );

            return FALSE;
        }

        $artID = $this->input->get('id');
        $this->add($artID);
    }

    public function delete()
    {
        if(!$this->_is_admin()) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "您无权访问该模块"
                )
            );

            return FALSE;
        }

        $id = $this->input->get('id', true);
        if (!is_numeric($id) && $id) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "传入的参数ID不合法！"
                )
            );
            return FALSE;
        }

        $result = $this->article_model->getArticle($id);

        if (!$result) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "传入参数的ID数据不存在！"
                )
            );
            return FALSE;
        }

        $del_id = $this->article_model->delete($id);
        if ($del_id) {
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => "删除成功！"
                )
            );
            return TRUE;
        }
    }

    public function status()
    {
        if(!$this->_is_admin()) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "您无权访问该模块"
                )
            );

            return FALSE;
        }

        $id = $this->input->get('id', true);
        $status = $this->input->get('status', true);
        if (!is_numeric($id) && $id) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "传入的参数ID不合法！"
                )
            );
            return FALSE;
        }

        $result = $this->article_model->getArticle($id);

        if (!$result) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "传入参数的ID数据不存在！"
                )
            );
            return FALSE;
        }

        $artId = $this->article_model->status($id, $status);
        if ($artId) {
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => "状态更改成功！状态为".$status,
                    'data' => $status
                )
            );

            return TRUE;
        }

    }

    public function get()
    {
        if(!$this->_is_admin()) {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "您无权访问该模块"
                )
            );

            return FALSE;
        }

        $id = $this->input->get('id', true);
        if (is_numeric($id) && $id) {

            $result = $this->article_model->getArticle($id);

            if (!$result) {
                echo json_encode(
                    array(
                        'error_code' => 2001,
                        'error_msg' => "传入参数的ID数据不存在！"
                    )
                );
                return FALSE;
            }

            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => "查询成功！",
                    'data' => $result
                )
            );
            return TRUE;

        } else {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => "传入的参数ID不合法！"
                )
            );
            return FALSE;
        }

    }


    public function getList()
    {
        $pageNo = $this->input->get('pageNo', true);
        $pageSize = $this->input->get('pageSize', true);
        $cate = $this->input->get('cate', true);
        $status = $this->input->get('status', true);

        $result = $this->article_model->getList($pageNo, $pageSize, $cate, $status);


        if ($result) {
            echo json_encode(
                array(
                    'error_code' => 1001,
                    'error_msg' => "数据读取成功",
                    'data' => $result
                )
            );

            return TRUE;
        } else {
            echo json_encode(
                array(
                    'error_code' => 2001,
                    'error_msg' => '获取数据失败，请检查参数！',
                )
            );

            return FALSE;
        }


    }

    /**
     * @return bool 权限验证
     */
    private function _is_admin()
    {
        return TRUE;
    }
}