<?php
$role_map = [
    1 => 'Batsman',
    2 => 'Bowler',
    3 => 'All-Rounder',
    4 => 'Wicket-Keeper'
];

$selectedCaptain = $selectedCaptain ?? '';
$selectedViceCaptain = $selectedViceCaptain ?? '';
$teamLimit = $teamLimit ?? '';
$pitchType = $pitchType ?? '';
$opponentTeamId = $opponentTeamId ?? '';
$weatherCondition = $weatherCondition ?? '';

$opponentTeams = [
    1 => 'Team A',
    2 => 'Team B',
    3 => 'Team C',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generated Teams - DozenDreams</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0b1d3a;
            color: #fff;
            font-family: 'Nunito', sans-serif;
        }
        h3, h5, strong {
            color: #ffd700;
        }
        .card {
            background-color: #10264b;
            border: 1px solid #ffd70055;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.15);
        }
        .card-header {
            background-color: #1a2f56;
            color: #ffd700;
            font-weight: 600;
            font-size: 1rem;
        }
        .list-group-item {
            background-color: #1c355e;
            color: #fff;
            border: 1px solid #1c355e;
            transition: background-color 0.2s ease-in-out;
        }
        .list-group-item:hover {
            background-color: #28416b;
        }
        .list-group-item span:first-child {
            font-weight: 500;
        }
        .badge-role {
            font-size: 0.75rem;
        }
        .btn-primary, .btn-outline-primary {
            background-color: #ffd700;
            color: #0b1d3a;
            border: none;
            font-weight: bold;
        }
        .btn-outline-primary:hover,
        .btn-primary:hover {
            background-color: #e6c200;
        }
        .alert-warning {
            background-color: #ffc10733;
            color: #fff;
            border: 1px solid #ffc10755;
        }
        ul li {
            margin-bottom: 0.3rem;
        }
        .player-name,
        .player-role {
            color: #ffd700;
            font-weight: 500;
        }

    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">📊 Generated Team Combinations</h3>

    <div class="mb-4 p-3 border rounded" style="background-color: #10264b;">
        <h5 class="mb-3">📝 User Selection Summary:</h5>
        <ul class="list-unstyled">
            <li><strong>Captain ID:</strong> <?= htmlspecialchars($selectedCaptain) ?></li>
            <li><strong>Vice Captain ID:</strong> <?= htmlspecialchars($selectedViceCaptain) ?></li>
            <li><strong>Pitch Type:</strong> <?= htmlspecialchars($pitchType) ?></li>
            <li><strong>Opponent Team:</strong> <?= $opponentTeams[$opponentTeamId] ?? 'N/A' ?></li>
            <li><strong>Weather Condition:</strong> <?= htmlspecialchars($weatherCondition) ?></li>
            <li><strong>Max Teams Generated:</strong> <?= htmlspecialchars($teamLimit) ?></li>
        </ul>
    </div>

    <?php if (empty($combinations)): ?>
        <div class="alert alert-warning">⚠️ No valid combinations could be generated.</div>
    <?php else: ?>
        <form method="post" action="<?= site_url('DozenDreams/exportGeneratedTeams') ?>">
            <input type="hidden" name="json_data" value='<?= json_encode($combinations) ?>' />
            <button type="submit" class="btn btn-outline-primary mb-4">⬇ Download as Excel</button>
        </form>

        <?php foreach ($combinations as $index => $team): ?>
            <div class="card mb-4 shadow-lg border-warning">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        🏏 <strong>Team <?= $index + 1 ?></strong>
                    </div>
                    <div class="text-end small">
                        <span class="badge bg-warning text-dark me-2">Captain: <?= $team['captain'] ?></span>
                        <span class="badge bg-info text-dark me-2">VC: <?= $team['vice_captain'] ?></span>
                        <span class="badge bg-light text-dark">Points: <?= $team['total_points'] ?></span>
                    </div>
                </div>
                <div class="card-body px-3 py-2">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($team['players'] as $player): ?>
                            <?php
                                $isCaptain = isset($team['captain_id']) && $player->player_id == $team['captain_id'];
                                $isViceCaptain = isset($team['vice_captain_id']) && $player->player_id == $team['vice_captain_id'];
                                $badge = '';
                                if ($isCaptain) $badge = '<span class="badge bg-warning text-dark ms-2">C</span>';
                                elseif ($isViceCaptain) $badge = '<span class="badge bg-info text-dark ms-2">VC</span>';

                                switch ($player->role_id) {
                                    case 1:
                                        $roleIcon = '🏏'; // Batsman
                                        break;
                                    case 2:
                                        $roleIcon = '🎯'; // Bowler
                                        break;
                                    case 3:
                                        $roleIcon = '⚔️'; // All-Rounder
                                        break;
                                    case 4:
                                        $roleIcon = '🧤'; // Wicket-Keeper
                                        break;
                                    default:
                                        $roleIcon = '❓';
                                }

                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <?= $roleIcon ?> <span class="player-name"><?= $player->name ?></span>
                                    <small class="player-role">(<?= $role_map[$player->role_id] ?>)</small>
                                    <?= $badge ?>
                                </div>
                                <span class="badge bg-secondary"><?= $player->credit_points ?> pts</span>
                            </li>


                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="<?= site_url('DozenDreams') ?>" class="btn btn-primary mt-3">← Back to Selection</a>
</div>
</body>
</html>
