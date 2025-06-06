<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DozenDreams_model extends CI_Model {

    /**
     * Get all players.
     * @return array
     */
    public function getAllPlayers() {
        return $this->db->get('players')->result();
    }

    /**
     * Get players by their IDs.
     * @param array $ids
     * @return array
     */
    public function getPlayersByIds(array $ids) {
        return $this->db->where_in('id', $ids)->get('players')->result_array();
    }

    /**
     * Get last 5 match stats for a player.
     * @param int $playerId
     * @return array
     */
    public function getPlayerMatchStats(int $playerId) {
        return $this->db->where('player_id', $playerId)
            ->order_by('match_date', 'DESC')
            ->limit(5)
            ->get('player_match_stats')
            ->result_array();
    }

    /**
     * Get all dream11 points records.
     * @return array
     */
    public function getAllPoints() {
        return $this->db->order_by('id', 'DESC')->get('dream11_points')->result_array();
    }

    /**
     * Get a dream11 point record by ID.
     * @param int $id
     * @return array|null
     */
    public function getPoint(int $id) {
        return $this->db->get_where('dream11_points', ['id' => $id])->row_array();
    }

    /**
     * Insert a new dream11 point record.
     * @param array $data
     * @return bool
     */
    public function insertPoint(array $data): bool {
        return $this->db->insert('dream11_points', $data);
    }

    /**
     * Update a dream11 point record by ID.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePoint(int $id, array $data): bool {
        return $this->db->where('id', $id)->update('dream11_points', $data);
    }

    /**
     * Get all player statistics with player names.
     * @return array
     */
    public function getAllPlayerStatisticsWithPlayerNames() {
        $this->db->select('ps.*, p.name as player_name')
            ->from('player_statistics ps')
            ->join('players p', 'p.id = ps.player_id');
        return $this->db->get()->result_array();
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
     * Get all schedules with related tournament and team names.
     * @return array
     */
    public function getAllSchedulesWithNames() {
        $this->db->select('s.*, t.name as tournament_name, ta.name as team_a_name, tb.name as team_b_name')
            ->from('schedules s')
            ->join('tournaments t', 't.id = s.tournament_id')
            ->join('teams ta', 'ta.id = s.team_a_id')
            ->join('teams tb', 'tb.id = s.team_b_id');
        return $this->db->get()->result_array();
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
     * Get all tournaments.
     * @return array
     */
    public function getAllTournaments() {
        return $this->db->get('tournaments')->result_array();
    }

    /**
     * Get tournament by short name.
     * @param string $shortName
     * @return array|null
     */
    public function getTournamentByShortName(string $shortName) {
        return $this->db->get_where('tournaments', ['short_name' => $shortName])->row_array();
    }

    /**
     * Get all teams.
     * @return array
     */
    public function getAllTeams() {
        return $this->db->get('teams')->result_array();
    }

    /**
     * Get team by short name.
     * @param string $shortName
     * @return array|null
     */
    public function getTeamByShortName(string $shortName) {
        return $this->db->get_where('teams', ['short_name' => $shortName])->row_array();
    }

    /**
     * Get team by ID.
     * @param int $id
     * @return array|null
     */
    public function getTeamById(int $id) {
        return $this->db->get_where('teams', ['id' => $id])->row_array();
    }

    /**
     * Insert a team.
     * @param array $data
     * @return bool
     */
    public function insertTeam(array $data): bool {
        return $this->db->insert('teams', $data);
    }

    /**
     * Update a team by ID.
     * @param int $id
     * @param array $data
     * @return bool
     */
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
        return $this->db->limit($limit, $start)->get('venues')->result_array();
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
    public function updateVenue(int $id, array $data): bool {
        return $this->db->where('id', $id)->update('venues', $data);
    }

    /**
     * Get venue by ID.
     * @param int $id
     * @return array|null
     */
    public function getVenueById(int $id) {
        return $this->db->get_where('venues', ['id' => $id])->row_array();
    }

    /**
     * Get all pitch types.
     * @return array
     */
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

    
}
