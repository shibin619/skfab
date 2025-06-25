<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DozenDreamsModel extends CI_Model {

    // Login function with full role/status
    public function login($email, $password) {
        $this->db->select('
            users.id, users.name, users.email, users.password,
            role.name as role_name, role.short_code as role_code,
            status.name as status_name, status.short_code as status_code
        ');
        $this->db->from('users');
        $this->db->join('masters as role', 'users.role_id = role.id', 'left');
        $this->db->join('masters as status', 'users.status_id = status.id', 'left');
        $this->db->where('users.email', $email);
        $this->db->where('status.short_code', '1'); // Only active users
        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                return (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role_code, // 'user', 'admin', etc.
                    'role_name' => $user->role_name,
                    'status' => $user->status_code,
                ];
            }
        }
        return false;
    }

    public function getTotalMatches() {
        return $this->db->count_all('matches');
    }
    
    public function getTotalPlayers() {
        return $this->db->count_all('players');
    }
    
    public function getTotalPoints($user_id) {
        $this->db->select_sum('points');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_points');
        return $query->row()->points ?? 0;
    }
    
    public function insertTeams($user_id, $players, $impact_player) {
        $this->db->trans_start();

        $team_data = [
            'user_id' => $user_id,
            'impact_player' => $impact_player,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('user_teams', $team_data);
        $team_id = $this->db->insert_id();
    
        $team_players = array_map(function($pid) use ($team_id) {
            return ['team_id' => $team_id, 'player_id' => $pid];
        }, $players);
    
        $this->db->insert_batch('team_players', $team_players);
        $this->db->trans_complete();
    
        return $team_id;
    }

    public function getPlayersByPitchType($pitch_type_id) {
        $this->db->select('p.*');
        $this->db->from('players p');
        $this->db->join('pitch_type_ability_map m', 'p.special_ability = m.special_ability');
        $this->db->where('m.pitch_type_id', $pitch_type_id);
        $this->db->where('p.fitness_status', 'Fit'); // Optional: filter fit players only
        $this->db->order_by('p.credit_points', 'DESC');
        return $this->db->get()->result();
    }

    public function getPlayersGroupedByRole($pitch_type_id) {
        $this->db->select('players.*, teams.short_name AS team_name');
        $this->db->from('players');
        $this->db->join('pitch_type_ability_map', 'players.id = pitch_type_ability_map.player_id');
        $this->db->join('teams_players', 'players.id = teams_players.player_id');
        $this->db->join('teams', 'teams.id = teams_players.team_id');
        $this->db->where('pitch_type_ability_map.pitch_type_id', $pitch_type_id);
        $this->db->where('players.status_id', 6); // Only active players
        $this->db->group_by('players.id'); // Prevent duplicates if player is mapped to multiple teams
        $query = $this->db->get();
    
        $grouped = [
            'WK' => [],
            'BAT' => [],
            'AR' => [],
            'BOWL' => [],
            'IMP' => []
        ];
    
        foreach ($query->result() as $row) {
            $role = strtoupper(trim($row->special_ability)); // More robust
            if (array_key_exists($role, $grouped)) {
                $grouped[$role][] = $row;
            } else {
                $grouped['IMP'][] = $row; // Fallback
            }
        }
    
        return $grouped;
    }

    public function getPlayersByPlayerIds($ids = []) {
        if (empty($ids)) return [];
    
        $this->db->select('p.*, t.name AS team_name, r.role_name AS role_name');
        $this->db->from('players p');
    
        // Join to get team info
        $this->db->join('player_team pt', 'pt.player_id = p.id', 'left');
        $this->db->join('teams t', 't.id = pt.team_id', 'left');
    
        // Join roles table instead of masters
        $this->db->join('roles r', 'r.id = pt.role_id', 'left');
    
        $this->db->where_in('p.id', $ids);
        $this->db->group_by('p.id'); // Ensures distinct player records
    
        return $this->db->get()->result();
    }

    public function getDecodedUserTeams($user_id, $match_id) {
        // Get the single team row
        $team = $this->db->get_where('user_teams', [
            'user_id' => $user_id,
            'match_id' => $match_id
        ])->row_array();
    
        if (!$team) return [];
    
        $team_data = json_decode($team['team_data'], true);
        if (!is_array($team_data)) return [];
    
        $result = [];
    
        foreach ($team_data as $key => $entry) {
            $player_ids = $entry['team_players'] ?? [];
            $captain_id = $entry['captain'] ?? null;
            $vice_captain_id = $entry['vice_captain'] ?? null;
    
            // Get all related players
            $unique_ids = array_unique(array_merge($player_ids, [$captain_id, $vice_captain_id]));
            $this->db->where_in('id', $unique_ids);
            $players_raw = $this->db->get('players')->result_array();
    
            $player_map = [];
            foreach ($players_raw as $p) {
                $player_map[$p['id']] = $p;
            }
    
            // Count team distribution
            $team_counts = [];
            foreach ($player_ids as $pid) {
                $team_name = $player_map[$pid]['team_name'] ?? 'Unknown';
                $team_counts[$team_name] = ($team_counts[$team_name] ?? 0) + 1;
            }
    
            arsort($team_counts);
            $teams_list = array_keys($team_counts);
            $team1_name = $teams_list[0] ?? 'Team A';
            $team2_name = $teams_list[1] ?? 'Team B';
    
            $result[] = [
                'team_key'       => $key, // 'team1', 'team2', etc.
                'team_id'        => $team['id'],
                'team_name'      => 'Team ' . ucfirst($key),
                'captain'        => $player_map[$captain_id] ?? null,
                'vice_captain'   => $player_map[$vice_captain_id] ?? null,
                'players'        => array_map(fn($pid) => $player_map[$pid] ?? null, $player_ids),
                'team1_name'     => $team1_name,
                'team2_name'     => $team2_name,
                'team1_count'    => $team_counts[$team1_name] ?? 0,
                'team2_count'    => $team_counts[$team2_name] ?? 0,
                'created_at'     => $team['created_at'],
            ];
            
        }
    
        return $result;
    }
    
    public function getContestWithType($contest_id)
    {
        $this->db->select('
            c.*, 
            ct.short_name AS type_name, 
            ct.short_code, 
            ct.description AS type_description, 
            ct.entry_limit, 
            ct.total_teams_limit
        ');
        $this->db->from('contests c');
        $this->db->join('contest_types ct', 'ct.id = c.contest_type_id', 'left');
        $this->db->where('c.id', $contest_id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getScheduledMatches() {
        return $this->db->select('m.id as match_id, s.match_date, t1.short_name as team_a, t2.short_name as team_b, tr.short_name as tournament')
            ->from('matches m')
            ->join('schedules s', 's.id = m.schedule_id')
            ->join('teams t1', 't1.id = s.team_a_id')
            ->join('teams t2', 't2.id = s.team_b_id')
            ->join('tournaments tr', 'tr.id = s.tournament_id')
            ->order_by('s.match_date', 'ASC')
            ->get()
            ->result_array();
    }

    public function getPlayersByMatchId($match_id) {
        $this->db->select('p.id AS player_id, p.name AS player_name, p.credit_points, r.short_name AS player_role');
        $this->db->from('matches m');
        $this->db->join('schedules s', 's.id = m.schedule_id');
        $this->db->join('player_team pt', 'pt.team_id = s.team_a_id OR pt.team_id = s.team_b_id');
        $this->db->join('players p', 'p.id = pt.player_id');
        $this->db->join('roles r', 'r.id = pt.role_id');
        $this->db->where('m.id', $match_id);
        $this->db->group_by('p.id');

        return $this->db->get()->result_array();
    }

    public function getRoleMap() {
        $query = $this->db->get('roles');
        $map = [];
        foreach ($query->result_array() as $role) {
            $map[$role['id']] = $role['short_name'];
        }
        return $map;
    }

    public function getOpponentTeams() {
        $teams = $this->db->select('id, short_name')->get('teams')->result_array();
        $result = [];
        foreach ($teams as $team) {
            $result[$team['id']] = $team['short_name'];
        }
        return $result;
    }

 
    

    public function getUserTeamsRaw($user_id, $match_id)
    {
        $this->db->select('id, user_id, match_id, team_data');
        $this->db->from('user_teams');
        $this->db->where('user_id', $user_id);
        $this->db->where('match_id', $match_id);
        $this->db->where('status_id', 24); // Assuming active teams only
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();
        return $query->result_array(); // Returns array of rows with team_data (JSON)
    }

    
    public function getAllUsersWithDetails() {
        $this->db->select('u.id, u.name, u.email, u.created_at,
                           r.name AS role,
                           s.name AS status');
        $this->db->from('users u');
        $this->db->join('masters r', 'r.id = u.role_id', 'left');
        $this->db->join('master_types rt', 'r.master_type_id = rt.id AND rt.name = "Role"', 'left');
        $this->db->join('masters s', 's.id = u.status_id', 'left');
        $this->db->join('master_types st', 's.master_type_id = st.id AND st.name = "Status"', 'left');
        return $this->db->get()->result_array();
    }
    
    public function getMasterTypes() {
        return $this->db->get('master_types')->result();
    }
    
    public function insertMaster($data) {
        return $this->db->insert('masters', $data);
    }
    
    public function updateMaster($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('masters', $data);
    }
    
    public function getMasterById($id) {
        return $this->db->get_where('masters', ['id' => $id])->row();
    }
    
    public function getAllMasters() {
        $this->db->select('m.*, mt.name as master_type');
        $this->db->from('masters m');
        $this->db->join('master_types mt', 'mt.id = m.master_type_id');
        return $this->db->get()->result();
    }

    public function getAllPlayerStatisticsWithPlayerNames() {
        return $this->db
            ->select('ps.*, p.name as player_name, p.status_id as player_status_id, m.name as player_status_name')
            ->from('player_statistics ps')
            ->join('players p', 'p.id = ps.player_id')
            ->join('masters m', 'm.id = p.status_id AND m.master_type_id = 2', 'left') // 2 => Status
            ->get()
            ->result_array();
    }
    
    public function getAllPlayers() {
        return $this->db
            ->select('p.*, m.name as status_name')
            ->from('players p')
            ->join('masters m', 'm.id = p.status_id AND m.master_type_id = 2', 'left')
            ->get()
            ->result();
    }

    public function getPlayersWithFullDetailsByMatch($matchId) {
        $today = date('Y-m-d');
    
        $sql = "
            SELECT 
                p.id AS player_id,
                p.name,
                p.image,
                p.credit_points,
                p.fitness_status,
                p.special_ability_id,
                pt.role_id,
                pt.team_id,
                ps.matches,
                ps.runs,
                ps.balls_faced,
                ps.strike_rate,
                ps.fours,
                ps.sixes,
                ps.wickets,
                ps.overs_bowled,
                ps.economy_rate,
                ps.runs_given,
                ps.catches,
                ps.stumpings
            FROM matches m
            INNER JOIN schedules s ON s.id = m.schedule_id
            INNER JOIN player_team pt ON pt.team_id IN (s.team_a_id, s.team_b_id)
            INNER JOIN players p ON p.id = pt.player_id
            LEFT JOIN player_statistics ps ON ps.player_id = p.id
            WHERE 
                m.id = ?
                AND pt.start_date <= ?
                AND (pt.end_date IS NULL OR pt.end_date >= ?)
            ORDER BY p.name ASC
        ";
    
        $query = $this->db->query($sql, [$matchId, $today, $today]);
        return $query->result_array();
    }
    


    
    public function insertPlayerStatistics($data) {
        // Ensure only valid data is inserted (player_id, runs, wickets, catches, status_id)
        return $this->db->insert('player_statistics', $data);
    }
    
    public function getPlayerStatisticsById($id) {
        return $this->db
            ->select('ps.*, p.name as player_name')
            ->from('player_statistics ps')
            ->join('players p', 'p.id = ps.player_id', 'left')
            ->where('ps.id', $id)
            ->get()
            ->row();
    }
    
    public function updatePlayerStatistics($id, $data) {
        return $this->db->where('id', $id)->update('player_statistics', $data);
    }
    
    public function deletePlayerStatistics($id) {
        return $this->db->delete('player_statistics', ['id' => $id]);
    }
    
    public function getPlayerStatById($id) {
        return $this->db
            ->select('ps.*, p.name as player_name')
            ->from('player_statistics ps')
            ->join('players p', 'p.id = ps.player_id', 'left')
            ->where('ps.id', $id)
            ->get()
            ->row(); // Return as object
    }
    
    public function getPlayersByIds(array $ids) {
        return $this->db
            ->select('p.*, m.name as status_name')
            ->from('players p')
            ->join('masters m', 'm.id = p.status_id AND m.master_type_id = 2', 'left')
            ->where_in('p.id', $ids)
            ->get()
            ->result_array();
    }
    
    // Fetch all rows from 'masters' table where type matches

    public function getMastersByType($typeName) {
        $this->db->select('m.id, m.name, m.short_code')
                 ->from('masters m')
                 ->join('master_types mt', 'mt.id = m.master_type_id')
                 ->where('mt.name', $typeName)
                 ->where('m.status', 1); // Optional: only active
        return $this->db->get()->result_array();
    }
    
    public function getAllStatuses() {
        $this->db->select('m.id, m.name, m.short_code')
                 ->from('masters m')
                 ->join('master_types mt', 'mt.id = m.master_type_id')
                 ->where('mt.name', 'Status')
                 ->where('m.status', 1);
        return $this->db->get()->result_array();
    }    
    

    public function getPlayerStatsById(?int $playerId) {
        if (empty($playerId)) return null;

        return $this->db->where('player_id', $playerId)
            ->get('player_statistics')
            ->row_array();
    }

    

    public function getAllPoints()
    {
        $this->db->select('ps.*, mt.type_name AS match_type, r.role_name');
        $this->db->from('points_system ps');
        $this->db->join('match_types mt', 'ps.type_id = mt.id', 'left');
        $this->db->join('roles r', 'ps.role_id = r.id', 'left'); // ✅ new join
        $this->db->order_by('ps.id', 'DESC');
        return $this->db->get()->result_array();
    }
    
    public function getMatchTypes()
    {
        return $this->db->order_by('id', 'ASC')->get('match_types')->result_array();
    }
    
    public function getPoint(int $id) {
        return $this->db->get_where('points_system', ['id' => $id])->row_array();
    }

    public function insertPoint(array $data): bool {
        return $this->db->insert('points_system', $data);
    }


    public function updatePoint(int $id, array $data): bool {
        return $this->db->where('id', $id)->update('points_system', $data);
    }



    /**
     * Get player statistics by player short name.
     * @param string $shortName
     * @return array|null
     */
    public function getPlayerStatisticsByShortName(string $shortName) {
        return $this->db->get_where('players', ['short_name' => $shortName])->row_array();
    }

    /**
     * Get all schedules with short names for tournament and teams.
     * @return array
     */
    public function getAllSchedulesWithShortNames() {
        $this->db->select('
            t.short_name as tournament_short_name,
            ta.short_name as team_a_short_name,
            tb.short_name as team_b_short_name,
            s.match_date,
            s.venue,
            s.status
        ')
        ->from('schedules s')
        ->join('tournaments t', 't.id = s.tournament_id')
        ->join('teams ta', 'ta.id = s.team_a_id')
        ->join('teams tb', 'tb.id = s.team_b_id');
        return $this->db->get()->result_array();
    }

    /**
     * Get tournament by short name.
     * @param string $shortName
     * @return array|null
     */
    public function getTournamentByShortName(string $shortName) {
        return $this->db->get_where('tournaments', ['short_name' => $shortName])->row_array();
    }


    // Get a team by ID
    public function getTeamById(int $id) {
        return $this->db->get_where('teams', ['id' => $id])->row_array();
    }

    // Insert new team and return insert_id
    public function insertTeam(array $data) {
        $this->db->insert('teams', $data);
        return $this->db->insert_id();
    }

    // Update team details
    public function updateTeam(int $id, array $data): bool {
        return $this->db->where('id', $id)->update('teams', $data);
    }


    /**
     * Get tournament by ID.
     * @param int $id
     * @return array|null
     */
    public function getTournamentById(int $id) {
        return $this->db->get_where('tournaments', ['id' => $id])->row_array();
    }

    /**
     * Insert a tournament.
     * @param array $data
     * @return bool
     */
    public function insertTournament(array $data): bool {
        return $this->db->insert('tournaments', $data);
    }

    /**
     * Update a tournament by ID.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateTournament(int $id, array $data): bool {
        return $this->db->where('id', $id)->update('tournaments', $data);
    }

    /**
     * Get paginated players with team and tournament names.
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getPlayers(int $limit, int $offset) {
        $this->db->select('players.*, teams.name as team_name, tournaments.name as tournament_name')
            ->from('players')
            ->join('teams', 'players.team_id = teams.id')
            ->join('tournaments', 'teams.tournament_id = tournaments.id')
            ->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    /**
     * Count all players (for pagination).
     * @return int
     */
    public function countPlayers(): int {
        return $this->db->count_all('players');
    }

    /**
     * Get player by ID.
     * @param int $id
     * @return array|null
     */
    public function getPlayerById(int $id) {
        return $this->db->get_where('players', ['id' => $id])->row_array();
    }

    /**
     * Insert a new player.
     * @param array $data
     * @return bool
     */
    public function insertPlayer(array $data): bool {
        return $this->db->insert('players', $data);
    }

    /**
     * Update a player by ID.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePlayer(int $id, array $data): bool {
        return $this->db->where('id', $id)->update('players', $data);
    }

    /**
     * Get teams by tournament ID.
     * @param int $tournament_id
     * @return array
     */
    public function getTeamsByTournament(int $tournament_id) {
        return $this->db->get_where('teams', ['tournament_id' => $tournament_id])->result_array();
    }

    /**
     * Count all venues.
     * @return int
     */
    public function countVenues(): int {
        return $this->db->count_all('venues');
    }

    /**
     * Get venues with pagination.
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getVenues(int $limit, int $start) {
        return $this->db->limit($limit, $start)->get('venues')->result();
    }

    /**
     * Insert a venue.
     * @param array $data
     * @return bool
     */
    public function insertVenue(array $data): bool {
        return $this->db->insert('venues', $data);
    }

    /**
     * Update a venue by ID.
     * @param int $id
     * @param array $data
     * @return bool
     */
    // In Dozen_model.php or wherever the model is
    public function updateVenueDetails(int $id, array $data): bool {
        return $this->db->where('id', $id)->update('venues', $data);
    }

    public function getAllReferralDetails() {
        return $this->db->select('u.name as parent_name, u2.name as referred_name, r.level, r.amount')
            ->from('referral_earnings r')
            ->join('users u', 'u.id = r.user_id')
            ->join('users u2', 'u2.id = r.referred_user_id')
            ->order_by('r.level', 'ASC')
            ->get()->result();
    }
    public function getVenueById(int $id) {
        return $this->db->get_where('venues', ['id' => $id])->row();
    }

    public function getAllPitchTypes() {
        return $this->db->get('pitch_types')->result_array();
    }

    /**
     * Get pitch type IDs associated with a venue.
     * @param int $venue_id
     * @return array
     */
    public function getVenuePitchTypes(int $venue_id) {
        $this->db->select('pitch_type_id')->where('venue_id', $venue_id);
        return array_column($this->db->get('venue_pitch_types')->result_array(), 'pitch_type_id');
    }

    /**
     * Save pitch types for a venue.
     * @param int $venue_id
     * @param array $pitch_type_ids
     * @return void
     */
    public function saveVenuePitchTypes(int $venue_id, array $pitch_type_ids = []) {
        $this->db->where('venue_id', $venue_id)->delete('venue_pitch_types');

        if (!empty($pitch_type_ids)) {
            $insert_data = [];
            foreach ($pitch_type_ids as $pitch_type_id) {
                $insert_data[] = [
                    'venue_id' => $venue_id,
                    'pitch_type_id' => $pitch_type_id
                ];
            }
            $this->db->insert_batch('venue_pitch_types', $insert_data);
        }
    }
    public function filter_players() {
        $filters = [
            'role' => $this->input->get('role'),               // e.g. 'Batsman'
            'special_ability' => $this->input->get('ability'), // e.g. 'Power Hitter'
            'max_credit' => $this->input->get('max_credit'),   // e.g. 9.5
            'tournament_id' => $this->input->get('tournament_id'),  // e.g. 1
            'pitch_type' => $this->input->get('pitch_type'),   // e.g. 'Spin-friendly'
        ];
    
        // Remove empty filters
        $filters = array_filter($filters);
    
        $this->load->model('Player_model');
        $players = $this->Player_model->get_players_by_filters($filters);
    
        // Pass $players to view or return as JSON
        $this->load->view('player_list', ['players' => $players]);
    }

    public function isPlayerFit($playerId)
    {
        $this->db->select('fitness_status');
        $this->db->from('players');
        $this->db->where('id', $playerId);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row()->fitness_status == 0;
        }

        return false; // treat unknown player as not fit
    }

    public function getAllContests() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('contests');
        return $query->result_array();
    }
    
    public function getPlayerRoles() {
        return $this->db->get('roles')->result_array();
    }
    

    

    public function getReferralLevels() {
        return $this->db->get('referral_levels')->result();
    }
    
    public function getReferralChain($user_id, $limit = 3) {
        $chain = [];
        while ($limit-- > 0 && $user_id) {
            $user = $this->db->select('referred_by')->from('users')->where('id', $user_id)->get()->row();
            if ($user && $user->referred_by) {
                $user_id = $user->referred_by;
                $chain[] = $user_id;
            } else break;
        }
        return $chain;
    }
    
    public function insertReferralEarning($data) {
        $this->db->insert('referral_earnings', $data);
        // Wallet update
        $this->db->set('balance', 'balance+' . $data['amount'], FALSE)->where('user_id', $data['user_id'])->update('wallets');
        // Wallet transaction
        $this->db->insert('wallet_transactions', [
            'user_id' => $data['user_id'],
            'wallet_type_id' => 2,
            'type' => 'credit',
            'amount' => $data['amount'],
            'source' => 'Referral Level ' . $data['level']
        ]);
    }

    public function getWalletBalance($user_id)
    {
        if (empty($user_id)) {
            return 0;
        }
    
        // Total credit
        $this->db->select_sum('amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('type', 'credit');
        $credit = $this->db->get('wallet_transactions')->row()->amount ?? 0;
    
        // Total debit
        $this->db->select_sum('amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('type', 'debit');
        $debit = $this->db->get('wallet_transactions')->row()->amount ?? 0;
    
        return round((float)$credit - (float)$debit, 2);
    }
    


    // 🔹 Get all schedules with joined names (including venue name)
    public function getAllSchedulesWithNames() {
        return $this->db->select('s.*, 
                                  ta.name as team_a_name, 
                                  tb.name as team_b_name, 
                                  t.name as tournament_name, 
                                  v.name as venue_name, 
                                  m.name as status_name, 
                                  m.short_code as status')
                        ->from('schedules s')
                        ->join('teams ta', 'ta.id = s.team_a_id', 'left')
                        ->join('teams tb', 'tb.id = s.team_b_id', 'left')
                        ->join('tournaments t', 't.id = s.tournament_id', 'left')
                        ->join('venues v', 'v.id = s.venue_id', 'left')
                        ->join('masters m', 'm.id = s.status_id', 'left') // ✅ Removed m.type condition
                        ->order_by('s.match_date', 'DESC')
                        ->get()->result_array();
    }
    
    
    
    // 🔹 Get a specific schedule by ID with joined names
    public function getScheduleById($id) {
        return $this->db->select('s.*, 
                                  ta.name as team_a_name, 
                                  tb.name as team_b_name, 
                                  t.name as tournament_name, 
                                  v.name as venue_name, v.location as venue_location, 
                                  m.name as status_name, m.short_code as status_short_code')
                        ->from('schedules s')
                        ->join('teams ta', 'ta.id = s.team_a_id', 'left')
                        ->join('teams tb', 'tb.id = s.team_b_id', 'left')
                        ->join('tournaments t', 't.id = s.tournament_id', 'left')
                        ->join('venues v', 'v.id = s.venue_id', 'left')
                        ->join('masters m', 'm.id = s.status_id', 'left')
                        ->where('s.id', $id)
                        ->get()->row_array();
    }

    // 🔹 Insert a new schedule
    public function insertSchedule($data) {
        return $this->db->insert('schedules', $data);
    }

    // 🔹 Update an existing schedule
    public function updateSchedule($id, $data) {
        return $this->db->where('id', $id)->update('schedules', $data);
    }

    // 🔹 Get all tournaments
    public function getAllTournaments() {
        return $this->db->order_by('name', 'ASC')->get('tournaments')->result_array();
    }

    // 🔹 Get all teams
    public function getAllTeams() {
        return $this->db->order_by('name', 'ASC')->get('teams')->result_array();
    }

    // 🔹 Get all venues
    public function getAllVenues() {
        return $this->db->order_by('name', 'ASC')->get('venues')->result_array();
    }

    // 🔹 Get all status options from masters table
    public function getAllScheduleStatuses() {
        return $this->db->select('id, name, short_code')
                        ->from('masters')
                        ->where('master_type_id', 3) // 2 = Status
                        ->where('status', 1)
                        ->order_by('name', 'ASC')
                        ->get()
                        ->result_array();
    }

    public function deleteSchedule($id) {
        return $this->db->delete('schedules', ['id' => $id]);
    }


        // ✅ 1. Get user by referral (email or ID)
        public function getUserByReferral($referral_code) {
            return $this->db->select('users.id')
                            ->from('users')
                            ->join('user_details', 'user_details.user_id = users.id')
                            ->where('user_details.referral_code', $referral_code)
                            ->get()
                            ->row_array(); // returns null if not found
        }
        
    
        // ✅ 2. Get admin ID (fallback, can enhance to round-robin later)
        public function getNextAvailableAdminId()
        {
            $adminRoleId = $this->getMasterIdByTypeAndCode('Role', 'admin');
            $activeStatusId = $this->getMasterIdByTypeAndCode('Status', '1');
    
            $this->db->where('role_id', $adminRoleId);
            $this->db->where('status_id', $activeStatusId);
            $this->db->order_by('id', 'ASC');
            $query = $this->db->get('users', 1);
    
            return ($query->num_rows() > 0) ? $query->row()->id : null;
        }
    
        // ✅ 3. Get master ID based on type and short_code
        public function getMasterIdByTypeAndCode($typeName, $shortCode)
        {
            $this->db->select('m.id');
            $this->db->from('masters m');
            $this->db->join('master_types mt', 'mt.id = m.master_type_id');
            $this->db->where('mt.name', $typeName);
            $this->db->where('m.short_code', $shortCode);
            $this->db->where('m.status', 1); // Active only
            $query = $this->db->get();
    
            return ($query->num_rows() > 0) ? $query->row()->id : null;
        }
    
        // ✅ 4. Add node to dream_tree
        public function addToTree($data)
        {
            $treeData = [
                'user_id'    => $data['user_id'],
                'parent_id'  => $data['parent_id'],
                'position'   => $data['position'],
                'level'      => $data['level'],
                'created_at' => date('Y-m-d H:i:s')
            ];
    
            return $this->db->insert('dream_tree', $treeData);
        }
    
        // ✅ 5. Get level of a tree node
        public function getTreeLevel($parent_id)
        {
            $this->db->select('level');
            $this->db->where('user_id', $parent_id);
            $query = $this->db->get('dream_tree');
            return ($query->num_rows() > 0) ? $query->row()->level : 0;
        }
    
        // ✅ 6. Check available position under a parent (left/right)
        public function getAvailableTreePosition($parent_id)
        {
            $this->db->where('parent_id', $parent_id);
            $query = $this->db->get('dream_tree');
            $positions = array_column($query->result_array(), 'position');
    
            if (!in_array('left', $positions)) return 'left';
            if (!in_array('right', $positions)) return 'right';
    
            return null;
        }
    
        // ✅ 7. Insert user into tree (handles fallback to 5th+ level)
        public function insertUserToTree($user_id, $parent_id = null)
        {
            $final_parent = $parent_id
                ? $this->findAvailableParent($parent_id)
                : $this->findGlobalAvailableParent();
    
            if ($final_parent === null) return false;
    
            $position = $this->getAvailableTreePosition($final_parent);
            $level    = $this->getTreeLevel($final_parent) + 1;
    
            return $this->addToTree([
                'user_id'   => $user_id,
                'parent_id' => $final_parent,
                'position'  => $position,
                'level'     => $level
            ]);
        }
    
        // ✅ 8. Recursively find a parent with open slot (left/right)
        public function findAvailableParent($parent_id)
        {
            // Check if current parent has room
            if ($this->getAvailableTreePosition($parent_id) !== null) {
                return $parent_id;
            }
    
            // Recurse children left to right
            $this->db->where('parent_id', $parent_id);
            $this->db->order_by('position', 'ASC');
            $children = $this->db->get('dream_tree')->result();
    
            foreach ($children as $child) {
                $available = $this->findAvailableParent($child->user_id);
                if ($available !== null) return $available;
            }
    
            return null;
        }
    
        // ✅ 9. Start global search for a root to auto-place user
        public function findGlobalAvailableParent()
        {
            $this->db->where('level', 0);
            $root = $this->db->get('dream_tree')->row();
    
            return $root ? $this->findAvailableParent($root->user_id) : null;
        }
    
        // ✅ 10. Insert root node if tree is empty
        public function initializeTreeIfEmpty($user_id)
        {
            $this->db->where('level', 0);
            $exists = $this->db->get('dream_tree')->row();
    
            if (!$exists) {
                return $this->addToTree([
                    'user_id'   => $user_id,
                    'parent_id' => null,
                    'position'  => null,
                    'level'     => 0
                ]);
            }
    
            return false;
        }


    public function register($data) {
        $userData = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => $data['password'],
            'role_id'     => $data['role_id'],
            'status_id'   => $data['status_id'],
            'referred_by' => $data['referred_by'] ?? null,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s')
        ];
    
        $this->db->insert('users', $userData);
        return $this->db->insert_id(); // ✅ This returns the user_id
    }
    
    
    public function email_exists($email) {
        return $this->db->get_where('users', ['email' => $email])->num_rows() > 0;
    }

    public function getMatchDetailsById($match_id)
    {
        return $this->db->select("
                m.id AS match_id,
                s.match_date,
                t1.name AS team1_name,
                t1.short_name AS t1_short,
                t2.name AS team2_name,
                t2.short_name AS t2_short,
                v.name AS venue_name,
                m.lineup_status_id,
                ls.short_code AS lineup_status_code
            ")
            ->from('matches m')
            ->join('schedules s', 's.id = m.schedule_id', 'inner')
            ->join('teams t1', 't1.id = s.team_a_id', 'left')
            ->join('teams t2', 't2.id = s.team_b_id', 'left')
            ->join('venues v', 'v.id = s.venue_id', 'left')
            ->join('masters ls', 'ls.id = m.lineup_status_id AND ls.master_type_id = 4', 'left')
            ->where('m.id', $match_id)
            ->get()
            ->row_array();
    }
    
    public function insertUserDetails($data) {
        // Check if user_id already has a user_details entry
        $exists = $this->db->where('user_id', $data['user_id'])
                           ->get('user_details')
                           ->num_rows();
    
        if ($exists == 0) {
            $this->db->insert('user_details', $data);
            if ($this->db->affected_rows() > 0) {
                return $this->db->insert_id(); // return the newly inserted ID
            }
        }
    
        return false;
    }

    public function getActiveWalletTypes() {
        return $this->db->select('id as wallet_type_id, type_name as label')
                        ->from('wallet_types')
                        ->where('status_id', 6) // Assuming 6 = active
                        ->get()
                        ->result_array();
    }
    
    

    public function createDefaultWalletsForUser($user_id) {

        $wallet_types = $this->dozen->getActiveWalletTypes();
    
        $wallets = [];
        foreach ($wallet_types as $w) {
            $wallets[] = [
                'user_id'        => $user_id,
                'wallet_type_id' => $w['wallet_type_id'],
                'balance'        => 0.00
            ];
        }
    
        return $this->db->insert_batch('wallets', $wallets);
    }

    public function distributeReferralIncome($new_user_id, $referrer_id) {
        if (!$referrer_id) {
            // No referrer, check if self-income rule applies
            $levels = $this->getReferralIncomeLevels('direct'); // Only 1 level
            foreach ($levels as $row) {
                if ($row['level'] == 0 && $row['receiver_role'] === 'self') {
                    $this->creditWallet($new_user_id, 3, $row['income_amount'], 'Self Signup Bonus');
                }
            }
            return;
        }
    
        // Build upline chain: level 1 = direct, 2 = first upline, 3 = second upline
        $level_users = [];
    
        $referrer = $this->db->get_where('users', ['id' => $referrer_id])->row();
        if ($referrer) $level_users[] = $referrer;
    
        if ($referrer && $referrer->referred_by) {
            $first_upline = $this->db->get_where('users', ['id' => $referrer->referred_by])->row();
            if ($first_upline) $level_users[] = $first_upline;
    
            if ($first_upline && $first_upline->referred_by) {
                $second_upline = $this->db->get_where('users', ['id' => $first_upline->referred_by])->row();
                if ($second_upline) $level_users[] = $second_upline;
            }
        }
    
        // Determine referral case
        $referral_case = 'one_upline';
        if (count($level_users) == 2) $referral_case = 'one_upline';
        if (count($level_users) >= 3) $referral_case = 'two_uplines';
    
        // Fetch income levels
        $levels = $this->getReferralIncomeLevels($referral_case);
        foreach ($levels as $row) {
            $level = (int) $row['level'];
            $receiver_role = $row['receiver_role'];
            $amount = (float) $row['income_amount'];
    
            $wallet_type_id = 3; // Referral Wallet (assumed for all levels)
    
            if ($level === 0 && $receiver_role === 'self') {
                $this->creditWallet($new_user_id, $wallet_type_id, $amount, 'Self Signup Bonus');
            } elseif (isset($level_users[$level - 1])) {
                $receiver_id = $level_users[$level - 1]->id;
                $this->creditWallet($receiver_id, $wallet_type_id, $amount, "Level {$level} Referral Bonus from User #{$new_user_id}");
            }
        }
    }

    public function getReferralIncomeLevels($referral_case) {
        $this->db->where('referral_case', $referral_case);
        $this->db->where('status_id', 6); // Assuming 6 = Active
        $this->db->order_by('level', 'ASC');
        return $this->db->get('referral_income_levels')->result_array();
    }

    public function creditWallet($user_id, $wallet_type_id, $amount, $source = null) {
        // 1. Update wallet balance
        $this->db->set('balance', 'balance + ' . (float)$amount, FALSE);
        $this->db->where(['user_id' => $user_id, 'wallet_type_id' => $wallet_type_id]);
        $this->db->update('wallets');
    
    }

    public function applyReferralBonusSlab($referrer_id) {
        if (!$referrer_id) return;
    
        // Count direct referrals
        $this->db->where('referred_by', $referrer_id);
        $referral_count = $this->db->count_all_results('users');
    
        // Get highest matching slab where direct_referrals <= referral_count
        $this->db->where('direct_referrals <=', $referral_count);
        $this->db->order_by('direct_referrals', 'DESC');
        $slab = $this->db->get('referral_bonus_slabs')->row_array();
    
        // Apply bonus if a valid slab is found
        if ($slab) {
            $amount = (float) $slab['bonus_amount'];
            $wallet_type_id = 3; // Referral Wallet
            $this->creditWallet($referrer_id, $wallet_type_id, $amount, "Referral Slab Bonus for $referral_count referrals");
        }
    }
    
    // Get all contests for a specific match
    public function getContestsByMatchId($match_id)
    {
        return $this->db->select('
                id,
                match_id,
                prize_pool,
                prize_type,
                entry_fee,
                first_prize,
                total_spots,
                spots_left,
                winning_percent,
                max_teams,
                created_at,
                updated_at
            ')
            ->from('contests')
            ->where('match_id', $match_id)
            ->order_by('id', 'ASC')
            ->get()
            ->result_array();
    }
    
    public function hasUserTeamsForMatch($user_id, $match_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('match_id', $match_id);
        $query = $this->db->get('user_teams');
        return $query->num_rows() > 0;
    }
    
    public function getContestById($contest_id)
{
    return $this->db->get_where('contests', ['id' => $contest_id])->row_array();
}

public function getUpcomingMatches() {
    // For now, use mock data
    return [
        [
            'team1' => 'South Africa',
            'team2' => 'Australia',
            'type' => 'Test',
            'title' => 'World Test Championship Final',
            'time_left' => '6h : 17m',
            'lineups_out' => true,
            'team_count' => 1,
            'contest_count' => 0
        ]
    ];
}


public function saveGeneratedTeams($userId, $matchId, $finalTeams, $statusId = 1) {
    if (empty($finalTeams)) return false;

    $genTeamData = [];
    $teamIndex = 1;

    foreach ($finalTeams as $team) {
        $genTeamData["team{$teamIndex}"] = [
            'captain' => $team['captain_id'],
            'vice_captain' => $team['vice_captain_id'],
            'team_players' => array_column($team['players'], 'player_id')
        ];
        $teamIndex++;
    }

    $data = [
        'user_id' => $userId,
        'match_id' => $matchId,
        'players' => json_encode(array_column($finalTeams[0]['players'], 'player_id')), // store 1st team as base
        'captain_id' => $finalTeams[0]['captain_id'],
        'vice_captain_id' => $finalTeams[0]['vice_captain_id'],
        'gen_team_data' => json_encode($genTeamData),
        'status_id' => $statusId,
        'created_at' => date('Y-m-d H:i:s')
    ];

    return $this->db->insert('user_generated_teams', $data);
}


public function getUserData($user_id = null) {
    if ($user_id !== null) {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row_array(); // Return single user
    } else {
        $query = $this->db->get('users');
        return $query->result_array(); // Return all users
    }
}

public function countUserTeams($user_id, $match_id) {
    return $this->db->where(['user_id' => $user_id, 'match_id' => $match_id])->count_all_results('user_teams');
}

public function getUserTeamRow($user_id, $match_id) {
    return $this->db->where([
        'user_id' => $user_id,
        'match_id' => $match_id
    ])->get('user_teams')->row_array();
}

public function updateTeamJson($team_id, $json) {
    return $this->db->where('id', $team_id)->update('user_teams', [
        'team_data' => $json,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
}

public function insertCreatedTeam($data) {
    return $this->db->insert('user_teams', $data);
}




public function getUserTeams($user_id, $match_id) {
    return $this->db->get_where('user_teams', ['user_id' => $user_id, 'match_id' => $match_id])->result_array();
}

public function getAvailablePlayers($match_id) {
    $this->db->select('
        p.*, 
        pt.team_id, 
        t.name AS team_name, 
        r.short_name AS player_role,
        ptm.pitch_type_id,
        ptc.type_name AS pitch_type_name,
        bh.short_code AS batting_hand_code,
        bh.name AS batting_hand_name,
        blh.short_code AS bowling_hand_code,
        blh.name AS bowling_hand_name
    ');
    $this->db->from('matches m');
    $this->db->join('schedules s', 's.id = m.schedule_id');
    $this->db->join('teams t1', 't1.id = s.team_a_id');
    $this->db->join('teams t2', 't2.id = s.team_b_id');
    $this->db->join('player_team pt', 'pt.team_id = t1.id OR pt.team_id = t2.id');
    $this->db->join('players p', 'p.id = pt.player_id');
    $this->db->join('teams t', 't.id = pt.team_id');
    $this->db->join('roles r', 'r.id = pt.role_id', 'left');
    $this->db->join('pitch_type_ability_map ptm', 'ptm.id = p.special_ability_id', 'left');
    $this->db->join('pitch_types ptc', 'ptc.id = ptm.pitch_type_id', 'left');

    // ✅ Hand Type Mapping using masters (master_type_id = 9)
    $this->db->join('masters bh', 'bh.id = p.batting_hand_id AND bh.master_type_id = 9', 'left');
    $this->db->join('masters blh', 'blh.id = p.bowling_hand_id AND blh.master_type_id = 9', 'left');

    $this->db->where('m.id', $match_id);
    $this->db->where('p.status_id', 6); // Only fit players
    $this->db->group_by('p.id');

    return $this->db->get()->result();
}

public function insertUserContestTeams($user_id, $match_id, $contest_id, $team_keys = [])
{
    if (empty($team_keys)) {
        return ['status' => 'error', 'message' => 'No teams selected.'];
    }

    // Remove "team" prefix and sanitize
    $new_ids = array_map(function ($key) {
        return preg_replace('/^team/i', '', $key);
    }, $team_keys);
    $new_ids = array_filter($new_ids, fn($id) => is_numeric($id));
    $new_ids = array_unique($new_ids);

    $num_to_add = count($new_ids);
    if ($num_to_add === 0) {
        return ['status' => 'error', 'message' => 'Invalid team keys.'];
    }

    // Get contest info
    $contest = $this->db->where('id', $contest_id)->get('contests')->row();
    if (!$contest) {
        return ['status' => 'error', 'message' => 'Invalid contest.'];
    }

    // Check spots availability
    if ($contest->spots_left < $num_to_add) {
        return ['status' => 'error', 'message' => 'Not enough spots left in the contest.'];
    }

    // Check user wallet (Main Wallet)
    $wallet = $this->db->get_where('wallets', [
        'user_id' => $user_id,
        'wallet_type_id' => 1 // Main Wallet
    ])->row();

    $totalAmount = $num_to_add * floatval($contest->entry_fee);
    if (!$wallet || floatval($wallet->balance) < $totalAmount) {
        return ['status' => 'error', 'message' => 'Insufficient balance in main wallet.'];
    }

    // Fetch existing record
    $existing = $this->db->where([
        'user_id' => $user_id,
        'match_id' => $match_id,
        'contest_id' => $contest_id
    ])->get('user_contest_teams')->row();

    if ($existing) {
        $existing_ids = array_filter(array_map('trim', explode(',', $existing->joined_teams)));

        foreach ($new_ids as $id) {
            if (in_array($id, $existing_ids)) {
                return ['status' => 'error', 'message' => "Team ID $id already joined. Please choose different teams."];
            }
        }

        $merged = array_unique(array_merge($existing_ids, $new_ids));
        if (count($merged) > 20) {
            return ['status' => 'error', 'message' => 'Cannot join more than 20 teams in this contest.'];
        }

        // Deduct wallet and insert transaction
        $this->db->trans_start();

        // Deduct wallet
        $this->db->set('balance', 'balance - ' . $totalAmount, false)
            ->where('user_id', $user_id)
            ->where('wallet_type_id', 1)
            ->update('wallets');

        // Wallet transaction
        $this->db->insert('wallet_transactions', [
            'wallet_type_id' => 1,
            'user_id' => $user_id,
            'type' => 'debit',
            'amount' => $totalAmount,
            'source' => 'Contest Join #' . $contest_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Update joined teams
        $this->db->where('id', $existing->id)->update('user_contest_teams', [
            'joined_teams' => implode(',', $merged),
            'teams_count' => count($merged),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Update contest spots
        $this->db->set('spots_left', 'spots_left - ' . $num_to_add, false)
            ->where('id', $contest_id)
            ->update('contests');

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return ['status' => 'success', 'joined_count' => count($merged), 'message' => 'Contest updated with new teams.'];
        } else {
            return ['status' => 'error', 'message' => 'Something went wrong while joining contest.'];
        }
    }

    // Insert new
    $this->db->trans_start();

    // Deduct wallet
    $this->db->set('balance', 'balance - ' . $totalAmount, false)
        ->where('user_id', $user_id)
        ->where('wallet_type_id', 1)
        ->update('wallets');

    $this->db->insert('wallet_transactions', [
        'wallet_type_id' => 1,
        'user_id' => $user_id,
        'type' => 'debit',
        'amount' => $totalAmount,
        'source' => 'Contest Join #' . $contest_id,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    $this->db->insert('user_contest_teams', [
        'user_id' => $user_id,
        'match_id' => $match_id,
        'contest_id' => $contest_id,
        'joined_teams' => implode(',', $new_ids),
        'teams_count' => $num_to_add,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->db->set('spots_left', 'spots_left - ' . $num_to_add, false)
        ->where('id', $contest_id)
        ->update('contests');

    $this->db->trans_complete();

    if ($this->db->trans_status()) {
        return ['status' => 'success', 'joined_count' => $num_to_add, 'message' => 'Contest joined successfully.'];
    }

    return ['status' => 'error', 'message' => 'Could not join contest.'];
}




public function getMatchShortNames($match_id) {
    $this->db->select('t1.short_name as team1_short, t2.short_name as team2_short');
    $this->db->from('matches m');
    $this->db->join('schedules s', 's.id = m.schedule_id');
    $this->db->join('teams t1', 't1.id = s.team_a_id');
    $this->db->join('teams t2', 't2.id = s.team_b_id');
    $this->db->where('m.id', $match_id);
    return $this->db->get()->row_array();
}
public function getMatchTopBarDetails($match_id)
{
    return $this->db->select("
            s.id as schedule_id,
            t1.short_name as team1_short,
            t2.short_name as team2_short,
            v.name as pitch_name,
            pt.type_name as pitch_type
        ")
        ->from('matches m')
        ->join('schedules s', 's.id = m.schedule_id')
        ->join('teams t1', 't1.id = s.team_a_id')
        ->join('teams t2', 't2.id = s.team_b_id')
        ->join('venues v', 'v.id = s.venue_id', 'left')
        ->join('venue_pitch_types vpt', 'vpt.venue_id = v.id', 'left')
        ->join('pitch_types pt', 'pt.id = vpt.pitch_type_id', 'left')
        ->where('m.id', $match_id)
        ->get()
        ->row_array();
}







public function getReferralsByUser($user_id) {
    return $this->db->select('u.id, u.name, r.level, r.amount, r.referred_user_id, u2.name as referred_name')
        ->from('referral_earnings r')
        ->join('users u', 'u.id = r.user_id')
        ->join('users u2', 'u2.id = r.referred_user_id')
        ->where('r.user_id', $user_id)
        ->order_by('r.level', 'ASC')
        ->get()->result();
}


public function getUserProfile($user_id)
{
    $this->db->select('u.*, ud.*')
             ->from('users u')
             ->join('user_details ud', 'u.id = ud.user_id', 'left')
             ->where('u.id', $user_id);
    return $this->db->get()->row_array();
}

public function updateUserDetails($user_id, $data)
{
    $this->db->where('user_id', $user_id);
    return $this->db->update('user_details', $data);
}

// Get wallets by user (for 'user' role)
public function getWalletsByUser($user_id) {
    return $this->db
        ->select('w.balance, wt.type_name AS type')
        ->from('wallets w')
        ->join('wallet_types wt', 'wt.id = w.wallet_type_id', 'left')
        ->where('w.user_id', $user_id)
        ->where('wt.status_id', 6)
        ->get()
        ->result_array();
}

// Get pending crons count for today (for 'commando' and 'tester')
public function getTodayPendingCronsCount() {
    return $this->db
        ->where('DATE(created_at)', date('Y-m-d'))
        ->where('status_id', 1) // 1 = Pending
        ->count_all_results('crons');
}

public function getTotalVenues() {
    return $this->db->count_all('venues');
}

public function getPairIncome() {
    $result = $this->db
        ->select_sum('income_amount')
        ->get('pair_income_history')
        ->row();

    return $result->income_amount ? number_format($result->income_amount, 2) : '0.00';
}

public function getReferralIncome($type = 'direct') {
    $source = ($type === 'direct') ? 'referral_direct' : 'referral_indirect';

    $result = $this->db
        ->select_sum('amount')
        ->where('type', 'credit')
        ->where('source', $source)
        ->get('wallet_transactions')
        ->row();

    return $result->amount ? number_format($result->amount, 2) : '0.00';
}


public function getTotalUsers() {
    return $this->db->count_all('users');
}

public function getTotalInvestments() {
    $result = $this->db
        ->select_sum('amount')
        ->where('type', 'credit')
        ->where('source', 'investment')
        ->get('wallet_transactions')
        ->row();

    return $result->amount ? number_format($result->amount, 2) : '0.00';
}

public function getTotalGain() {
    $result = $this->db
        ->select_sum('amount')
        ->where('type', 'credit')
        ->where('source', 'gain')
        ->get('wallet_transactions')
        ->row();

    return $result->amount ? number_format($result->amount, 2) : '0.00';
}


// Basic matches for dropdowns
public function getAllMatches() {
    $this->db->select('s.id, s.match_date, ta.name as team_a, tb.name as team_b');
    $this->db->from('schedules s');
    $this->db->join('teams ta', 'ta.id = s.team_a_id', 'left');
    $this->db->join('teams tb', 'tb.id = s.team_b_id', 'left');
    $this->db->order_by('s.match_date', 'DESC');
    return $this->db->get()->result_array();
}

// Full matches with contest preview for all_matches page
public function getAllMatchesWithContests() {
    $this->db->select('
        matches.id AS match_id,
        team1.name AS team1_name,
        team2.name AS team2_name,
        schedules.match_date,
        venues.name AS venue,
        contests.prize_pool,
        contests.prize_type,
        contests.entry_fee,
        contests.first_prize,
        contests.total_spots,
        contests.spots_left,
        lineup_status.name AS lineup_status_name,
        lineup_status.short_code AS lineup_status_code
    ');
    $this->db->from('matches');
    $this->db->join('schedules', 'schedules.id = matches.schedule_id');
    $this->db->join('teams AS team1', 'team1.id = schedules.team_a_id');
    $this->db->join('teams AS team2', 'team2.id = schedules.team_b_id');
    $this->db->join('venues', 'venues.id = schedules.venue_id', 'left');
    $this->db->join('contests', 'contests.match_id = matches.id', 'left');
    $this->db->join('masters AS lineup_status', 'lineup_status.id = matches.lineup_status_id AND lineup_status.master_type_id = 4', 'left');
    $this->db->group_by('matches.id');
    $this->db->order_by('schedules.match_date', 'ASC');
    return $this->db->get()->result_array();
}


public function insertContest($data) {
    $this->db->insert('contests', $data);
}

public function getPrizeBreakup($contest_id)
{
    $contest = $this->getContestById($contest_id);
    if (!$contest) return [];

    // Prize pool multiplier
    switch (strtolower($contest['prize_type'])) {
        case 'crores':    $multiplier = 10000000; break;
        case 'lakhs':     $multiplier = 100000; break;
        case 'thousands': $multiplier = 1000; break;
        default:          $multiplier = 1; break;
    }

    $entry_fee     = (float) $contest['entry_fee'];
    $prize_pool    = floor($contest['prize_pool'] * $multiplier);
    $total_spots   = (int) $contest['total_spots'];
    $winning_pct   = (float) $contest['winning_percent'];

    $total_winners = floor($total_spots * ($winning_pct / 100));
    if ($total_winners < 10 || $prize_pool <= 0) return [];

    // Fetch levels
    $this->db->where('status_id', 6);
    $this->db->order_by('level', 'ASC');
    $levels = $this->db->get('prize_breakup_levels')->result_array();

    $breakup = [];
    $current_rank = 1;

    foreach ($levels as $level) {
        $type = $level['calculation_type'];
        $pct = (float) $level['percentage'];
        $desc = $level['description'];

        // Levels 1 to 5 are fixed single winners
        if ($level['level'] >= 1 && $level['level'] <= 5) {
            $winners = 1;

            if ($type == 'pool') {
                $total_prize = round(($pct / 100) * $prize_pool);
                $prize_per_user = $total_prize;
            } else {
                $prize_per_user = round(($pct / 100) * $entry_fee, 2);
                $total_prize = $prize_per_user;
            }

            $breakup[] = [
                'level'           => $level['level'],
                'description'     => $desc,
                'start_rank'      => $current_rank,
                'end_rank'        => $current_rank,
                'winners'         => $winners,
                'prize_per_user'  => $prize_per_user,
                'total'           => $total_prize
            ];
            $current_rank++;
            continue;
        }

        // Now handle range-based levels
        if (preg_match('/(\d+(?:\.\d+)?)\s*[-–]\s*(\d+(?:\.\d+)?)/', $level['winner_percent_range'], $match)) {
            $start_percent = (float)$match[1];
            $end_percent   = (float)$match[2];
        } else {
            continue; // Skip invalid range
        }

        $start_rank = $current_rank;
        $end_rank   = min(floor(($end_percent / 100) * $total_winners), $total_winners);
        $winners    = max(0, $end_rank - $start_rank + 1);

        if ($winners <= 0) continue;

        if ($type == 'pool') {
            $total_prize    = round(($pct / 100) * $prize_pool);
            $prize_per_user = round($total_prize / $winners, 2);
        } else {
            $prize_per_user = round(($pct / 100) * $entry_fee, 2);
            $total_prize    = round($prize_per_user * $winners, 2);
        }

        $breakup[] = [
            'level'           => $level['level'],
            'description'     => $desc,
            'start_rank'      => $start_rank,
            'end_rank'        => $end_rank,
            'winners'         => $winners,
            'prize_per_user'  => $prize_per_user,
            'total'           => $total_prize
        ];

        $current_rank = $end_rank + 1;
    }

    return $breakup;
}



public function getUserImage($user_id)
{
    $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
    return $user['image'] ?? null;
}




}
