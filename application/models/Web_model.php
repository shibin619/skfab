<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Web_model extends CI_Model {

    public function get_web_details()
    {
        $query = $this->db->query("SELECT code,content FROM `master_content_data` WHERE status=1");
        return $query->result_array();
    }

    public function insert_request($data) {
        return $this->db->insert('callback_requests', $data);
    }

    public function insert_message($data) {
        return $this->db->insert('contact_messages', $data);
    }
}

