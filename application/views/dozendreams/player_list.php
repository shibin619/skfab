<?php
$role_map = [
    1 => 'Batsman',
    2 => 'Bowler',
    3 => 'All-Rounder',
    4 => 'Wicket-Keeper'
];

// Example options for pitch, opponent teams, weather —
// you can replace these with dynamic values if needed
$pitch_types = ['Batting', 'Bowling', 'Balanced'];
$opponent_teams = [
    1 => 'Team A',
    2 => 'Team B',
    3 => 'Team C',
];
$weather_conditions = ['Sunny', 'Cloudy', 'Rainy', 'Windy'];

// To handle old input, fallback to empty or default values
$selectedCaptain = isset($_POST['captain']) ? $_POST['captain'] : '';
$selectedViceCaptain = isset($_POST['vice_captain']) ? $_POST['vice_captain'] : '';
$selectedPlayers = isset($_POST['players']) ? $_POST['players'] : [];
$teamLimit = isset($_POST['limit']) ? intval($_POST['limit']) : 50;
$pitchType = isset($_POST['pitch_type']) ? $_POST['pitch_type'] : '';
$opponentTeamId = isset($_POST['opponent_team']) ? $_POST['opponent_team'] : '';
$weatherCondition = isset($_POST['weather']) ? $_POST['weather'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>DozenDreams - Player Selection</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4 text-primary">Select Your Players (Max 11)</h3>

    <form method="post" action="<?= site_url('DozenDreams/generate') ?>" id="playerForm">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="captain" class="form-label">Select Captain</label>
                <select name="captain" id="captain" class="form-control">
                    <option value="">-- Select Captain --</option>
                    <?php foreach ($players as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($selectedCaptain == $p['id']) ? 'selected' : '' ?>><?= $p['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="vice_captain" class="form-label">Select Vice Captain</label>
                <select name="vice_captain" id="vice_captain" class="form-control">
                    <option value="">-- Select Vice Captain --</option>
                    <?php foreach ($players as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($selectedViceCaptain == $p['id']) ? 'selected' : '' ?>><?= $p['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Select Players:</label>
            <div class="row" id="playerCheckboxes">
                <?php foreach ($players as $p): ?>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="players[]" value="<?= $p['id'] ?>" class="form-check-input player-checkbox" id="player<?= $p['id'] ?>"
                                <?= in_array($p['id'], $selectedPlayers) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="player<?= $p['id'] ?>">
                                <?= $p['name'] ?> (<?= $role_map[$p['role_id']] ?> - <?= $p['credit_points'] ?> pts)
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="pitch_type" class="form-label">Pitch Type</label>
                <select name="pitch_type" id="pitch_type" class="form-control">
                    <option value="">-- Select Pitch Type --</option>
                    <?php foreach ($pitch_types as $pt): ?>
                        <option value="<?= $pt ?>" <?= ($pitchType == $pt) ? 'selected' : '' ?>><?= $pt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="opponent_team" class="form-label">Opponent Team</label>
                <select name="opponent_team" id="opponent_team" class="form-control">
                    <option value="">-- Select Opponent Team --</option>
                    <?php foreach ($opponent_teams as $id => $name): ?>
                        <option value="<?= $id ?>" <?= ($opponentTeamId == $id) ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="weather" class="form-label">Weather Condition</label>
                <select name="weather" id="weather" class="form-control">
                    <option value="">-- Select Weather --</option>
                    <?php foreach ($weather_conditions as $w): ?>
                        <option value="<?= $w ?>" <?= ($weatherCondition == $w) ? 'selected' : '' ?>><?= $w ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="limit" class="form-label">Max Teams to Generate</label>
            <input type="number" name="limit" value="<?= htmlspecialchars($teamLimit) ?>" min="1" max="5000" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Generate Teams</button>
    </form>
</div>

<script>
    function updateMaxLimit() {
        let cap = document.getElementById("captain").value;
        let vice = document.getElementById("vice_captain").value;
        let maxAllowed = 11;

        if (cap && vice) maxAllowed = 9;
        else if (cap || vice) maxAllowed = 10;

        let checkboxes = document.querySelectorAll('.player-checkbox');
        let checked = Array.from(checkboxes).filter(cb => cb.checked);

        checkboxes.forEach(cb => cb.disabled = false);

        if (checked.length >= maxAllowed) {
            checkboxes.forEach(cb => {
                if (!cb.checked) cb.disabled = true;
            });
        }
    }

    document.querySelectorAll('.player-checkbox, #captain, #vice_captain').forEach(el => {
        el.addEventListener('change', updateMaxLimit);
    });

    // On page load
    updateMaxLimit();
</script>
</body>
</html>
