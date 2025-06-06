<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DozenDreams extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('DozenDreams_model', 'dozen');
        $this->load->helper(array('form', 'url'));
        $this->load->library(['form_validation', 'session','pagination']);

    }

    // Load the player selection page
    public function index() {
        // Load CodeIgniter cache driver (if not autoloaded)
        $this->load->driver('cache');

        // Try to get cached player list
        $players = $this->cache->file->get('cachedPlayerList');

        // If cache is empty or expired, fetch from database and store in cache
        if ($players === false) {
            // Fetch all players from the database via model
            $players = $this->dozen->getAllPlayers();

            // Save result to cache for 10 minutes (600 seconds)
            $this->cache->file->save('cachedPlayerList', $players, 600);
        }

        // Pass player data to view
        $data['players'] = $players;

        // Load the player selection view
        $this->load->view('dozendreams/player_list', $data);
    }

    // Generate valid team combinations based on user input
    public function generate() {
        $selectedPlayers = $this->input->post('players');          // User-selected player IDs
        $teamLimit = intval($this->input->post('limit'));          // Max teams to show
        $selectedCaptain = $this->input->post('captain');          // User-selected Captain ID
        $selectedViceCaptain = $this->input->post('vice_captain'); // User-selected Vice-Captain ID
        $pitchType = $this->input->post('pitch_type');             // Pitch type selected for the match
        $opponentTeamId = $this->input->post('opponent_team');     // Opponent team ID
        $weatherCondition = $this->input->post('weather');         // Weather condition for match
    
        if (!is_array($selectedPlayers)) $selectedPlayers = [];
    
        // Max allowed players based on captain/VC selection
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
    
        // Fetch all players from DB
        $allPlayers = $this->dozen->get_all_players();
    
        // In-memory cache for form scores to avoid recalculating
        $formScoreCache = [];
    
        // Define form threshold
        $formThreshold = 50;  // adjust as needed
    
        // Filter players based on multiple criteria
        $filteredPlayers = [];
        foreach ($allPlayers as $player) {
            // Calculate or fetch cached form score
            if (!isset($formScoreCache[$player['id']])) {
                $formScoreCache[$player['id']] = $this->calculatePlayerForm($player['id']);
            }
            $formScore = $formScoreCache[$player['id']];
    
            // Check player fitness / injury status
            $isFit = $this->dozen->isPlayerFit($player['id']);
            if (!$isFit) continue;
    
            // Check opponent matchup stats (e.g., player performs well against opponent)
            $goodMatchup = $this->dozen->hasGoodOpponentStats($player['id'], $opponentTeamId);
            if (!$goodMatchup) continue;
    
            // Check pitch compatibility with player's special ability / type
            $pitchCompatible = $this->dozen->isPitchCompatible($player['special_ability'], $pitchType);
            if (!$pitchCompatible) continue;
    
            // Weather influence could also be checked similarly (optional)
            // if (!$this->dozen->isWeatherSuitable($player['id'], $weatherCondition)) continue;
    
            if ($formScore >= $formThreshold) {
                $filteredPlayers[] = $player;
                $player['form_score'] = $formScore; // store for later use
            }
        }
    
        // Ensure all selected players meet form and filters
        $filteredPlayerIds = array_column($filteredPlayers, 'id');
        foreach ($selectedPlayers as $pid) {
            if (!in_array($pid, $filteredPlayerIds)) {
                echo "Selected player ID $pid does not meet the form or fitness criteria.";
                return;
            }
        }
    
        // Build player map with form scores
        $playerMap = [];
        foreach ($filteredPlayers as $player) {
            $playerMap[$player['id']] = (object)$player;
        }
    
        // Remaining pool excluding selected players
        $remainingPool = array_values(array_diff($filteredPlayerIds, $selectedPlayers));
        $playersNeeded = 11 - count($selectedPlayers);
    
        // Generate combinations from filtered remaining pool
        $extraCombinations = $this->generateCombinations($remainingPool, $playersNeeded);
    
        $finalTeams = [];
    
        // Team composition constraints (max 7 from one team)
        $maxPlayersFromOneTeam = 7;
    
        foreach ($extraCombinations as $extras) {
            $teamIds = array_merge($selectedPlayers, $extras);
    
            $teamPlayers = array_map(fn($id) => $playerMap[$id], $teamIds);
    
            // Count roles and team distribution
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
    
            // Check team points limit
            if ($totalPoints > 100) continue;
    
            // Check if all roles are present (no zero)
            if (in_array(0, $roleCounts)) continue;
    
            // Check max players from one team constraint
            if (max($teamCount) > $maxPlayersFromOneTeam) continue;
    
            $hasCaptain = $selectedCaptain && in_array($selectedCaptain, $teamIds);
            $hasViceCaptain = $selectedViceCaptain && in_array($selectedViceCaptain, $teamIds);
    
            if ($hasCaptain && $hasViceCaptain && $selectedCaptain != $selectedViceCaptain) {
                $finalTeams[] = [
                    'players' => $teamPlayers,
                    'captain' => $playerMap[$selectedCaptain]->name,
                    'vice_captain' => $playerMap[$selectedViceCaptain]->name,
                    'total_points' => $totalPoints,
                    'total_form_score' => $totalFormScore,
                ];
            } else {
                // Generate all captain-vice captain combos if none selected
                for ($i = 0; $i < count($teamPlayers); $i++) {
                    for ($j = 0; $j < count($teamPlayers); $j++) {
                        if ($i == $j) continue;
                        $finalTeams[] = [
                            'players' => $teamPlayers,
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
    
        // Sort by total form score then points descending
        usort($finalTeams, fn($a, $b) => 
            $b['total_form_score'] <=> $a['total_form_score'] ?: $b['total_points'] <=> $a['total_points']
        );
    
        $finalTeams = array_slice($finalTeams, 0, $teamLimit);
    
        $data['combinations'] = $finalTeams;
        $this->load->view('dozendreams/combinations', $data);
    }

    /**
     * Generate all combinations of $k players from $arr
     * 
     * @param array $arr  Array of player IDs to choose from
     * @param int   $k    Number of players needed in a team
     * @return array      All valid combinations
     */
    private function generateCombinations($arr, $k) {
        $result = [];
        $this->combineRecursive($arr, $k, 0, [], $result); // Start recursive combination generation
        return $result; // Return all generated combinations
    }

    /**
     * Recursive function to generate combinations
     * 
     * @param array $arr       Input array to generate from
     * @param int   $k         Target number of elements per combination
     * @param int   $index     Current index in recursion
     * @param array $current   Current combination being built
     * @param array &$result   Reference to final result array
     */
    private function combineRecursive($arr, $k, $index, $current, &$result) {
        // If the current combination has reached required size, save it
        if (count($current) == $k) {
            $result[] = $current;
            return;
        }

        // Loop from current index to end of array
        for ($i = $index; $i < count($arr); $i++) {
            $newCurrent = $current;           // Clone current state
            $newCurrent[] = $arr[$i];         // Add next element to combination
            $this->combineRecursive($arr, $k, $i + 1, $newCurrent, $result); // Recurse with updated state

            // Break early if result grows too large to avoid memory overflow
            if (count($result) > 10000) break;
        }
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
    
    /**
     * Calculate player's form score based on performance metrics
     * @param int $playerId
     * @return float $formScore
     */
    public function calculatePlayerForm($playerId, $pitchType = null, $opponentTeamId = null) {
        // Try getting cached form score first (Redis or file cache)
        $cacheKey = "player_form_{$playerId}_{$pitchType}_{$opponentTeamId}";
        $cachedScore = $this->cache->get($cacheKey);  // Implement cache->get and cache->set
        if ($cachedScore !== false) {
            return $cachedScore;
        }
    
        // Load player's match stats
        $matchStats = $this->dozen->getPlayerMatchStats($playerId);
        if (empty($matchStats)) return 0;
    
        // Load player's details
        $player = $this->dozen->getPlayerById($playerId);
    
        // Check injury - exclude injured players
        if (!empty($player->fitness_status)) {
            return 0;
        }
    
        $totalRuns = 0; $totalWickets = 0; $totalStrikeRate = 0; $totalEconomy = 0; $totalImpactScore = 0;
        $matchesPlayed = count($matchStats);
    
        foreach ($matchStats as $match) {
            $runs = (int)$match['runs'];
            $wickets = (int)$match['wickets'];
            $ballsFaced = max((int)$match['balls_faced'], 1);
            $oversBowled = max((float)$match['overs_bowled'], 0.1);
            $runsConceded = (int)$match['runs_conceded'];
    
            $strikeRate = ($runs / $ballsFaced) * 100;
            $economyRate = $runsConceded / $oversBowled;
            $impactScore = ($runs * 0.5) + ($wickets * 20) - ($economyRate * 2);
    
            // Weight for opponent matchup - example:
            $oppFactor = 1;
            if ($opponentTeamId) {
                $matchup = $this->dozen->getPlayerOpponentStats($playerId, $opponentTeamId);
                if (!empty($matchup)) {
                    // Increase factor if player performs well vs this opponent
                    $avgRunsVsOpp = $matchup['avg_runs'] ?? 0;
                    $avgWicketsVsOpp = $matchup['avg_wickets'] ?? 0;
                    $oppFactor += ($avgRunsVsOpp * 0.01) + ($avgWicketsVsOpp * 0.1);
                }
            }
    
            $totalRuns += $runs;
            $totalWickets += $wickets;
            $totalStrikeRate += $strikeRate;
            $totalEconomy += $economyRate;
            $totalImpactScore += $impactScore * $oppFactor;
        }
    
        $avgRuns = $totalRuns / $matchesPlayed;
        $avgWickets = $totalWickets / $matchesPlayed;
        $avgStrikeRate = $totalStrikeRate / $matchesPlayed;
        $avgEconomy = $totalEconomy / $matchesPlayed;
        $avgImpactScore = $totalImpactScore / $matchesPlayed;
    
        // Pitch compatibility multiplier
        $pitchMultiplier = 1;
        if ($pitchType && !empty($player->special_ability)) {
            $compatibleAbilities = $this->getPreferredAbilitiesForPitch($pitchType);
            if (in_array($player->special_ability, $compatibleAbilities)) {
                $pitchMultiplier = 1.2;  // boost form score by 20% if compatible
            } else {
                $pitchMultiplier = 0.85; // penalize if incompatible
            }
        }
    
        // Dynamic form threshold adjustment can be done here if needed, skipped now
    
        $formScore = (
            ($avgRuns * 0.4) +
            ($avgWickets * 15) +
            ($avgStrikeRate * 0.1) +
            ($avgImpactScore * 0.5) -
            ($avgEconomy * 1)
        ) * $pitchMultiplier;
    
        // Cache the result for 5 minutes (300 seconds)
        $this->cache->set($cacheKey, round($formScore, 2), 300);
    
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
    

    public function pointsList() {
        $data['points'] = $this->dozen->getAllPoints(); 
        $this->load->view('dozendreams/points_list', $data);
    }
    
    public function pointsAdd() {
        if ($_POST) {
            $this->form_validation->set_rules('action', 'Action', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('role_id', 'Role', 'required|in_list[1,2,3,4]');
            $this->form_validation->set_rules('points', 'Points', 'required|numeric');
    
            if ($this->form_validation->run() == TRUE) {
                $insertData = [
                    'action' => $this->input->post('action'),
                    'role_id' => $this->input->post('role_id'),
                    'points' => $this->input->post('points')
                ];
                $this->dozen->insertPoint($insertData); // Renamed to camelCase
                $this->session->set_flashdata('success', 'Point rule added successfully.');
                redirect('dozendreams/pointsList'); // Updated route to match camelCase method
            }
        }
        $this->load->view('dozendreams/points_add');
    }
    
    public function pointsEdit($id) {
        $data['point'] = $this->dozen->getPoint($id);
        if (!$data['point']) show_404();
    
        if ($_POST) {
            $this->form_validation->set_rules('action', 'Action', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('role_id', 'Role', 'required|in_list[1,2,3,4]');
            $this->form_validation->set_rules('points', 'Points', 'required|numeric');
    
            if ($this->form_validation->run() == TRUE) {
                $updateData = [
                    'action' => $this->input->post('action'),
                    'role_id' => $this->input->post('role_id'),
                    'points' => $this->input->post('points')
                ];
                $this->dozen->updatePoint($id, $updateData); // Renamed to camelCase
                $this->session->set_flashdata('success', 'Point rule updated successfully.');
                redirect('dozendreams/points_ist'); // Updated route to match camelCase method
            }
        }
    
        $this->load->view('dozendreams/points_edit', $data);
    }
        
    // Load the list of player statistics
    public function playerStatisticsList() {
        // Fetch all player statistics joined with player names
        $data['playersStats'] = $this->dozen->getAllPlayerStatisticsWithPlayerNames();
        $this->load->view('dozendreams/playerstatistics_list', $data);
    }

    // Load the add form for player statistics
    public function playerStatisticsAdd() {
        // Get all players for the dropdown selection
        $data['players'] = $this->dozen->getAllPlayers();
        $this->load->view('dozendreams/playerstatistics_add', $data);
    }

    // Insert new player statistics to DB
    public function playerStatisticsInsert() {
        // Get submitted form data
        $postData = $this->input->post();
        // Insert new record into player_statistics table
        $this->dozen->insertPlayerStatistics($postData);
        redirect('playerStatisticsList');
    }

    // Load the edit form for a specific player statistic record
    public function playerStatisticsEdit($id) {
        // Get player statistics by ID for editing
        $data['stat'] = $this->dozen->getPlayerStatisticsById($id);
        // Get all players for dropdown in edit form
        $data['players'] = $this->dozen->getAllPlayers();
        $this->load->view('dozendreams/playerstatistics_edit', $data);
    }

    // Update player statistics in DB
    public function playerStatisticsUpdate($id) {
        // Get updated form data
        $postData = $this->input->post();
        // Update the player_statistics record with given ID
        $this->dozen->updatePlayerStatistics($id, $postData);
        redirect('playerStatisticsList');
    }

    // Export player statistics to Excel
    public function playerStatisticsExport() {
        // Get all player statistics with player short names for export
        $playerStats = $this->dozen->getAllPlayerStatisticsWithPlayerShortNames();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header columns
        $sheet->setCellValue('A1', 'Player Short Name');
        $sheet->setCellValue('B1', 'Matches');
        $sheet->setCellValue('C1', 'Runs');
        $sheet->setCellValue('D1', 'Wickets');
        $sheet->setCellValue('E1', 'Catches');
        $sheet->setCellValue('F1', 'Status');

        $rowIndex = 2;
        foreach ($playerStats as $playerStat) {
            $sheet->setCellValue("A$rowIndex", $playerStat['player_short_name']);
            $sheet->setCellValue("B$rowIndex", $playerStat['matches']);
            $sheet->setCellValue("C$rowIndex", $playerStat['runs']);
            $sheet->setCellValue("D$rowIndex", $playerStat['wickets']);
            $sheet->setCellValue("E$rowIndex", $playerStat['catches']);
            $sheet->setCellValue("F$rowIndex", $playerStat['status']);
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="player_statistics.xlsx"');
        $writer->save('php://output');
    }

    // Import player statistics from Excel
    public function playerStatisticsImport() {
        // Check if an Excel file has been uploaded
        if (!empty($_FILES['excel_file']['name'])) {
            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Loop through rows, skipping header row
            for ($i = 1; $i < count($sheetData); $i++) {
                $rowData = $sheetData[$i];

                // Get player info by short name
                $player = $this->dozen->getPlayerStatisticsByShortName($rowData[0]);

                // If player exists, insert the statistics record
                if ($player) {
                    $insertData = [
                        'player_id' => $player['id'],
                        'matches' => $rowData[1],
                        'runs' => $rowData[2],
                        'wickets' => $rowData[3],
                        'catches' => $rowData[4],
                        'status' => $rowData[5],
                    ];
                    $this->dozen->insertPlayerStatistics($insertData);
                }
            }
        }
        // Redirect back to statistics list
        redirect('playerstatistics_list');
    }
    
    // Display the list of schedules with tournament and team names
    public function scheduleList() {
        $data['schedules'] = $this->dozen->getAllSchedulesWithNames(); // Fixed method name
        $this->load->view('dozendreams/schedule_list', $data);
    }

    // Load form to add a new schedule
    public function addSchedule() {
        $data['tournaments'] = $this->dozen->getAllTournaments();
        $data['teams'] = $this->dozen->getAllTeams();
        $this->load->view('dozendreams/schedule_add', $data);
    }

    // Insert new schedule data into the database
    public function insertSchedule() {
        $postData = $this->input->post();
        $this->dozen->insertSchedule($postData);
        redirect('dozen/scheduleList');
    }

    // Load form to edit an existing schedule
    public function editSchedule($id) {
        $data['schedule'] = $this->dozen->getScheduleById($id);
        $data['tournaments'] = $this->dozen->getAllTournaments();
        $data['teams'] = $this->dozen->getAllTeams();
        $this->load->view('dozendreams/schedule_edit', $data);
    }

    // Update an existing schedule in the database
    public function updateSchedule($id) {
        $postData = $this->input->post();
        $this->dozen->updateSchedule($id, $postData);
        redirect('dozen/scheduleList');
    }

    // Export match schedules to Excel with short names
    public function exportSchedules() {
        $this->load->library('phpspreadsheet'); // Make sure you load PhpSpreadsheet or have it autoloaded

        $schedules = $this->dozen->getAllSchedulesWithShortNames();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tournament Short Name');
        $sheet->setCellValue('B1', 'Team A Short Name');
        $sheet->setCellValue('C1', 'Team B Short Name');
        $sheet->setCellValue('D1', 'Match Date');
        $sheet->setCellValue('E1', 'Venue');
        $sheet->setCellValue('F1', 'Status');

        $rowIndex = 2;
        foreach ($schedules as $schedule) {
            $sheet->setCellValue("A$rowIndex", $schedule['tournament_short_name']);
            $sheet->setCellValue("B$rowIndex", $schedule['team_a_short_name']);
            $sheet->setCellValue("C$rowIndex", $schedule['team_b_short_name']);
            $sheet->setCellValue("D$rowIndex", $schedule['match_date']);
            $sheet->setCellValue("E$rowIndex", $schedule['venue']);
            $sheet->setCellValue("F$rowIndex", $schedule['status']);
            $rowIndex++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="schedules.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // Import match schedules from uploaded Excel file
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
        redirect('dozen/scheduleList');
    }

    // Display the list of all teams
    public function teamList() {
        $data['teams'] = $this->dozen->getAllTeams(); // Correct model function name
        $this->load->view('team_list', $data);
    }

    // Load the form view to add a new team
    public function addTeams() {
        $this->load->view('team_add');
    }

    // Insert a new team record into the database
    public function insertTeams() {
        $formData = $this->input->post();
        $this->dozen->insertTeam($formData); // Correct model function name
        redirect('team_list');
    }

    // Load the edit form with existing team data
    public function editTeams($id) {
        $data['team'] = $this->dozen->getTeamById($id); // Correct model function name
        $this->load->view('team_edit', $data);
    }

    // Update team information in the database
    public function updateTeams($id) {
        $formData = $this->input->post();
        $this->dozen->updateTeam($id, $formData); // Correct model function name
        redirect('team_list');
    }

    // Export all team data to an Excel file
    public function exportTeams() {
        $teams = $this->dozen->getAllTeams(); // Correct model function name

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
                $teamRow = [
                    'name' => $sheetData[$i][0],
                    'short_name' => $sheetData[$i][1],
                    'status' => $sheetData[$i][2],
                ];
                $this->dozen->insertTeam($teamRow); // Correct model function name
            }
        }
        redirect('team_list');
    }

    // Display tournament list page
    public function tournamentList() {
        $data['tournaments'] = $this->dozen->getAllTournaments(); // Fetch all tournaments
        $this->load->view('dozendreams/tournament_list', $data); // Load tournament list view
    }

    // Load add tournament page
    public function addTournament() {
        $this->load->view('dozendreams/tournament_add_edit'); // Load add tournament form view
    }

    // Insert new tournament into DB
    public function insertTournament() {
        $postData = $this->input->post(); // Get posted tournament data
        $this->dozen->insertTournament($postData);  // Insert into database
        redirect('tournamentList');       // Redirect to tournament list
    }

    // Load edit form for selected tournament
    public function editTournament($id) {
        $data['tournament'] = $this->dozen->getTournamentById($id); // Fetch tournament by ID
        $this->load->view('dozendreams/tournament_add_edit', $data); // Load edit form view
    }

    // Update tournament details
    public function updateTournament($id) {
        $postData = $this->input->post(); // Get updated tournament data
        $this->dozen->updateTournament($id, $postData); // Update record in database
        redirect('tournamentList');          // Redirect to tournament list
    }

    // Export tournament list as Excel file
    public function exportTournament() {
        $tournaments = $this->dozen->getAllTournaments(); // Fetch all tournament data
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Excel headers
        $sheet->setCellValue('A1', 'Tournament Name');
        $sheet->setCellValue('B1', 'Short Name');
        $sheet->setCellValue('C1', 'Start Date');
        $sheet->setCellValue('D1', 'End Date');
        $sheet->setCellValue('E1', 'Status');

        $row = 2;
        // Fill Excel rows with tournament data
        foreach ($tournaments as $tournament) {
            $sheet->setCellValue("A$row", $tournament['name']);
            $sheet->setCellValue("B$row", $tournament['short_name']);
            $sheet->setCellValue("C$row", $tournament['start_date']);
            $sheet->setCellValue("D$row", $tournament['end_date']);
            $sheet->setCellValue("E$row", $tournament['status']);
            $row++;
        }

        // Output Excel file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="tournaments.xlsx"');
        $writer->save('php://output');
    }

    // Import tournament data from uploaded Excel file
    public function importTournament() {
        // Check if file is uploaded
        if (!empty($_FILES['excel_file']['name'])) {
            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']); // Load Excel file
            $sheetData = $spreadsheet->getActiveSheet()->toArray(); // Convert sheet to array

            // Loop through rows (skip header)
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = [
                    'name' => $sheetData[$i][0],
                    'short_name' => $sheetData[$i][1],
                    'start_date' => $sheetData[$i][2],
                    'end_date' => $sheetData[$i][3],
                    'status' => $sheetData[$i][4],
                ];
                $this->dozen->insertTournament($row); // Insert row into DB
            }
        }
        redirect('tournamentList'); // Redirect after import
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
        redirect('DozenDreams/playerList');
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
        $this->load->view('player_add_edit', $data);
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
        redirect('DozenDreams/playerList');
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
        redirect('DozenDreams/playerList');
    }

    public function getTeamsByTournament($tournament_id) {
        header('Content-Type: application/json');
        $teams = $this->dozen->getTeamsByTournamentId($tournament_id);
        echo json_encode($teams);
    }

    public function venueList() {
        $config['base_url'] = base_url('DozenDreams/index');
        $config['total_rows'] = $this->dozen->countVenues();
        $config['per_page'] = 10;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['venues'] = $this->dozen->getVenues($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('venue_list', $data);
    }

    public function addVenue() {
        $data['pitch_types'] = $this->dozen->getAllPitchTypes(); // Get all pitch types
        $data['venue'] = []; // Empty for add
        $data['selected_pitch_types'] = [];
        $this->load->view('venue_add_edit', $data);
    }
    
    public function editVenue($id) {
        $data['venue'] = $this->dozen->getVenueById($id); // Get venue
        $data['pitch_types'] = $this->dozen->getAllPitchTypes(); // All pitch types
        $data['selected_pitch_types'] = $this->dozen->getVenuePitchTypes($id); // Venue-specific pitch types
        $this->load->view('venue_add_edit', $data);
    }
    
    public function insertVenue() {
        $post = $this->input->post();
        $pitch_type_ids = $post['pitch_type_ids'] ?? [];
        unset($post['pitch_type_ids']);
        
        $this->dozen->insertVenue($post);
        $venue_id = $this->db->insert_id(); // Get inserted venue ID
        $this->dozen->saveVenuePitchTypes($venue_id, $pitch_type_ids); // Save pitch types
        redirect('DozenDreams/venueList');
    }
    
    public function updateVenue($id) {
        $post = $this->input->post();
        $pitch_type_ids = $post['pitch_type_ids'] ?? [];
        unset($post['pitch_type_ids']);
        
        $this->dozen->updateVenue($id, $post);
        $this->dozen->saveVenuePitchTypes($id, $pitch_type_ids);
        redirect('DozenDreams/venueList');
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
        redirect('DozenDreams/venueList');
    }

}
