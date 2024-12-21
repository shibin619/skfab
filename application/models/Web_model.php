<?php

class Web_model extends CI_Model {


    public function get_web_details()
    {
        $query = $this->db->query("SELECT code,content FROM `master_content_data` WHERE status=1");
        return $query->result_array();
    }


}

