<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class SuperAdmin extends CI_Controller {

    public function viewAllReferrals() {
        $role = $this->session->userdata('role');
        if ($role == 'User') redirect('DozenDreams/dashboard');
    
        $data['referrals'] = $this->spadmin->getAllReferralDetails();
        $data['role'] = $role;
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar', $data);
        $this->load->view('dozendreams/view_all_referrals', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function referralSettings() {
    
        if ($_POST) {
            $levels = $this->input->post('levels');
            foreach ($levels as $id => $percentage) {
                $this->db->where('id', $id)
                         ->update('referral_levels', [
                             'percentage' => $percentage,
                             'updated_at' => date('Y-m-d H:i:s')
                         ]);
            }
            $this->session->set_flashdata('success', 'Referral levels updated successfully!');
            redirect('DozenDreams/referralSettings');
        }
    
        $data['levels'] = $this->dozen->getReferralLevels();
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar', $data);
        $this->load->view('dozendreams/referral_settings', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    
    
}