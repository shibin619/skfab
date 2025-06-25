<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CommonModel');
    }

    public function index() {
        $this->load->view('users/list'); // create this view
    }

    public function fetchUsers() {
        $table = 'users';
        $columns = ['id', 'name', 'email'];
        $searchable = ['name', 'email'];
        $order = ['id' => 'desc'];

        $this->CommonModel->set_datatable_params($table, $columns, $searchable, $order);

        $list = $this->CommonModel->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $user) {
            $no++;
            $row = [];
            $row[] = $user->id;
            $row[] = $user->name;
            $row[] = $user->email;
            $data[] = $row;
        }

        $output = [
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $this->CommonModel->count_all(),
            "recordsFiltered" => $this->CommonModel->count_filtered(),
            "data" => $data,
        ];

        echo json_encode($output);
    }


    
}
