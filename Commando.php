<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Commando extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    // ✅ This function will run as cron OR manually (via browser, secure it if needed)
    public function generateUpcomingMatches() {
        // ✅ Allow only certain logged-in roles
        if (
            !$this->session->userdata('logged_in') ||
            (
                !$this->session->userdata('is_commando') &&
                !$this->session->userdata('is_tester')
            )
        ) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
            return;
        }
    
        $today = date('Y-m-d H:i:s');
        $nextWeek = date('Y-m-d H:i:s', strtotime('+7 days'));
    
        // ✅ Fetch upcoming schedules not yet inserted in matches
        $query = $this->db->select('s.id AS schedule_id, s.match_date, t1.name AS team1_name, t2.name AS team2_name, v.name AS venue')
            ->from('schedules s')
            ->join('teams t1', 't1.id = s.team_a_id')
            ->join('teams t2', 't2.id = s.team_b_id')
            ->join('venues v', 'v.id = s.venue_id', 'left')
            ->join('matches m', 's.id = m.schedule_id', 'left')
            ->where('s.match_date >=', $today)
            ->where('s.match_date <=', $nextWeek)
            ->where('m.schedule_id IS NULL')
            ->order_by('s.match_date', 'ASC')
            ->get();
    
        $schedules = $query->result_array();
    
        $inserted_count = 0;
    
        if (!empty($schedules)) {
            foreach ($schedules as $row) {
                $this->db->insert('matches', [
                    'schedule_id' => $row['schedule_id'],
                    'lineup_status_id' => 1 // Default: Not Announced
                ]);
                $inserted_count++;
            }
        }
    
        $data['schedules'] = $schedules;
        $data['inserted_count'] = $inserted_count;
    
        // ✅ Load visual feedback view
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/generated_matches', $data); // <-- you’ll create this view
        $this->load->view('dozendreams/layout/footer');
    }
    
    
    public function cronList() {
        // Restrict access
        if (
            !$this->session->userdata('is_commando') &&
            !$this->session->userdata('is_tester')
        ) {
            $this->session->set_flashdata('error', 'Unauthorized Access!');
            redirect('DozenDreams/dashboard');
            return;
        }
    
        // ✅ Now also fetching last_run_role
        $this->db->select('c.*, m.name AS status_name, u.name AS updated_by, c.last_run_role')
            ->from('crons c')
            ->join('masters m', 'm.id = c.status_id', 'left')
            ->join('users u', 'u.id = c.last_run_by', 'left')
            ->where('DATE(c.created_at)', date('Y-m-d'))
            ->where_in('c.status_id', [1]) // Pending
            ->order_by('c.created_at', 'DESC');
    
        $data['crons'] = $this->db->get()->result_array();
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/cron_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    

    public function runCron($cron_id) {
        if (
            !$this->session->userdata('is_commando') &&
            !$this->session->userdata('is_tester')
        ) {
            $this->session->set_flashdata('error', 'Unauthorized Access!');
            redirect('DozenDreams/dashboard');
        }
    
        $user_id = $this->session->userdata('user_id');
    
        // Fetch the cron by ID
        $cron = $this->db->get_where('crons', ['id' => $cron_id])->row_array();
        if (!$cron) {
            $this->session->set_flashdata('error', 'Cron not found.');
            redirect('DozenDreams/cronList');
        }
    
        // Simulate cron run (actual logic to be implemented)
        $run_status = true; // Assume success
    
        $status_id = $run_status ? 2 : 3; // 2 = Completed, 3 = Failed
    
        $role = $this->session->userdata('user_role'); // Fetch user role

        $this->db->where('id', $cron_id)->update('crons', [
            'status_id' => $status_id,
            'last_run_by' => $user_id,
            'last_run_role' => $role,
            'last_run_time' => date('Y-m-d H:i:s')
        ]);
    
        $this->session->set_flashdata('success', 'Cron executed successfully.');
        redirect('DozenDreams/cronList');
    }

    // 🧠 CRON: Pair Income Evaluation based on dream_tree
public function checkPairIncomeEligibleUsers()
{
    // For security: allow CLI or commando/tester login
    if (!$this->input->is_cli_request() && 
        (!$this->session->userdata('is_commando') && !$this->session->userdata('is_tester'))
    ) {
        show_error('Access denied: CLI or authorized role only.');
    }

    $eligible_users = $this->db->select('user_id')
        ->from('dream_tree')
        ->where('level >', 17)
        ->get()
        ->result_array();

    if (!empty($eligible_users)) {
        foreach ($eligible_users as $user) {
            $user_id = $user['user_id'];

            // ⚡ For example: Count how many levels exist under this user (simulate pair count)
            $pair_count = $this->db->where('parent_id', $user_id)->count_all_results('dream_tree');

            // 🧮 Find best matching slab
            $this->db->select('*')
                ->from('pair_income_slabs')
                ->where('pair_count <=', $pair_count)
                ->order_by('pair_count', 'DESC')
                ->limit(1);
            $slab = $this->db->get()->row_array();

            if ($slab) {
                // ✅ Apply income logic here
                $income = $slab['income_amount'];

                // 💾 Example: log to `pair_income_logs` (create if needed)
                $this->db->insert('pair_income_history', [
                    'user_id' => $user_id,
                    'pair_count' => $pair_count,
                    'income_amount' => $income,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // TODO: Optionally add wallet update here
            }
        }
    }

    echo "Pair income checked for users with level > 17.\n";
}

    
    
    
}
