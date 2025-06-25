<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class TesterModel extends CI_Model {

    public function initializeTreeIfEmpty($root_user_id) {
        $count = $this->db->count_all('dream_tree');
        if ($count == 0) {
            $this->db->insert('dream_tree', [
                'user_id' => $root_user_id,
                'parent_id' => null,
                'position' => 'root',
                'level' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    public function insertUserToTree($user_id) {
        $parent_id = $this->dozen->findGlobalAvailableParent();
        if ($parent_id) {
            $position = $this->dozen->getAvailableTreePosition($parent_id);
            $level = $this->dozen->getTreeLevel($parent_id) + 1;
    
            return $this->dozen->addToTree([
                'user_id'   => $user_id,
                'parent_id' => $parent_id,
                'position'  => $position,
                'level'     => $level
            ]);
        }
        return false;
    }

    public function printTree($user_id = null, $prefix = '')
    {
        // If user_id is null, start from root
        if ($user_id === null) {
            $this->db->where('level', 0);
            $root = $this->db->get('dream_tree')->row();
            if (!$root) {
                echo "🌱 Tree is empty.\n";
                return;
            }
            $user_id = $root->user_id;
        }

        // Get current node
        $this->db->where('user_id', $user_id);
        $node = $this->db->get('dream_tree')->row();

        if (!$node) return;

        // Print current node
        echo $prefix . "👤 User ID: {$node->user_id} (Level {$node->level}, Position: " . ($node->position ?? 'root') . ")\n";

        // Get left and right children
        $this->db->where('parent_id', $user_id);
        $this->db->order_by('position', 'ASC'); // left first
        $children = $this->db->get('dream_tree')->result();

        foreach ($children as $child) {
            $new_prefix = $prefix . '    '; // indent
            $this->printTree($child->user_id, $new_prefix);
        }
    }

    public function buildHtmlTree($user_id = null)
    {
        if ($user_id === null) {
            $this->db->where('level', 0);
            $root = $this->db->get('dream_tree')->row();
            if (!$root) return '<li>🌱 Tree is empty.</li>';
            $user_id = $root->user_id;
        }
    
        // Get user and tree data
        $this->db->select('dt.*, u.name, u.email'); // join more fields as needed
        $this->db->from('dream_tree dt');
        $this->db->join('users u', 'u.id = dt.user_id');
        $this->db->where('dt.user_id', $user_id);
        $node = $this->db->get()->row();
    
        if (!$node) return '';
    
        // Node HTML with tooltip
        $html = '<li>';
        $html .= '<span class="tooltip">';
        $html .= "👤 <strong>{$node->user_id}</strong> ({$node->name})";
        $html .= '<span class="tooltiptext">';
        $html .= "Name: {$node->name}<br>Email: {$node->email}<br>Level: {$node->level}<br>Position: " . ($node->position ?? 'root');
        $html .= '</span></span>';
    
        // Get children
        $this->db->where('parent_id', $user_id);
        $this->db->order_by('position', 'ASC');
        $children = $this->db->get('dream_tree')->result();
    
        if (!empty($children)) {
            $html .= '<ul>';
            foreach ($children as $child) {
                $html .= $this->buildHtmlTree($child->user_id);
            }
            $html .= '</ul>';
        }
    
        $html .= '</li>';
    
        return $html;
    }
    


    

}