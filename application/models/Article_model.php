<?php
class Article_model extends CI_Model {

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
}
?>