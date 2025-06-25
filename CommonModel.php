<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CommonModel extends CI_Model {

    private $table;
    private $column_order;
    private $column_search;
    private $order;

    public function set_datatable_params($table, $column_order, $column_search, $order = ['id' => 'desc']) {
        $this->table = $table;
        $this->column_order = $column_order;
        $this->column_search = $column_search;
        $this->order = $order;
    }

    private function _get_datatables_query($where = null) {
        $this->db->from($this->table);

        if ($where) {
            $this->db->where($where);
        }

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_datatables($where = null) {
        $this->_get_datatables_query($where);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    public function count_filtered($where = null) {
        $this->_get_datatables_query($where);
        return $this->db->get()->num_rows();
    }

    public function count_all($where = null) {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }
}
