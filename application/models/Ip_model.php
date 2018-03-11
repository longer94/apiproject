<?php
header("Content-Type: text/html; charset=utf-8");
/**
 * 引入ip
 */
$path = APPPATH.'third_party';
//$path = dirname(__FILE__).'../'.'application/third_party/getui';
require_once($path . '/' . 'Ip.php');


class Ip_model extends CI_Model
{

    public $error_code = 0;
    public $error_msg = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function get( $ip )
    {
        $rep = ip::find($ip);
        return $rep;
    }


}