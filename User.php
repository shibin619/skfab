<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();

    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $userData = $this->user->getUserData();  // Now accessible as $this->user
        $data['matches'] = $this->user->getUpcomingMatches();
        $data['user'] = $userData;
        $data['wallets'] = $this->db
        ->select('w.balance, wt.name as type')
        ->from('wallets w')
        ->join('wallet_types wt', 'wt.id = w.wallet_type_id', 'left')
        ->where('w.user_id', $user_id)
        ->get()
        ->result_array();
        $this->load->view('dozendreams/user/header');
        $this->load->view('dozendreams/user/sidebar');
        $this->load->view('dozendreams/user/dashboard', $data);
        $this->load->view('dozendreams/user/footer');
    }
    


    
    
    
}