<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tester extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dozen_model', 'dozen');
        $this->load->database();
    }

    public function index() {
        echo "<h2>🌲 Dream Tree Tester</h2><hr>";
        $this->runTreeSimulation();
    }

    public function runTreeSimulation() {
        echo "<h4>🔁 Initializing test tree...</h4><pre>";

        // Clear dream_tree
        $this->db->truncate('dream_tree');
        echo "✅ dream_tree cleared.\n";

        // Initialize root user
        $this->dozen->initializeTreeIfEmpty(1);
        echo "🌱 Root user inserted: 1\n";

        // Simulate users 2 to 15 filling up full binary tree (up to 4 levels)
        for ($i = 2; $i <= 15; $i++) {
            $this->dozen->insertUserToTree($i);
            echo "➕ Inserted user {$i} in tree.\n";
        }

        // Now, test 5th level insertions
        echo "\n🔎 Testing 5th-level insertions:\n";

        $testUsers = [
            ['user_id' => 16, 'expected_parent' => 8, 'expected_pos' => 'left'],
            ['user_id' => 17, 'expected_parent' => 8, 'expected_pos' => 'right'],
            ['user_id' => 18, 'expected_parent' => 9, 'expected_pos' => 'left'],
        ];

        foreach ($testUsers as $user) {
            $this->dozen->insertUserToTree($user['user_id']);
            $this->assertUserInTree($user['user_id'], $user['expected_parent'], $user['expected_pos']);
        }

        echo "\n✅ Tree simulation complete.\n</pre>";
    }

    private function assertUserInTree($user_id, $expected_parent_id, $expected_position) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('dream_tree');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            if ($row->parent_id == $expected_parent_id && $row->position == $expected_position) {
                echo "✅ User {$user_id} correctly placed under {$expected_parent_id} as '{$expected_position}'\n";
            } else {
                echo "❌ User {$user_id} placed incorrectly. Expected parent: {$expected_parent_id}, position: {$expected_position}, got parent: {$row->parent_id}, position: {$row->position}\n";
            }
        } else {
            echo "❌ User {$user_id} not found in dream_tree.\n";
        }
    }

    public function showTree()
    {
        echo "<pre>"; // preserve spacing
        $this->dozen->printTree(); // visualize from root
        echo "</pre>";
    }

    
    public function viewTree()
    {
        $data['tree'] = $this->dozen->buildHtmlTree();
        $this->load->view('dozendreams/tree_view', $data);
    }


}
