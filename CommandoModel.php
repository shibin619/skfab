<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class CommandoModel extends CI_Model {

    public function getUpcomingMatches() {
        $this->db->select('m.id, t1.name as team1_name, t2.name as team2_name, s.match_date, v.name as venue');
        $this->db->from('matches m');
        $this->db->join('schedules s', 's.id = m.schedule_id');
        $this->db->join('teams t1', 't1.id = s.team_a_id');
        $this->db->join('teams t2', 't2.id = s.team_b_id');
        $this->db->join('venues v', 'v.id = s.venue_id', 'left');
        $this->db->where('DATE(s.match_date) >= CURDATE()');
        $this->db->order_by('s.match_date', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    

}