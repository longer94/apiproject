<?php
class Sms_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insertRecord($uid, $content, $template)
    {
        $data = array(
            'uid' => $uid,
            'content' => $content,
            'template' => $template,
            'ctime' => time()
        );
        $this->db->set($data);
        return $this->db->insert('sms_record');
    }
}
?>