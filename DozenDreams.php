<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class DozenDreams extends CI_Controller {

    protected $user_id;
    protected $user_role;
    protected $user_name;
    protected $user_email;

    // Optional: role flags
    protected $is_superadmin;
    protected $is_admin;
    protected $is_commando;
    protected $is_user;
    protected $is_developer;
    protected $is_tester;

    public function __construct() {
        parent::__construct();

        // Get current method (e.g. login, dashboard, etc.)
        $method = $this->router->fetch_method();

        // Define public methods that do not require login
        $public_methods = ['login', 'logout','register'];

        // Only check login/session for non-public methods
        if (!in_array($method, $public_methods)) {
            if (!$this->session->userdata('logged_in')) {
                redirect('DozenDreams/login');
            }

            // Load session data only if logged in
            $this->user_id     = $this->session->userdata('user_id');
            $this->user_name   = $this->session->userdata('user_name');
            $this->user_email  = $this->session->userdata('user_email');
            $this->user_role   = $this->session->userdata('user_role');

            // Load role flags
            $this->is_superadmin = $this->session->userdata('is_superadmin');
            $this->is_admin      = $this->session->userdata('is_admin');
            $this->is_commando   = $this->session->userdata('is_commando');
            $this->is_user       = $this->session->userdata('is_user');
            $this->is_developer  = $this->session->userdata('is_developer');
            $this->is_tester     = $this->session->userdata('is_tester');

            // ✅ Restrict access to only allowed roles (uncomment below as needed)
            $this->allowRoles([
                'superadmin', 
                'admin', 
                'commando', 
                'developer', 
                'tester',
                'user'
            ]);

            // 🚫 Block specific roles if needed
            // $this->blockRoles(['tester']);
        }
    }




    // ✅ Allow only specific roles
    private function allowRoles(array $roles) {
        if (!in_array($this->user_role, $roles)) {
            show_error('Access Denied: You do not have permission to access this page.', 403);
            exit;
        }
    }

    // 🚫 Block specific roles
    private function blockRoles(array $roles) {
        if (in_array($this->user_role, $roles)) {
            show_error('Access Denied: Your role is restricted from this page.', 403);
            exit;
        }
    }

    public function codeMapOverview() {

        // if (!$this->session->userdata('is_superadmin')) {
        //     show_error('Access denied. Super Admins only.', 403);
        //     return;
        // }
        $controllers_path = APPPATH . 'controllers/';
        $models_path = APPPATH . 'models/';

        $controller_data = $this->scanPHPFiles($controllers_path, 'controller');
        $model_data = $this->scanPHPFiles($models_path, 'model');

        $data['controllers'] = $controller_data;
        $data['models'] = $model_data;

        $this->load->view('dozendreams/code_map', $data);
    }

    private function scanPHPFiles($path, $type = 'controller') {
        $results = [];
        $files = glob($path . '*.php');

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $filename = basename($file);
            $functions = [];
            $views = [];
            $tables = [];

            // Get all functions
            preg_match_all('/function\s+([a-zA-Z0-9_]+)\s*\(/', $content, $func_matches);
            $functions = $func_matches[1];

            // Get views loaded (for controllers)
            if ($type === 'controller') {
                preg_match_all('/\$this->load->view\s*\(\s*[\'"](.+?)[\'"]\s*,/', $content, $view_matches);
                $views = $view_matches[1];
            }

            // Get tables used (for models)
            if ($type === 'model') {
                preg_match_all('/\$this->db->(?:get|insert|update|delete|from)\s*\(\s*[\'"](.+?)[\'"]\s*\)/', $content, $table_matches);
                $tables = array_unique($table_matches[1]);
            }

            $results[] = [
                'file' => $filename,
                'functions' => $functions,
                'views' => $views,
                'tables' => $tables
            ];
        }

        return $results;
    }




    public function login() {
        if ($this->input->post()) {
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('error', 'Invalid email format.');
                redirect('DozenDreams/login');
            }
    
            $user = $this->dozen->login($email, $password);
    
            if ($user) {
                if (isset($user->status) && $user->status == '1') {
                    $sessionData = [
                        'logged_in'   => TRUE,
                        'user_id'     => $user->id,
                        'user_name'   => $user->name,
                        'user_email'  => $user->email,
                        'user_role'   => $user->role
                    ];
    
                    // Role-specific flags
                    switch ($user->role) {
                        case 'superadmin':
                            $sessionData['is_superadmin'] = TRUE;
                            break;
                        case 'admin':
                            $sessionData['is_admin'] = TRUE;
                            break;
                        case 'commando':
                            $sessionData['is_commando'] = TRUE;
                            break;
                        case 'user':
                            $sessionData['is_user'] = TRUE;
                            break;
                        case 'developer':
                            $sessionData['is_developer'] = TRUE;
                            break;
                        case 'tester':
                            $sessionData['is_tester'] = TRUE;
                            break;
                        default:
                            $this->session->set_flashdata('error', 'Unauthorized role.');
                            redirect('DozenDreams/login');
                    }
    
                    $this->session->set_userdata($sessionData);
                    redirect('DozenDreams/dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Your account is inactive.');
                    redirect('DozenDreams/login');
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid credentials.');
                redirect('DozenDreams/login');
            }
        }
        $this->load->view('dozendreams/login');
    }
        
    public function register() {
        if ($this->input->post()) {
            $name     = $this->input->post('name', TRUE);
            $email    = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);
            $referral = $this->input->post('referral_code', TRUE);
    
            // 1. Input Validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 4) {
                $this->session->set_flashdata('error', 'Invalid email or password too short.');
                redirect('DozenDreams/register');
            }
    
            // 2. Check if email exists
            if ($this->dozen->email_exists($email)) {
                $this->session->set_flashdata('error', 'Email already registered.');
                redirect('DozenDreams/register');
            }
    
            // 3. Fetch role and status IDs
            $roleId   = $this->dozen->getMasterIdByTypeAndCode('Role', 'user');
            $statusId = $this->dozen->getMasterIdByTypeAndCode('Status', '1'); // Active
    
            if (!$roleId || !$statusId) {
                $this->session->set_flashdata('error', 'Master values missing. Contact admin.');
                redirect('DozenDreams/register');
            }
    
            // 4. Validate Referral Code (if provided)
            $referrer_id = null;
            $referred_by = null;

            if (!empty($referral)) {
                $referrer = $this->dozen->getUserByReferral($referral);
                if ($referrer && isset($referrer['id'])) {
                    $referrer_id = $referrer['id'];
                    $referred_by = $referrer_id;
                } else {
                    $this->session->set_flashdata('error', 'Invalid referral code. Please check and try again.');
                    redirect('DozenDreams/register');
                }
            }
    
            // 5. Register User
            $data = [
                'name'        => $name,
                'email'       => $email,
                'password'    => password_hash($password, PASSWORD_BCRYPT),
                'role_id'     => $roleId,
                'status_id'   => $statusId,
                'parent_id'   => null,
                'referred_by' => $referred_by ?? rand(1, 17)
            ];
    
            $user_id = $this->dozen->register($data);
    
            if ($user_id) {
                // 🔄 6. Generate Unique Referral Code
                do {
                    $generated_referral_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 12));
                    $referral_exists = $this->db->where('referral_code', $generated_referral_code)
                                                ->get('user_details')
                                                ->num_rows();
                } while ($referral_exists > 0);

                // 🔄 7. Generate Unique User Identifier (12-char mix with DZNDR + numbers)
                do {
                    $shuffled_string = str_shuffle('DZNDR' . mt_rand(100000, 999999)); // mix string
                    $generated_unique_id = substr($shuffled_string, 0, 12);
                    $unique_id_exists = $this->db->where('unique_id', $generated_unique_id)
                                                ->get('user_details')
                                                ->num_rows();
                } while ($unique_id_exists > 0);

                // ✅ 8. Insert User Details
                $user_details_data = [
                    'user_id'       => $user_id,
                    'fullname'      => $name,
                    'referral_code' => $generated_referral_code,
                    'unique_id'     => $generated_unique_id,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s')
                ];

                $details_id = $this->dozen->insertUserDetails($user_details_data);

                if ($details_id) {
                                                    
                    $this->dozen->createDefaultWalletsForUser($user_id);

                    // 9. Add to Dream Tree
                    $this->dozen->insertUserToTree($user_id, $referrer_id);

                    // 10. Handle Referral Income Levels
                    $this->dozen->distributeReferralIncome($user_id, $referrer_id);

                    // 11. Apply Referral Bonus Slab
                    $this->dozen->applyReferralBonusSlab($referrer_id);
                }

                $this->session->set_flashdata('success', 'Registered successfully! Please login.');
                redirect('DozenDreams/login');
            } else {
                $this->session->set_flashdata('error', 'Registration failed.');
                redirect('DozenDreams/register');
            }
        }
    
        $this->load->view('dozendreams/register');
    }
      
    public function logout() {
        $this->session->unset_userdata([
            'logged_in', 'user_id', 'user_name', 'user_email', 'user_role',
            'is_superadmin', 'is_admin', 'is_commando', 'is_user', 'is_developer', 'is_tester'
        ]);
        $this->session->sess_destroy();
        redirect('DozenDreams/login');
    } 

    public function dashboard() {
        if (!$this->session->userdata('user_id')) {
            redirect('DozenDreams/login');
        }
    
        $user_id   = $this->session->userdata('user_id');
        $user_role = $this->session->userdata('user_role');
    
        $data['role']          = $user_role;
        $data['total_matches'] = $this->dozen->getTotalMatches();
    
        // ✅ Only for non-users
        if ($user_role !== 'user') {
            $data['total_players']       = $this->dozen->getTotalPlayers();
            $data['total_venues']        = $this->dozen->getTotalVenues();
            $data['pair_income']         = $this->dozen->getPairIncome();
            $data['referral_direct']     = $this->dozen->getReferralIncome('direct');
            $data['referral_indirect']   = $this->dozen->getReferralIncome('indirect');
            $data['total_users']         = $this->dozen->getTotalUsers();
            $data['total_investments']   = $this->dozen->getTotalInvestments();
            $data['total_gain']          = $this->dozen->getTotalGain();
        }
    
        // ✅ Only for users
        if ($user_role === 'user') {
            $data['total_points'] = $this->dozen->getTotalPoints($user_id);
            $data['wallets']      = $this->dozen->getWalletsByUser($user_id);
        } else {
            $data['total_points'] = null;
            $data['wallets']      = [];
        }
    
        // ✅ Pending Crons only for commando and tester
        $data['pending_crons'] = (in_array($user_role, ['commando', 'tester']))
            ? $this->dozen->getTodayPendingCronsCount()
            : null;
    
        // ✅ Load views
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar', $data);
        $this->load->view('dozendreams/dashboard', $data);
        $this->load->view('dozendreams/layout/footer');
    }      
	



	public function viewAllReferrals() {

		if ($this->user_role == 'user') redirect('DozenDreams/dashboard');
	
		$this->load->model('dozen');
		$data['referrals'] = $this->dozen->getAllReferralDetails();
		$data['role'] = $this->user_role;
	
		$this->load->view('dozendreams/layout/header');
		$this->load->view('dozendreams/layout/sidebar', $data);
		$this->load->view('dozendreams/view_all_referrals', $data);
		$this->load->view('dozendreams/layout/footer');
	}

    public function myReferrals() {
    
        // Only user can see their own referrals
        if (!$this->user_id || $this->user_role !== 'user') redirect('DozenDreams/dashboard');
    
        $data['referrals'] = $this->dozen->getReferralsByUser($this->user_id);
        $data['role'] = $this->user_role;
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar', $data);
        $this->load->view('dozendreams/my_referrals', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function userProfile()
    {
        $user_id = $this->session->userdata('user_id');
        $data['profile'] = $this->dozen->getUserProfile($user_id);
        $this->load->view('dozendreams/user_profile', $data);
    }

    public function userProfileEdit()
    {
        $user_id = $this->session->userdata('user_id');
        $data['profile'] = $this->dozen->getUserProfile($user_id);

        if ($this->input->post()) {
            $updateData = [
                'first_name'     => $this->input->post('first_name', true),
                'last_name'      => $this->input->post('last_name', true),
                'mobile_number'  => $this->input->post('mobile_number', true),
                'aadhaar_number' => $this->input->post('aadhaar_number', true),
                'pan_number'     => $this->input->post('pan_number', true),
                'address'        => $this->input->post('address', true),
            ];

            $this->dozen->updateUserDetails($user_id, $updateData);
            $this->session->set_flashdata('success', 'Profile updated successfully.');
            redirect('profile');
        }

        $this->load->view('dozendreams/user_profile_edit', $data);
    }

    public function usersList() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
    
        $data['users'] = $this->dozen->getAllUsersWithDetails();
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/users_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }





    // Load Master Add/Edit Form
    public function addMaster() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
    
        $data['master_types'] = $this->dozen->getMasterTypes();
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/master_add_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    }    

    // Load Edit Form
    public function editMaster($id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
    
        $data['master'] = $this->dozen->getMasterById($id);
        $data['master_types'] = $this->dozen->getMasterTypes();
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/master_add_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    } 

    // Insert New Record
    public function insertMaster() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
    
        $this->load->library('form_validation');
        $this->form_validation->set_rules('master_type_id', 'Master Type', 'required|integer');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]');
        $this->form_validation->set_rules('short_code', 'Short Code', 'trim');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
    
        if ($this->form_validation->run() === FALSE) {
            $data['master_types'] = $this->dozen->getMasterTypes();
            $this->load->view('dozendreams/layout/header');
            $this->load->view('dozendreams/layout/sidebar');
            $this->load->view('dozendreams/master_add_edit', $data);
            $this->load->view('dozendreams/layout/footer');
        } else {
            $data = [
                'master_type_id' => $this->input->post('master_type_id', TRUE),
                'name'           => $this->input->post('name', TRUE),
                'short_code'     => $this->input->post('short_code', TRUE),
                'status'         => $this->input->post('status', TRUE),
            ];
    
            $this->dozen->insertMaster($data);
            $this->session->set_flashdata('success', 'Master added successfully.');
            redirect('DozenDreams/mastersList');
        }
    }    

    // Update Existing Record
    public function updateMaster($id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
    
        $this->load->library('form_validation');
        $this->form_validation->set_rules('master_type_id', 'Master Type', 'required|integer');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]');
        $this->form_validation->set_rules('short_code', 'Short Code', 'trim');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
    
        if ($this->form_validation->run() === FALSE) {
            $data['master'] = $this->dozen->getMasterById($id);
            $data['master_types'] = $this->dozen->getMasterTypes();
            $this->load->view('dozendreams/layout/header');
            $this->load->view('dozendreams/layout/sidebar');
            $this->load->view('dozendreams/master_add_edit', $data);
            $this->load->view('dozendreams/layout/footer');
        } else {
            $data = [
                'master_type_id' => $this->input->post('master_type_id', TRUE),
                'name'           => $this->input->post('name', TRUE),
                'short_code'     => $this->input->post('short_code', TRUE),
                'status'         => $this->input->post('status', TRUE),
            ];
    
            $this->dozen->updateMaster($id, $data);
            $this->session->set_flashdata('success', 'Master updated successfully.');
            redirect('DozenDreams/mastersList');
        }
    }
    
    public function mastersList() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
    
        $data['masters'] = $this->dozen->getAllMasters();
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/master_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }











    
    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }

        $scheduled_matches = $this->dozen->getScheduledMatches();
        $firstMatchId = !empty($scheduled_matches) ? $scheduled_matches[0]['match_id'] : '';

        $players = $this->dozen->getPlayersByMatchId($firstMatchId);

        $data = [
            'players' => $players,
            'scheduled_matches' => $scheduled_matches,
            'selectedCaptain' => '',
            'selectedViceCaptain' => '',
            'selectedPlayers' => [],
            'teamLimit' => 1,
            'pitchType' => '',
            'opponentTeamId' => '',
            'weatherCondition' => '',
            'selectedMatch' => $firstMatchId,
            'role_map' => $this->dozen->getRoleMap(),
            'weather_conditions' => ['Clear', 'Cloudy', 'Rainy', 'Humid'],
            'pitch_types' => ['Batting', 'Bowling', 'Balanced'],
            'opponent_teams' => $this->dozen->getOpponentTeams()
        ];
        

        $this->load->view('dozendreams/player_list', $data);
    }

    public function getPlayersForMatch($match_id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }

        $players = $this->dozen->getPlayersByMatchId($match_id);
        $formatted = [];
    
        foreach ($players as $p) {
            $formatted[] = [
                'player_id' => $p['player_id'],          // ✅ Use consistent keys
                'player_name' => $p['player_name'],      // ✅ Same as initial page load
                'player_role' => $p['player_role'],
                'credit_points' => $p['credit_points']
            ];
        }
    
        header('Content-Type: application/json');
        echo json_encode($formatted);
        exit;
    }
 
    public function generate() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
        $selectedPlayers      = $this->input->post('players') ?? [];
        $teamLimit            = intval($this->input->post('limit')) ?? 1;
        $selectedCaptain      = $this->input->post('captain') ?? null;
        $selectedViceCaptain  = $this->input->post('vice_captain') ?? null;
        $matchId              = $this->input->post('match_id');
        $usePitchType         = $this->input->post('pitch_type') ?? 'No';         // "Yes" or "No"
        $useWeatherCondition  = $this->input->post('weather') ?? 'No';            // "Yes" or "No"
        $useOpponentTeam      = $this->input->post('opponent_team') ?? 'No';      // ID or "No"
        $useKnockoutStage     = $this->input->post('knockout_stage') ?? 'No';     // "Yes" or "No"
        $teamAPlayerCount = intval($this->input->post('team_a_count') ?? 1);
        $teamBPlayerCount = intval($this->input->post('team_b_count') ?? 1);


        if (!is_array($selectedPlayers)) $selectedPlayers = [];
    
        // ✅ Ensure captain & VC are in the selected list
        if ($selectedCaptain && !in_array($selectedCaptain, $selectedPlayers)) {
            $selectedPlayers[] = $selectedCaptain;
        }
        if ($selectedViceCaptain && !in_array($selectedViceCaptain, $selectedPlayers)) {
            $selectedPlayers[] = $selectedViceCaptain;
        }
    
        // ✅ Max allowed players based on captain/VC logic
        $maxAllowed = 11;
        if ($selectedCaptain && $selectedViceCaptain) {
            $maxAllowed = 9;
        } elseif ($selectedCaptain || $selectedViceCaptain) {
            $maxAllowed = 10;
        }
    
        if (count($selectedPlayers) > $maxAllowed) {
            echo "Invalid selection. Max allowed is $maxAllowed based on Captain/VC selection.";
            return;
        }
    
        $allPlayers = $this->dozen->getPlayersWithFullDetailsByMatch($matchId);
    
        $formScoreCache = [];
        $formThreshold = 10;
        $filteredPlayers = [];
    
        foreach ($allPlayers as $player) {
            $pid = $player['player_id'];
            if (!isset($formScoreCache[$pid])) {
                $formScoreCache[$pid] = $this->calculatePlayerForm($pid);
            }
    
            $formScore = $formScoreCache[$pid];
            $isFit = $this->dozen->isPlayerFit($pid);
            if (!$isFit) continue;
    
            if ($formScore >= $formThreshold) {
                $player['form_score'] = $formScore;
                $filteredPlayers[] = $player;
            }
        }
    
        $filteredPlayerIds = array_column($filteredPlayers, 'player_id');
    
        // ✅ Build map of valid players
        $playerMap = [];
        foreach ($filteredPlayers as $player) {
            $playerMap[$player['player_id']] = (object)$player;
        }
    
        // ✅ Check that all selected players are valid
        foreach ($selectedPlayers as $pid) {
            if (!in_array($pid, $filteredPlayerIds)) {
                $playerName = isset($playerMap[$pid]) ? $playerMap[$pid]->name : "Unknown (ID: $pid)";
                echo "Selected player <strong>$playerName</strong> does not meet the form or fitness criteria.";
                return;
            }
        }
    
        $remainingPool = array_values(array_diff($filteredPlayerIds, $selectedPlayers));
        $playersNeeded = 11 - count($selectedPlayers);
        $extraCombinations = $this->generateCombinations($remainingPool, $playersNeeded);
    
        $finalTeams = [];
        $maxPlayersFromOneTeam = 7;
    
        foreach ($extraCombinations as $extras) {
            $teamIds = array_merge($selectedPlayers, $extras);
            $teamPlayers = array_map(fn($id) => $playerMap[$id] ?? null, $teamIds);
    
            if (in_array(null, $teamPlayers, true)) continue;
    
            $roleCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $teamCount = [];
            $totalPoints = 0;
            $totalFormScore = 0;
    
            foreach ($teamPlayers as $player) {
                $roleCounts[$player->role_id]++;
                $teamCount[$player->team_id] = ($teamCount[$player->team_id] ?? 0) + 1;
                $totalPoints += $player->credit_points;
                $totalFormScore += $player->form_score;
            }
    
            if ($totalPoints > 100) continue;
            if (in_array(0, $roleCounts)) continue;
            if (max($teamCount) > $maxPlayersFromOneTeam) continue;
    
            // ✅ Enforce user-selected Captain & VC if present
            $hasCaptain = $selectedCaptain && in_array($selectedCaptain, $teamIds);
            $hasViceCaptain = $selectedViceCaptain && in_array($selectedViceCaptain, $teamIds);
    
            if ($hasCaptain && $hasViceCaptain && $selectedCaptain != $selectedViceCaptain) {
                $finalTeams[] = [
                    'players' => $teamPlayers,
                    'captain_id' => $selectedCaptain,
                    'vice_captain_id' => $selectedViceCaptain,
                    'captain' => $playerMap[$selectedCaptain]->name ?? 'Unknown',
                    'vice_captain' => $playerMap[$selectedViceCaptain]->name ?? 'Unknown',
                    'total_points' => $totalPoints,
                    'total_form_score' => $totalFormScore,
                ];
            } else {
                // Generate all captain/VC combos
                for ($i = 0; $i < count($teamPlayers); $i++) {
                    for ($j = 0; $j < count($teamPlayers); $j++) {
                        if ($i == $j) continue;
                        $finalTeams[] = [
                            'players' => $teamPlayers,
                            'captain_id' => $teamPlayers[$i]->player_id,
                            'vice_captain_id' => $teamPlayers[$j]->player_id,
                            'captain' => $teamPlayers[$i]->name,
                            'vice_captain' => $teamPlayers[$j]->name,
                            'total_points' => $totalPoints,
                            'total_form_score' => $totalFormScore,
                        ];
                        if (count($finalTeams) >= 5000) break 3;
                    }
                }
            }
        }
    
        usort($finalTeams, fn($a, $b) =>
            $b['total_form_score'] <=> $a['total_form_score'] ?: $b['total_points'] <=> $a['total_points']
        );
    
        $finalTeams = array_slice($finalTeams, 0, $teamLimit);
    
        $data['combinations'] = $finalTeams;
        $data['selectedCaptain'] = $selectedCaptain;
        $data['selectedViceCaptain'] = $selectedViceCaptain;
        $data['teamLimit'] = $teamLimit;

        if ($this->dozen->saveGeneratedTeams($this->user_id, $matchId, $finalTeams)) {
            $this->load->view('dozendreams/combinations', $data);
        } else {
            echo "Failed to save generated teams.";
        }
        
    
    }
    
    private function generateCombinations($arr, $k) {

        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }

        $result = [];
        $this->combineRecursive($arr, $k, 0, [], $result);
        return $result;
    }
    
    private function combineRecursive($arr, $k, $index, $current, &$result) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }

        if (count($current) == $k) {
            $result[] = $current;
            return;
        }
    
        for ($i = $index; $i < count($arr); $i++) {
            $newCurrent = $current;
            $newCurrent[] = $arr[$i];
            $this->combineRecursive($arr, $k, $i + 1, $newCurrent, $result);
    
            if (count($result) > 10000) break;
        }
    }
    
    public function calculatePlayerForm($playerId, $pitchType = null, $opponentTeamId = null) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }

        $stats = $this->dozen->getPlayerStatsById($playerId);

        if (empty($stats)) return 0;
    
        $player = $this->dozen->getPlayerById($playerId);
        if (!empty($player->fitness_status)) {
            return 0;
        }

    
        $matchesPlayed = (int) $stats['matches'];
        $totalRuns = (int) $stats['runs'];
        $totalWickets = (int) $stats['wickets'];
        $totalStrikeRate = (float) $stats['strike_rate'];
        $totalEconomy = (float) $stats['economy_rate'] ?? 0;
        $oversBowled = (float) $stats['overs_bowled'] ?? 0.1;
        $runsConceded = (int) $stats['runs_given'];
    
        $impactScore = ($totalRuns * 0.5) + ($totalWickets * 20) - ($totalEconomy * 2);
    
        $oppFactor = 1;
        if ($opponentTeamId) {
            $matchup = $this->dozen->getPlayerOpponentStats($playerId, $opponentTeamId);
            if (!empty($matchup)) {
                $avgRunsVsOpp = $matchup['avg_runs'] ?? 0;
                $avgWicketsVsOpp = $matchup['avg_wickets'] ?? 0;
                $oppFactor += ($avgRunsVsOpp * 0.01) + ($avgWicketsVsOpp * 0.1);
            }
        }
    
        $pitchMultiplier = 1;
        if ($pitchType && !empty($player->special_ability)) {
            $compatibleAbilities = $this->getPreferredAbilitiesForPitch($pitchType);
            if (in_array($player->special_ability, $compatibleAbilities)) {
                $pitchMultiplier = 1.2;
            } else {
                $pitchMultiplier = 0.85;
            }
        }
    
        $avgRuns = $totalRuns / max($matchesPlayed, 1);
        $avgWickets = $totalWickets / max($matchesPlayed, 1);
        $avgStrikeRate = $totalStrikeRate;
        $avgEconomy = $totalEconomy;
        $avgImpactScore = $impactScore / max($matchesPlayed, 1);
    
        $formScore = (
            ($avgRuns * 0.4) +
            ($avgWickets * 15) +
            ($avgStrikeRate * 0.1) +
            ($avgImpactScore * 0.5) -
            ($avgEconomy * 1)
        ) * $pitchMultiplier * $oppFactor;

        return round($formScore, 2);
    }
    
    private function getPreferredAbilitiesForPitch($pitchType) {
        $map = [
            'Spin-friendly' => ['Spinner', 'All-rounder'],
            'Pace-friendly' => ['Pace Bowler', 'Fast Bowler'],
            'Good for Batting' => ['Batsman', 'Aggressive Batsman'],
            'Difficult for Batting' => ['Bowler', 'All-rounder'],
            'High Bounce' => ['Fast Bowler', 'Pace Bowler'],
            'Low Bounce' => ['Spinner'],
            'Good for Chasing' => ['Finisher', 'Aggressive Batsman'],
            'Favors Swing' => ['Swing Bowler', 'Pace Bowler'],
        ];
    
        return $map[$pitchType] ?? [];
    }

    public function exportGeneratedTeams()
    {
        // Get the JSON data sent via POST request
        $jsonData = $this->input->post('json_data');
    
        // Decode the JSON string into a PHP associative array
        $teams = json_decode($jsonData, true);
    
        // Set HTTP headers to tell the browser this is an Excel file download
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=teams.xls");
    
        // Start building an HTML table - Excel can open this as a spreadsheet
        echo "<table border='1'>";
        echo "<tr><th>Team #</th><th>Captain</th><th>Vice Captain</th><th>Player</th><th>Role</th><th>Points</th></tr>";
    
        // Map role IDs to descriptive role names for display
        $roleMap = [
            1 => 'Batsman',
            2 => 'Bowler',
            3 => 'All-Rounder',
            4 => 'Wicket-Keeper'
        ];
    
        // Loop through each team (with index $i) and their players
        foreach ($teams as $i => $team) {
            foreach ($team['players'] as $player) {
                // Output a row for each player with team info and player details
                echo "<tr>
                    <td>Team " . ($i + 1) . "</td>  <!-- Team number -->
                    <td>{$team['captain']}</td>    <!-- Captain name -->
                    <td>{$team['vice_captain']}</td> <!-- Vice Captain name -->
                    <td>{$player['name']}</td>     <!-- Player name -->
                    <td>{$roleMap[$player['role_id']]}</td> <!-- Role description -->
                    <td>{$player['credit_points']}</td> <!-- Player credit points -->
                </tr>";
            }
        }
    
        // Close the HTML table
        echo "</table>";
    } 





    public function pointsList() {
        $data['points'] = $this->dozen->getAllPoints(); 
        $roles = [];
        foreach ($this->dozen->getPlayerRoles() as $role) {
            $roles[$role['id']] = $role['role_name'];
        }
        $data['roles'] = $roles;
        $data['title'] = "Dream Points List";
    
        $this->load->view('dozendreams/layout/header', $data);
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/points_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    
    public function pointsAdd() {
        $data['title'] = "Add Dream11 Point Rule";
    
        // Roles from roles table
        $roles = [];
        foreach ($this->dozen->getPlayerRoles() as $role) {
            $roles[$role['id']] = $role['role_name']; // changed to 'role_name'
        }
        $data['roles'] = $roles;
    
        // Match types from match_types table
        $match_types = [];
        foreach ($this->dozen->getMatchTypes() as $type) {
            $match_types[$type['id']] = $type['type_name'];
        }
        $data['match_types'] = $match_types;
    
        if ($_POST) {
            $this->form_validation->set_rules('action', 'Action', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('role_id', 'Role', 'required|in_list[' . implode(',', array_keys($roles)) . ']');
            $this->form_validation->set_rules('type_id', 'Match Type', 'required|in_list[' . implode(',', array_keys($match_types)) . ']');
            $this->form_validation->set_rules('points', 'Points', 'required|numeric');
    
            if ($this->form_validation->run() === TRUE) {
                $insertData = [
                    'action'   => $this->input->post('action'),
                    'role_id'  => $this->input->post('role_id'),
                    'type_id'  => $this->input->post('type_id'), // added
                    'points'   => $this->input->post('points')
                ];
                $this->dozen->insertPoint($insertData);
                $this->session->set_flashdata('success', 'Point rule added successfully.');
                redirect('DozenDreams/pointsList');
            }
        }
    
        $this->load->view('dozendreams/layout/header', $data);
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/points_add', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    
    public function pointsEdit($id) {
        $data['point'] = $this->dozen->getPoint($id);
        if (!$data['point']) show_404();
    
        $data['title'] = "Edit Dream11 Point Rule";
    
        // Roles from roles table
        $roles = [];
        foreach ($this->dozen->getPlayerRoles() as $role) {
            $roles[$role['id']] = $role['role_name']; // changed to 'role_name'
        }
        $data['roles'] = $roles;
    
        // Match types from match_types table
        $match_types = [];
        foreach ($this->dozen->getMatchTypes() as $type) {
            $match_types[$type['id']] = $type['type_name'];
        }
        $data['match_types'] = $match_types;
    
        if ($_POST) {
            $this->form_validation->set_rules('action', 'Action', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('role_id', 'Role', 'required|in_list[' . implode(',', array_keys($roles)) . ']');
            $this->form_validation->set_rules('type_id', 'Match Type', 'required|in_list[' . implode(',', array_keys($match_types)) . ']');
            $this->form_validation->set_rules('points', 'Points', 'required|numeric');
    
            if ($this->form_validation->run() === TRUE) {
                $updateData = [
                    'action'   => $this->input->post('action'),
                    'role_id'  => $this->input->post('role_id'),
                    'type_id'  => $this->input->post('type_id'), // added
                    'points'   => $this->input->post('points')
                ];
                $this->dozen->updatePoint($id, $updateData);
                $this->session->set_flashdata('success', 'Point rule updated successfully.');
                redirect('DozenDreams/pointsList');
            }
        }
    
        $this->load->view('dozendreams/layout/header', $data);
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/points_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    




        
    public function playerStatisticsList() {
        $this->_requireLogin();
        $data['playersStats'] = $this->dozen->getAllPlayerStatisticsWithPlayerNames();
        $data['title'] = 'Player Statistics List';
    
        $this->load->view('dozendreams/layout/header', $data);
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/playerstatistics_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    
    public function playerStatisticsAdd() {
        $this->_requireLogin();
        $this->_setStatValidationRules();
    
        if ($this->form_validation->run() === TRUE) {
            $insertData = $this->input->post(null, true);
    
            $dataToInsert = [
                'player_id'  => $insertData['player_id'],
                'runs'       => $insertData['runs'],
                'wickets'    => $insertData['wickets'],
                'catches'    => $insertData['catches'],
                'status_id'  => $insertData['status_id'],
            ];
    
            $this->dozen->insertPlayerStatistics($dataToInsert);
            $this->session->set_flashdata('success', 'Player statistics added successfully.');
            redirect('DozenDreams/playerStatisticsList');
        }

        $data['players'] = $this->dozen->getAllPlayers();
        $data['statuses'] = $this->dozen->getAllStatuses(); // from masters where master_type_id = 2
        $data['title'] = 'Add Player Statistics';
    
        $this->load->view('dozendreams/layout/header', $data);
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/playerstatistics_add', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    
    public function playerStatisticsEdit($id) {
        $this->_requireLogin();
        
        $data['stat'] = $this->dozen->getPlayerStatisticsById($id);
        if (!$data['stat']) show_404();
    
        $this->_setStatValidationRules();
    
        if ($this->form_validation->run() === TRUE) {
            $updateData = $this->input->post(null, true);
    
            $dataToUpdate = [
                'player_id'     => $updateData['player_id'],
                'runs'          => $updateData['runs'],
                'balls_faced'   => $updateData['balls_faced'],
                'fours'         => $updateData['fours'],
                'sixes'         => $updateData['sixes'],
                'wickets'       => $updateData['wickets'],
                'overs_bowled'  => $updateData['overs_bowled'],
                'runs_conceded' => $updateData['runs_conceded'],
                'catches'       => $updateData['catches'],
                'stumpings'     => $updateData['stumpings'],
                'status_id'     => $updateData['status_id']
            ];
    
            $this->dozen->updatePlayerStatistics($id, $dataToUpdate);
            $this->session->set_flashdata('success', 'Player statistics updated successfully.');
            redirect('DozenDreams/playerStatisticsList');
        }
    
        $data['players']  = $this->dozen->getAllPlayers();
        $data['statuses'] = $this->dozen->getAllStatuses(); // assumes this returns master_type_id = 2
        $data['title']    = 'Edit Player Statistics';
    
        $this->load->view('dozendreams/layout/header', $data);
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/playerstatistics_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    } 
    
    private function _requireLogin() {
        if (!$this->session->userdata('user_id')) redirect('DozenDreams/login');
    }
    
    private function _setStatValidationRules() {
        $this->form_validation->set_rules('player_id', 'Player', 'required|numeric');
        $this->form_validation->set_rules('runs', 'Runs', 'required|numeric');
        $this->form_validation->set_rules('wickets', 'Wickets', 'required|numeric');
        $this->form_validation->set_rules('catches', 'Catches', 'required|numeric');
        $this->form_validation->set_rules('status_id', 'Status', 'required|in_list[6,7]');
    }

    public function playerStatisticsExport() {
        if (!$this->session->userdata('user_id')) redirect('DozenDreams/login');

        $this->load->library('spreadsheet');
        $playerStats = $this->dozen->getAllPlayerStatisticsWithPlayerShortNames();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Player Short Name');
        $sheet->setCellValue('B1', 'Matches');
        $sheet->setCellValue('C1', 'Runs');
        $sheet->setCellValue('D1', 'Wickets');
        $sheet->setCellValue('E1', 'Catches');
        $sheet->setCellValue('F1', 'Status');

        $row = 2;
        foreach ($playerStats as $stat) {
            $sheet->setCellValue("A$row", $stat['player_short_name']);
            $sheet->setCellValue("B$row", $stat['matches']);
            $sheet->setCellValue("C$row", $stat['runs']);
            $sheet->setCellValue("D$row", $stat['wickets']);
            $sheet->setCellValue("E$row", $stat['catches']);
            $sheet->setCellValue("F$row", $stat['status']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="player_statistics.xlsx"');
        $writer->save('php://output');
    }

    public function playerStatisticsImport() {
        if (!$this->session->userdata('user_id')) redirect('DozenDreams/login');

        if (!empty($_FILES['excel_file']['name'])) {
            $this->load->library('spreadsheet');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                $player = $this->dozen->getPlayerStatisticsByShortName($row[0]);

                if ($player) {
                    $insert = [
                        'player_id' => $player['id'],
                        'matches' => $row[1],
                        'runs' => $row[2],
                        'wickets' => $row[3],
                        'catches' => $row[4],
                        'status' => $row[5]
                    ];
                    $this->dozen->insertPlayerStatistics($insert);
                }
            }
        }

        $this->session->set_flashdata('success', 'Import completed.');
        redirect('DozenDreams/playerStatisticsList');
    }






    
    // 🔹 Display the list of schedules
    public function scheduleList() {
        $data['schedules'] = $this->dozen->getAllSchedulesWithNames();

        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/schedule_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    // 🔹 Load Add Schedule Form
    public function addSchedule() {
        $data['statuses'] = $this->dozen->getAllScheduleStatuses();
        $data['tournaments'] = $this->dozen->getAllTournaments();
        $data['teams'] = $this->dozen->getAllTeams();
        $data['venues'] = $this->dozen->getAllVenues();
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/schedule_add', $data);
        $this->load->view('dozendreams/layout/footer');
    }    

    // 🔹 Insert Schedule with Validation
    public function insertSchedule() {
        $this->form_validation->set_rules('tournament_id', 'Tournament', 'required');
        $this->form_validation->set_rules('team_a_id', 'Team A', 'required');
        $this->form_validation->set_rules('team_b_id', 'Team B', 'required|callback_team_check');
        $this->form_validation->set_rules('match_date', 'Match Date', 'required');
        $this->form_validation->set_rules('venue_id', 'Venue', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
    
        if ($this->form_validation->run() === FALSE) {
            $this->addSchedule();
        } else {
            $data = [
                'tournament_id' => $this->input->post('tournament_id'),
                'team_a_id'     => $this->input->post('team_a_id'),
                'team_b_id'     => $this->input->post('team_b_id'),
                'match_date'    => $this->input->post('match_date'),
                'venue_id'      => $this->input->post('venue_id'),
                'status_id'     => $this->input->post('status')
            ];
            $this->dozen->insertSchedule($data);
            redirect('DozenDreams/scheduleList');
        }
    }

    // 🔹 Callback: Ensure Team A and B are not the same
    public function team_check($team_b_id) {
        if ($this->input->post('team_a_id') == $team_b_id) {
            $this->form_validation->set_message('team_check', 'Team A and Team B must be different.');
            return FALSE;
        }
        return TRUE;
    }

    // 🔹 Load Edit Schedule Form
    public function editSchedule($id) {
        $data['statuses'] = $this->dozen->getAllScheduleStatuses();
        $data['schedule'] = $this->dozen->getScheduleById($id);  // Single schedule row with values
        $data['tournaments'] = $this->dozen->getAllTournaments();
        $data['teams'] = $this->dozen->getAllTeams();
        $data['venues'] = $this->dozen->getAllVenues();
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/schedule_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    }    

    // 🔹 Update Schedule with Validation
    public function updateSchedule($id) {
        $this->form_validation->set_rules('tournament_id', 'Tournament', 'required');
        $this->form_validation->set_rules('team_a_id', 'Team A', 'required');
        $this->form_validation->set_rules('team_b_id', 'Team B', 'required|callback_team_check');
        $this->form_validation->set_rules('match_date', 'Match Date', 'required');
        $this->form_validation->set_rules('venue_id', 'Venue', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
    
        if ($this->form_validation->run() === FALSE) {
            $this->editSchedule($id);
        } else {
            $data = [
                'tournament_id' => $this->input->post('tournament_id'),
                'team_a_id'     => $this->input->post('team_a_id'),
                'team_b_id'     => $this->input->post('team_b_id'),
                'match_date'    => $this->input->post('match_date'),
                'venue_id'      => $this->input->post('venue_id'),
                'status_id'     => $this->input->post('status')
            ];
            $this->dozen->updateSchedule($id, $data);
            redirect('DozenDreams/scheduleList');
        }
    }
    
    // Export to Excel
    public function exportSchedules() {
        $this->load->library('phpspreadsheet');

        $schedules = $this->dozen->getAllSchedulesWithShortNames();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tournament');
        $sheet->setCellValue('B1', 'Team A');
        $sheet->setCellValue('C1', 'Team B');
        $sheet->setCellValue('D1', 'Date');
        $sheet->setCellValue('E1', 'Venue');
        $sheet->setCellValue('F1', 'Status');

        $rowIndex = 2;
        foreach ($schedules as $s) {
            $sheet->setCellValue("A$rowIndex", $s['tournament_short_name']);
            $sheet->setCellValue("B$rowIndex", $s['team_a_short_name']);
            $sheet->setCellValue("C$rowIndex", $s['team_b_short_name']);
            $sheet->setCellValue("D$rowIndex", $s['match_date']);
            $sheet->setCellValue("E$rowIndex", $s['venue']);
            $sheet->setCellValue("F$rowIndex", $s['status']);
            $rowIndex++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="schedules.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    
    // Import from Excel
    public function importSchedules() {
        if (!empty($_FILES['excel_file']['name'])) {
            $this->load->library('phpspreadsheet');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                $tournament = $this->dozen->getTournamentByShortName($row[0]);
                $teamA = $this->dozen->getTeamByShortName($row[1]);
                $teamB = $this->dozen->getTeamByShortName($row[2]);

                if ($tournament && $teamA && $teamB) {
                    $insertData = [
                        'tournament_id' => $tournament['id'],
                        'team_a_id' => $teamA['id'],
                        'team_b_id' => $teamB['id'],
                        'match_date' => $row[3],
                        'venue' => $row[4],
                        'status' => $row[5]
                    ];
                    $this->dozen->insertSchedule($insertData);
                }
            }
        }
        redirect('DozenDreams/scheduleList');
    }






        
    // Display all teams
    public function teamList() {
        $data['teams'] = $this->dozen->getAllTeams(); // fetch all teams only

        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/team_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    // Load form to add a new team
    public function addTeams() {
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/team_add_edit'); // no tournaments passed
        $this->load->view('dozendreams/layout/footer');
    }

    // Insert new team (simplified)
    public function insertTeams() {
        $formData = $this->input->post();

        if (empty($formData['name']) || empty($formData['short_name'])) {
            $this->session->set_flashdata('error', 'Team name and short name are required.');
            redirect('DozenDreams/addTeams');
        }

        $teamData = [
            'name' => trim($formData['name']),
            'short_name' => trim($formData['short_name']),
            'status' => isset($formData['status']) ? $formData['status'] : 1
        ];

        $this->dozen->insertTeam($teamData);

        $this->session->set_flashdata('success', 'Team added successfully.');
        redirect('DozenDreams/teamList');
    }

    // Load form to edit an existing team
    public function editTeams($id) {
        $data['team'] = $this->dozen->getTeamById($id);

        if (!$data['team']) {
            $this->session->set_flashdata('error', 'Team not found.');
            redirect('DozenDreams/teamList');
        }

        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/team_add_edit', $data); // only team data passed
        $this->load->view('dozendreams/layout/footer');
    }

    // Update team details
    public function updateTeams($id) {
        $formData = $this->input->post();

        if (empty($formData['name']) || empty($formData['short_name'])) {
            $this->session->set_flashdata('error', 'Team name and short name are required.');
            redirect('DozenDreams/editTeams/' . $id);
        }

        $teamData = [
            'name' => trim($formData['name']),
            'short_name' => trim($formData['short_name']),
            'status' => isset($formData['status']) ? $formData['status'] : 1
        ];

        $this->dozen->updateTeam($id, $teamData);

        $this->session->set_flashdata('success', 'Team updated successfully.');
        redirect('DozenDreams/teamList');
    }

    // Export all team data to an Excel file
    public function exportTeams() {
        $teams = $this->dozen->getAllTeams();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Team Name');
        $sheet->setCellValue('B1', 'Short Name');
        $sheet->setCellValue('C1', 'Status');

        $row = 2;
        foreach ($teams as $team) {
            $sheet->setCellValue("A$row", $team['name']);
            $sheet->setCellValue("B$row", $team['short_name']);
            $sheet->setCellValue("C$row", $team['status']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="teams.xlsx"');
        $writer->save('php://output');
    }

    // Import team data from uploaded Excel file
    public function importTeams() {
        if (!empty($_FILES['excel_file']['name'])) {
            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            for ($i = 1; $i < count($sheetData); $i++) {
                if (!empty($sheetData[$i][0]) && !empty($sheetData[$i][1])) {
                    $teamRow = [
                        'name' => trim($sheetData[$i][0]),
                        'short_name' => trim($sheetData[$i][1]),
                        'status' => $sheetData[$i][2] ?? 1
                    ];
                    $this->dozen->insertTeam($teamRow);
                }
            }

            $this->session->set_flashdata('success', 'Teams imported successfully.');
        } else {
            $this->session->set_flashdata('error', 'Please upload a valid Excel file.');
        }

        redirect('teamList');
    }






    public function tournamentList() {
        $data['tournaments'] = $this->dozen->getAllTournaments();
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/tournament_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function addTournament() {
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/tournament_add_edit');
        $this->load->view('dozendreams/layout/footer');
    }

    public function insertTournament() {
        $this->form_validation->set_rules('name', 'Tournament Name', 'required|trim');
        $this->form_validation->set_rules('short_name', 'Short Name', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->addTournament();
        } else {
            $postData = $this->input->post();
            $this->dozen->insertTournament($postData);
            $this->session->set_flashdata('success', 'Tournament added successfully.');
            redirect('DozenDreams/tournamentList');
        }
    }

    public function editTournament($id) {
        $data['tournament'] = $this->dozen->getTournamentById($id);
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/tournament_add_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function updateTournament($id) {
        $this->form_validation->set_rules('name', 'Tournament Name', 'required|trim');
        $this->form_validation->set_rules('short_name', 'Short Name', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->editTournament($id);
        } else {
            $postData = $this->input->post();
            $this->dozen->updateTournament($id, $postData);
            $this->session->set_flashdata('success', 'Tournament updated successfully.');
            redirect('DozenDreams/tournamentList');
        }
    }

    public function exportTournament() {
        $tournaments = $this->dozen->getAllTournaments();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tournament Name');
        $sheet->setCellValue('B1', 'Short Name');
        $sheet->setCellValue('C1', 'Start Date');
        $sheet->setCellValue('D1', 'End Date');
        $sheet->setCellValue('E1', 'Status');

        $row = 2;
        foreach ($tournaments as $tournament) {
            $sheet->setCellValue("A$row", $tournament['name']);
            $sheet->setCellValue("B$row", $tournament['short_name']);
            $sheet->setCellValue("C$row", $tournament['start_date']);
            $sheet->setCellValue("D$row", $tournament['end_date']);
            $sheet->setCellValue("E$row", $tournament['status']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="tournaments.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function importTournament() {
        if (!empty($_FILES['excel_file']['name'])) {
            $mime = $_FILES['excel_file']['type'];
            $allowed_mimes = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel'
            ];

            if (in_array($mime, $allowed_mimes)) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                for ($i = 1; $i < count($sheetData); $i++) {
                    $row = [
                        'name' => $sheetData[$i][0],
                        'short_name' => $sheetData[$i][1],
                        'start_date' => $sheetData[$i][2],
                        'end_date' => $sheetData[$i][3],
                        'status' => $sheetData[$i][4],
                    ];

                    if (!$this->dozen->tournamentExists($row['name'], $row['short_name'])) {
                        $this->dozen->insertTournament($row);
                    }
                }
                $this->session->set_flashdata('success', 'Tournament data imported successfully.');
            } else {
                $this->session->set_flashdata('error', 'Invalid file type.');
            }
        }
        redirect('tournamentList');
    }






    
    // List players with pagination
    public function playerList() {
        $config['base_url'] = base_url('DozenDreams/playerList');
        $config['total_rows'] = $this->dozen->countPlayers();
        $config['per_page'] = 10;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['players'] = $this->dozen->getPlayers($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('dozendreams/player_list', $data);
    }

    // Show add player form
    public function addPlayer() {
        $data['tournaments'] = $this->dozen->getAllTournaments();
        $data['teams'] = []; // Empty initially
        $data['pitch_types'] = $this->dozen->getAllPitchTypes(); 
        $this->load->view('dozendreams/player_add_edit', $data);
    }

    // Insert new player
    public function insertPlayer() {
        $post = $this->input->post();
        $this->dozen->insertPlayer([
            'name' => $post['name'],
            'role' => $post['role'],
            'team_id' => $post['team_id'],
            'special_ability' => $post['special_ability'],  // Add this line
        ]);
        redirect('playerList');
    }

    public function editPlayer($id) {
        // Validate input ID
        if (!is_numeric($id)) {
            show_404();
        }
    
        // Fetch player details
        $player = $this->dozen->getPlayerById($id);
        if (empty($player)) {
            show_404();
        }
    
        // Assign player data
        $data['player'] = $player;
    
        // Get all tournaments
        $data['tournaments'] = $this->dozen->getAllTournaments();
    
        // Ensure tournament_id is set from player record
        $tournamentId = $player['tournament_id'] ?? null;
        $data['selected_tournament'] = $tournamentId;
    
        // Get teams based on tournament_id
        $data['teams'] = !empty($tournamentId)
            ? $this->dozen->getTeamsByTournamentId($tournamentId)
            : [];
    
        // Load pitch types (for dropdown)
        $data['pitch_types'] = $this->dozen->getAllPitchTypes();
    
        // Load the add/edit player view
        $this->load->view('dozendreams/player_add_edit', $data);
    }

    // Update player
    public function updatePlayer($id) {
        $post = $this->input->post();
        $this->dozen->updatePlayer($id, [
            'name' => $post['name'],
            'role' => $post['role'],
            'team_id' => $post['team_id'],
            'special_ability' => $post['special_ability'],  // Add this line
        ]);
        redirect('playerList');
    }
    
    // Export players to Excel
    public function exportPlayers() {
        $players = $this->dozen->getPlayers(1000, 0); // get all or large limit

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Player Name');
        $sheet->setCellValue('B1', 'Role');
        $sheet->setCellValue('C1', 'Team');
        $sheet->setCellValue('D1', 'Tournament');
        $sheet->setCellValue('E1', 'Special Ability');

        $row = 2;
        foreach ($players as $p) {
            $sheet->setCellValue("A$row", $p['name']);
            $sheet->setCellValue("B$row", $p['role']);
            $sheet->setCellValue("C$row", $p['team_name']);
            $sheet->setCellValue("D$row", $p['tournament_name']);
            $sheet->setCellValue("E$row", $p['special_ability']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="players.xlsx"');
        $writer->save('php://output');
    }

    // Import players from Excel
    public function importPlayers() {
        if (!empty($_FILES['excel_file']['name'])) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Skip header row, and read all rows
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];

                // Find team_id by team name in row[2] and tournament name in row[3]
                $team = $this->db
                    ->select('teams.id')
                    ->join('tournaments', 'teams.tournament_id = tournaments.id')
                    ->where('teams.name', $row[2])
                    ->where('tournaments.name', $row[3])
                    ->get('teams')
                    ->row_array();

                if ($team) {
                    $data = [
                        'name' => $row[0],
                        'role' => $row[1],
                        'team_id' => $team['id'],
                        'special_ability' => $row[4] ?? null,
                    ];
                    $this->dozen->insertPlayer($data);
                }
            }
        }
        redirect('playerList');
    }





    public function getTeamsByTournament($tournament_id) {
        header('Content-Type: application/json');
        $teams = $this->dozen->getTeamsByTournamentId($tournament_id);
        echo json_encode($teams);
    }




    public function venueList() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }

        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }

        $config['base_url'] = base_url('DozenDreams/venueList');
        $config['total_rows'] = $this->dozen->countVenues();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['venues'] = $this->dozen->getVenues($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/venue_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function addVenue() {
        $data['pitch_types'] = $this->dozen->getAllPitchTypes();
        $data['venue'] = [];
        $data['selected_pitch_types'] = [];
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/venue_add_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function editVenue($id) {
        $data['venue'] = $this->dozen->getVenueById($id);
        $data['pitch_types'] = $this->dozen->getAllPitchTypes();
        $data['selected_pitch_types'] = $this->dozen->getVenuePitchTypes($id);
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/venue_add_edit', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function saveVenue() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('location', 'Location', 'trim');
        $this->form_validation->set_rules('capacity', 'Capacity', 'numeric');
    
        if ($this->form_validation->run() == FALSE) {
            $this->addVenue();
        } else {
            $post = $this->input->post();
            $pitch_type_ids = $post['pitch_type_ids'] ?? [];
            unset($post['pitch_type_ids']);
    
            $this->dozen->insertVenue($post);
            $venue_id = $this->db->insert_id();
            $this->dozen->saveVenuePitchTypes($venue_id, $pitch_type_ids);
            $this->session->set_flashdata('success', 'Venue added successfully.');
            redirect('DozenDreams/venueList');
        }
    }
    
    public function updateVenue($id) {

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('location', 'Location', 'trim');
        $this->form_validation->set_rules('capacity', 'Capacity', 'numeric');
    
        if ($this->form_validation->run() == FALSE) {
            $this->editVenue($id);
        } else {
            $post = $this->input->post();
            $pitch_type_ids = $post['pitch_type_ids'] ?? [];
            unset($post['pitch_type_ids']);
    
            // ✅ use renamed model method here
            $this->dozen->updateVenueDetails($id, $post);
            $this->dozen->saveVenuePitchTypes($id, $pitch_type_ids);
    
            $this->session->set_flashdata('success', 'Venue updated successfully.');
            redirect('DozenDreams/venueList');
        }
    }    

    public function exportVenue() {
        $venues = $this->dozen->getVenues(1000, 0);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Location');
        $sheet->setCellValue('C1', 'Capacity');

        $row = 2;
        foreach ($venues as $v) {
            $sheet->setCellValue("A$row", $v['name']);
            $sheet->setCellValue("B$row", $v['location']);
            $sheet->setCellValue("C$row", $v['capacity']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="venues.xlsx"');
        $writer->save('php://output');
    }

    public function importVenue() {
        if (!empty($_FILES['excel_file']['name'])) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                $data = [
                    'name' => $row[0],
                    'location' => $row[1],
                    'capacity' => $row[2],
                ];
                $this->dozen->insertVenue($data);
            }
        }
        $this->session->set_flashdata('success', 'Venues imported successfully.');
        redirect('DozenDreams/venueList');
    }





    public function showContests() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        //Allow only superadmin, admin, developer, tester
        if (
            !$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')
        ) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }
    
        // Load all contests
        $data['contests'] = $this->dozen->getAllContests();
    
        // Load views
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/contest_list', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function allMatches() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        $allMatches = $this->dozen->getAllMatchesWithContests();
        $upcoming = [];
        $completed = [];
    
        $now = date('Y-m-d H:i:s');
    
        foreach ($allMatches as $match) {
            if ($match['match_date'] >= $now) {
                $upcoming[] = $match;
            } else {
                $completed[] = $match;
            }
        }
    
        $data['upcoming_matches'] = $upcoming;
        $data['completed_matches'] = $completed;
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/all_matches', $data);
        $this->load->view('dozendreams/layout/footer');
    }
    

    public function getContestsByMatch($match_id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }

        if ($this->session->userdata('is_superadmin') ||
            $this->session->userdata('is_admin') ||
            $this->session->userdata('is_developer') ||
            $this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Only users can access this page.');
            redirect('DozenDreams/dashboard');
        }

        $data['contests'] = $this->dozen->getContestsByMatchId($match_id);
        $data['match_info'] = $this->dozen->getMatchDetailsById($match_id);

        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/contest_view', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function createContest() {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }

        if (!$this->session->userdata('is_superadmin') &&
            !$this->session->userdata('is_admin') &&
            !$this->session->userdata('is_developer') &&
            !$this->session->userdata('is_tester')) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('DozenDreams/dashboard');
        }

        $data['matches'] = $this->dozen->getAllMatches();

        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/create_contest', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function saveContest() {
        $data = $this->input->post();
        $this->dozen->insertContest($data);
        redirect('DozenDreams/showContests');
    }

    public function joinContest($contest_id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }

        $user_id = $this->session->userdata('user_id');

        // Get contest and match info
        $contest = $this->dozen->getContestById($contest_id);

        if (!$contest) {
            $this->session->set_flashdata('error', 'Invalid contest selected.');
            redirect('DozenDreams/allMatches');
        }

        $match_id = $contest['match_id'];

        // Check if user has any teams for this match
        if (!$this->dozen->hasUserTeamsForMatch($user_id, $match_id)) {
            $this->session->set_flashdata('info', 'Please create a team before joining the contest.');
            redirect('DozenDreams/createTeam/' . $match_id);
        }

        // You can proceed with actual join process (not shown here)
        $this->session->set_flashdata('success', 'You can now join the contest.');
        redirect('DozenDreams/contestDetailsView/' . $contest_id);
    }

    public function contestDetailsView($contest_id = null)
    {
        if (!$contest_id) {
            show_error('Contest ID is required', 400);
        }
    
        // ✅ Get contest details
        $data['contest'] = $this->dozen->getContestById($contest_id);
        if (!$data['contest']) {
            show_404();
        }
    
        $match_id = $data['contest']['match_id'];
    
        // ✅ Get match details
        $matchDetails = $this->dozen->getMatchShortNames($match_id);
        $data['match_teams'] = $matchDetails['team1_short'] . ' vs ' . $matchDetails['team2_short'];
        $data['match_time'] = $matchDetails['start_time'];
        $data['match_id'] = $match_id;
    
        // ✅ Calculate spots filled percentage
        $spots_left = $data['contest']['spots_left'];
        $total_spots = $data['contest']['total_spots'];
        $data['filled_percent'] = 100 - (($spots_left / $total_spots) * 100);
    
        // ✅ Optional: Prize breakup
        $data['prize_breakup'] = $this->dozen->getPrizeBreakup($contest_id); // if implemented
    
        // ✅ User joined team count
        $data['user_teams_joined'] = 0;
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
            $joinedRow = $this->db->get_where('user_contest_teams', [
                'user_id' => $user_id,
                'contest_id' => $contest_id
            ])->row();
    
            if ($joinedRow) {
                $data['user_teams_joined'] = (int) $joinedRow->teams_count;
            }
        }
    
        // ✅ Load views
        $this->load->view('dozendreams/contest_details', $data);
    }
    



    public function createTeam($match_id = null) {
        if (!$match_id) {
            show_error('Match ID is required to create a team.', 400);
        }
    
        $topBarDetails = $this->dozen->getMatchTopBarDetails($match_id);
    
        $data['match_teams'] = $topBarDetails['team1_short'] . ' vs ' . $topBarDetails['team2_short'];
    
        $data['selected_pitch'] = (object)[
            'name' => $topBarDetails['pitch_name'],
            'average_score' => 'N/A', // Optional - extend if stored
            'pitch_type' => $topBarDetails['pitch_type']
        ];
    
        $players = $this->dozen->getAvailablePlayers($match_id); // This returns all players flat

        // 👉 Group by role
        $players_by_role = [];
        foreach ($players as $player) {

            $role = $player->player_role ?? 'UNKNOWN';
            $players_by_role[$role][] = $player;
        }

        $data['players_by_role'] = $players_by_role;
        $data['match_id'] = $match_id;
    
        $this->load->view('dozendreams/create_unique_team', $data);
    }

    public function saveCreatedTeam() {
        $user_id = $this->session->userdata('user_id');
        $match_id = $this->input->post('match_id') ?? $this->session->userdata('selected_match_id');
        $players = $this->input->post('players') ?? $this->session->userdata('selected_players');
        $captain_id = $this->input->post('captain_id');
        $vice_captain_id = $this->input->post('vice_captain_id');
    
        if (!$user_id) redirect('DozenDreams/login');
    
        // ✅ Validations
        if (!is_array($players) || count($players) !== 11) {
            $this->session->set_flashdata('error', 'Exactly 11 players must be selected.');
            redirect('DozenDreams/createTeam/' . $match_id);
        }
    
        if (!$captain_id || !$vice_captain_id) {
            $this->session->set_flashdata('error', 'Captain and Vice-Captain must be selected.');
            redirect('DozenDreams/selectCaptainView');
        }
    
        if (!in_array($captain_id, $players) || !in_array($vice_captain_id, $players)) {
            $this->session->set_flashdata('error', 'Captain and Vice-Captain must be among selected players.');
            redirect('DozenDreams/selectCaptainView');
        }
    
        if ($captain_id == $vice_captain_id) {
            $this->session->set_flashdata('error', 'Captain and Vice-Captain must be different.');
            redirect('DozenDreams/selectCaptainView');
        }
    
        // ✅ Normalize
        $players = array_map('intval', $players);
        sort($players);
        $current_team = [
            'captain' => (int)$captain_id,
            'vice_captain' => (int)$vice_captain_id,
            'team_players' => $players
        ];
    
        // ✅ Check existing row
        $existing_row = $this->dozen->getUserTeamRow($user_id, $match_id);
    
        if ($existing_row) {
            $team_data = json_decode($existing_row['team_data'], true) ?? [];
    
            // 🔁 Check if this team already exists
            foreach ($team_data as $existing_team) {
                $existing_players = array_map('intval', $existing_team['team_players'] ?? []);
                sort($existing_players);
    
                if (
                    (int)$existing_team['captain'] === $current_team['captain'] &&
                    (int)$existing_team['vice_captain'] === $current_team['vice_captain'] &&
                    $existing_players === $current_team['team_players']
                ) {
                    $this->session->set_flashdata('error', 'You cannot create a same team again.');
                    redirect('DozenDreams/viewOrCreateTeam/' . $match_id);
                }
            }
    
            // ✅ Check 20 team limit
            if (count($team_data) >= 20) {
                $this->session->set_flashdata('error', 'Maximum 20 teams allowed per match.');
                redirect('DozenDreams/viewOrCreateTeam/' . $match_id);
            }
    
            // 🆕 Append new team as next key
            $new_key = 'team' . (count($team_data) + 1);
            $team_data[$new_key] = $current_team;
    
            $this->dozen->updateTeamJson($existing_row['id'], json_encode($team_data, JSON_UNESCAPED_UNICODE));
            $this->session->set_flashdata('success', 'New team added successfully!');
        } else {
            // 🔰 First time insert
            $team_data = ['team1' => $current_team];
    
            $insert_data = [
                'user_id'   => $user_id,
                'match_id'  => $match_id,
                'team_data' => json_encode($team_data, JSON_UNESCAPED_UNICODE),
                'status_id' => 24
            ];
    
            $this->dozen->insertCreatedTeam($insert_data);
            $this->session->set_flashdata('success', 'First team created successfully!');
        }
    
        // 🧹 Clear temp
        $this->session->unset_userdata('selected_players');
        $this->session->unset_userdata('selected_match_id');
    
        redirect('DozenDreams/viewOrCreateTeam/' . $match_id);
    }
    
    public function viewOrCreateTeam($match_id, $contest_id = null) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        $user_id = $this->session->userdata('user_id');
        $this->load->model('Dozen_model', 'dozen');
    
        // ✅ Get match info
        $match_info = $this->dozen->getMatchDetailsById($match_id);
        if (!$match_info) {
            show_error("Match not found or invalid match ID.");
        }
    
        // ✅ Get user's teams for the match
        $user_teams = $this->dozen->getDecodedUserTeams($user_id, $match_id);
    
        // ✅ Optionally load contest info if needed later in view
        $contest_info = null;
        if (!empty($contest_id)) {
            $contest_info = $this->dozen->getContestById($contest_id);
        }
    
        // ✅ Pass to view
        $data = [
            'match_info'    => $match_info,
            'user_teams'    => $user_teams,
            'contest_id'    => $contest_id,
            'contest_info'  => $contest_info
        ];
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/layout/sidebar');
        $this->load->view('dozendreams/view_create_teams', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function selectTeam($contest_id)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        $user_id = $this->session->userdata('user_id');
    
        // ✅ Get contest info
        $contest = $this->dozen->getContestWithType($contest_id);
        if (!$contest) {
            show_404();
        }
    
        // ✅ Get match info
        $match_id = $contest['match_id'];
        $match_info = $this->dozen->getMatchDetailsById($match_id);
        if (!$match_info) {
            show_error("Invalid match ID associated with this contest.");
        }
    
        // ✅ Fetch full player list for the match (indexed by player_id)
        $players = $this->dozen->getPlayersByMatchId($match_id);
        $playersById = [];
        foreach ($players as $p) {
            $playersById[$p['player_id']] = $p;
        }
    
        // ✅ Fetch all user teams
        $user_team_rows = $this->dozen->getUserTeamsRaw($user_id, $match_id);
        $user_teams = [];
    
        foreach ($user_team_rows as $row) {
            $team_data = json_decode($row['team_data'], true);
    
            foreach ($team_data as $key => $team) {
                $team_info = [];
                $team_info['team_key'] = $key; // Just 'team1', 'team2', etc.
                
                $team_players = $team['team_players'] ?? [];
    
                // ✅ Count team compositions
                $counts = ['WK' => 0, 'BAT' => 0, 'AR' => 0, 'BOWL' => 0];
                $team1_count = 0;
                $team2_count = 0;
    
                foreach ($team_players as $pid) {
                    $player = $playersById[$pid] ?? null;
                    if ($player) {
                        $role = $player['player_role'] ?? '';
                        if (isset($counts[$role])) {
                            $counts[$role]++;
                        }
    
                        // ✅ Team distribution
                        if ($player['team_id'] == $match_info['team1_id']) {
                            $team1_count++;
                        } elseif ($player['team_id'] == $match_info['team2_id']) {
                            $team2_count++;
                        }
                    }
                }
    
                $team_info['wk_count'] = $counts['WK'];
                $team_info['bat_count'] = $counts['BAT'];
                $team_info['ar_count'] = $counts['AR'];
                $team_info['bowl_count'] = $counts['BOWL'];
                $team_info['team1_count'] = $team1_count;
                $team_info['team2_count'] = $team2_count;
    
                // ✅ Captain fallback
                $captain = $playersById[$team['captain']] ?? null;
                if ($captain) {
                    $captain['image'] = !empty($captain['image'])
                        ? $captain['image']
                        : ASSETS_PATH . 'img/dozen/' . (strtoupper($captain['batting_hand_code'] ?? '') === 'LHB' ? 'lhb.png' : 'rhb.png');
                    
                    // ✅ Add this line:
                    $captain['name'] = $captain['player_name'] ?? 'N/A';
                }
                
                $vice_captain = $playersById[$team['vice_captain']] ?? null;
                if ($vice_captain) {
                    $vice_captain['image'] = !empty($vice_captain['image'])
                        ? $vice_captain['image']
                        : ASSETS_PATH . 'img/dozen/' . (strtoupper($vice_captain['batting_hand_code'] ?? '') === 'LHB' ? 'lhb.png' : 'rhb.png');
                    
                    // ✅ Add this line:
                    $vice_captain['name'] = $vice_captain['player_name'] ?? 'N/A';
                }
                
    
                $team_info['captain'] = $captain;
                $team_info['vice_captain'] = $vice_captain;
    
                // ✅ Owner image
                $team_info['owner_image'] = $this->dozen->getUserImage($user_id) ?? ASSETS_PATH . 'img/dozen/profile.png';
    
                $user_teams[] = $team_info;
            }
        }
    
        $data = [
            'contest' => $contest,
            'match_info' => $match_info,
            'user_teams' => $user_teams,
            'entry_fee_total' => count($user_teams) * floatval($contest['entry_fee']),
        ];
    
        $this->load->view('dozendreams/layout/header');
        $this->load->view('dozendreams/select_team_view', $data);
        $this->load->view('dozendreams/layout/footer');
    }

    public function submitContestEntry()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
    
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => 'error', 'message' => 'Login required.']);
            return;
        }
    
        $user_id = $this->session->userdata('user_id');
        $json = json_decode(file_get_contents('php://input'), true);
    
        $match_id = intval($json['match_id'] ?? 0);
        $contest_id = intval($json['contest_id'] ?? 0);
        $team_keys = $json['team_keys'] ?? [];
    
        if (!$match_id || !$contest_id || empty($team_keys)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or incomplete data.']);
            return;
        }
    
        $result = $this->dozen->insertUserContestTeams($user_id, $match_id, $contest_id, $team_keys);
    
        echo json_encode($result);
    }
    
     
    
    public function selectCaptainView() {
        $players = $this->input->post('players');
        $match_id = $this->input->post('match_id');
    
        if (!is_array($players) || count($players) !== 11) {
            $this->session->set_flashdata('error', 'Exactly 11 players must be selected.');
            redirect('DozenDreams/createTeam/' . $match_id);
        }
    
        // Save to session
        $this->session->set_userdata('selected_players', $players);
        $this->session->set_userdata('selected_match_id', $match_id);
    
        // Load player details for next view
        $data['match_id'] = $match_id;
        $data['players'] = $this->dozen->getPlayersByPlayerIds($players); // Ensure this fetches role_name

        $this->load->view('dozendreams/select_captain_view', $data);
    }
    
    public function createTeamViewFromCaptain() {
        $players = $this->session->userdata('selected_players');
        $match_id = $this->session->userdata('selected_match_id');
    
        if (!is_array($players) || count($players) !== 11) {
            $this->session->set_flashdata('error', 'No team found in session.');
            redirect('DozenDreams/createTeam/' . $match_id);
        }
    
        $data['match_id'] = $match_id;
        $data['preselected_players'] = $players;
    
        // ✅ Flat player list
        $all_players = $this->dozen->getAvailablePlayers($match_id);
    
        // ✅ Group by player_role
        $players_by_role = [
            'WK' => [],
            'BAT' => [],
            'AR' => [],
            'BOWL' => []
        ];
    
        foreach ($all_players as $player) {
            $role = strtoupper(trim($player->player_role ?? $player->special_ability ?? 'IMP'));
            if (!array_key_exists($role, $players_by_role)) {
                $role = 'IMP'; // fallback
            }
            $players_by_role[$role][] = $player;
        }
    
        $data['players_by_role'] = $players_by_role;
    
        $this->load->view('dozendreams/create_unique_team', $data);
    }
    
    public function viewTeam($match_id, $team_key) {
        if (!$this->session->userdata('logged_in')) {
            redirect('DozenDreams/login');
        }
    
        $user_id = $this->session->userdata('user_id');
    
        // Fetch the single user_team row for this user and match
        $team_row = $this->db->get_where('user_teams', [
            'user_id'  => $user_id,
            'match_id' => $match_id
        ])->row_array();
    
        if (!$team_row) {
            show_404();
        }
    
        // Decode all teams stored in JSON
        $team_data = json_decode($team_row['team_data'], true);
    
        if (!isset($team_data[$team_key])) {
            show_error("Team not found with key: " . htmlspecialchars($team_key));
        }
    
        $selected_team = $team_data[$team_key];
    
        $player_ids = $selected_team['team_players'] ?? [];
        $captain_id = $selected_team['captain'] ?? null;
        $vice_captain_id = $selected_team['vice_captain'] ?? null;
    
        // Fetch player info
        $players_raw = $this->dozen->getPlayersByPlayerIds($player_ids);
    
        $players = [];
        foreach ($players_raw as $p) {
            $p->c = ($p->id == $captain_id);
            $p->vc = ($p->id == $vice_captain_id);
            $players[] = $p;
        }
    
        $data['players']    = $players;
        $data['match_id']   = $match_id;
        $data['team_key']   = $team_key;
        $data['team_name']  = 'Team ' . ucfirst($team_key);
        $data['created_at'] = $team_row['created_at'];
    
        $this->load->view('dozendreams/view_team', $data);
    }






}
