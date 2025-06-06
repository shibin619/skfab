<?php
$role_map = [
    1 => 'Batsman',
    2 => 'Bowler',
    3 => 'All-Rounder',
    4 => 'Wicket-Keeper'
];

// Assume these variables come from controller or session after form submission
$selectedCaptain = isset($selectedCaptain) ? $selectedCaptain : '';
$selectedViceCaptain = isset($selectedViceCaptain) ? $selectedViceCaptain : '';
$teamLimit = isset($teamLimit) ? $teamLimit : '';
$pitchType = isset($pitchType) ? $pitchType : '';
$opponentTeamId = isset($opponentTeamId) ? $opponentTeamId : '';
$weatherCondition = isset($weatherCondition) ? $weatherCondition : '';

// You may want to map IDs to names if needed
$opponentTeams = [
    1 => 'Team A',
    2 => 'Team B',
    3 => 'Team C',
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generated Teams - DozenDreams</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-white">
<div class="container mt-5">
    <h3 class="text-success mb-4">Generated Team Combinations</h3>

    <div class="mb-4">
        <h5>User Selection Summary:</h5>
        <ul>
            <li><strong>Captain ID:</strong> <?= htmlspecialchars($selectedCaptain) ?></li>
            <li><strong>Vice Captain ID:</strong> <?= htmlspecialchars($selectedViceCaptain) ?></li>
            <li><strong>Pitch Type:</strong> <?= htmlspecialchars($pitchType) ?></li>
            <li><strong>Opponent Team:</strong> <?= isset($opponentTeams[$opponentTeamId]) ? $opponentTeams[$opponentTeamId] : 'N/A' ?></li>
            <li><strong>Weather Condition:</strong> <?= htmlspecialchars($weatherCondition) ?></li>
            <li><strong>Max Teams Generated:</strong> <?= htmlspecialchars($teamLimit) ?></li>
        </ul>
    </div>

    <?php if (empty($combinations)): ?>
        <div class="alert alert-warning">No valid combinations could be generated.</div>
    <?php else: ?>
        <form method="post" action="<?= site_url('DozenDreams/exportGeneratedTeams') ?>">
            <input type="hidden" name="json_data" value='<?= json_encode($combinations) ?>' />
            <button type="submit" class="btn btn-outline-primary mb-3">Download as Excel</button>
        </form>

        <?php foreach ($combinations as $index => $team): ?>
            <div class="card mb-4 shadow">
                <div class="card-header bg-dark text-white">
                    <strong>Team <?= $index + 1 ?></strong> | Captain: <?= $team['captain'] ?> | Vice Captain: <?= $team['vice_captain'] ?> | Total Points: <?= $team['total_points'] ?>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($team['players'] as $player): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?= $player->name ?> (<?= $role_map[$player->role_id] ?>)</span>
                                <span><?= $player->credit_points ?> pts</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="<?= site_url('DozenDreams') ?>" class="btn btn-primary">← Back to Selection</a>
</div>
</body>
</html>
