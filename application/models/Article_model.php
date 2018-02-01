<?php
class Article_model extends CI_Model {

    public $error_msg = "";
    public $error_code = 0;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function add($title, $contents, $author, $cate, $artID)
    {
        if (!$artID) {
            $data = array(
                'title' => $title,
                'contents' => $contents,
                'author' => $author,
                'cate' => $cate
            );
            $this->db->insert('art', $data);
            return $this->db->insert_id();
        } else {
            $where = array('id' => $artID);
            $query = $this->db->get_where('art', $where);
            $result = $query->row_array();

            if (!$result) {
                return $id = 0;
            } else {
                $data = array(
                    'title' => $title,
                    'contents' => $contents,
                    'author' => $author,
                    'cate' => $cate
                );
                $where = array(
                    'id' => $artID
                );
                $this->db->update('art', $data, $where);
                return $artID;
            }
        }

    }

    public function getArticle($id)
    {
        $where = array(
            'id' => $id
        );
        $query = $this->db->get_where('art', $where);
        return $query->row_array();
    }

    public function delete($id)
    {
        $where = array(
            'id' => $id
        );
        return $this->db->delete('art', $where);
    }

    public function status($id, $status)
    {
        $this->db->where('id', $id);
        $data = array(
            'status' => $status
        );
        return $this->db->update('art', $data);
    }

    public function getList($pageNo, $pageSize, $cate, $status)
    {
        $start = $pageNo * $pageSize + ($pageNo == 0 ? 0: 1);
        $this->db->where('cate', $cate);
        $this->db->where('status', $status);
        $this->db->limit($pageSize, $start);
        $query = $this->db->get('art');
        $result = $query->result_array();

        if (!$result) {
            $this->error_code = -2011;
            $this->error_msg = "获取文章失败";
            return false;
        }


        $data = array();
        $cateInfo = array();

        foreach($result as $item) {

            //获取分类信息
            if (isset($cateInfo[$item['cate']])) {
                $cateName = $cateInfo[$item['cate']];
            } else {
                $where = array(
                    'id' => $item['cate']
                );
                $query = $this->db->get_where('cate', $where);
                $cate = $query->row_array();

                if (!$cate) {
                    $this->error_code = -2010;
                    $this->error_msg = "获取分类失败";
                    return false;
                }
                $cateName = $cateInfo[$item['cate']] = $cate['name'];
            }
            //正文太长得剪切
            $contents = mb_strlen($item['contents']) > 30 ? mb_string($item['contents'], 0, 30)."....." : $item['contents'];
            $data[] = array(
                'id' => intval($item['id']),
                'title' => $item['title'],
                'contents' => $contents,
                'author' => $item['author'],
                'cateName' => $cateName,
                'cateId' => intval($item['cate']),
                'ctime' => $item['ctime'],
                'mtime' => $item['mtime'],
                'status' => $item['status'],
            );
        }
        return $data;

    }
}
?>