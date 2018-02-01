<?php
class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getUser($name)
    {
        $query = $this->db->get_where('user', array('name' => $name));
        return $query->row_array();
    }

    public function register($name, $pwd)
    {
        $data = array(
            'name' => $name,
            'pwd' => $pwd
            );
        return $this->db->insert('user', $data);
    }

    public function login($name, $pwd)
    {
        $data = array(
            'name' => $name,
            'pwd' => $pwd
        );
        $query = $this->db->get_where('user', $data);
        return $query->row_array();

    }
}
?>