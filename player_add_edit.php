<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($player) ? 'Edit Player' : 'Add Player' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow rounded-4 p-4">
        <h4 class="mb-4"><?= isset($player) ? 'Edit Player' : 'Add Player' ?></h4>
        <form method="post" action="<?= isset($player) ? base_url('DozenDreams/updatePlayer/' . $player['id']) : base_url('DozenDreams/insertPlayer') ?>">

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Player Name</label>
                <input type="text" name="name" class="form-control" required value="<?= isset($player) ? htmlspecialchars($player['name']) : '' ?>">
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <?php
                    $roles = ['Batsman', 'Bowler', 'All-Rounder', 'Wicket-Keeper'];
                    foreach ($roles as $role) :
                        $selected = (isset($player) && $player['role'] == $role) ? 'selected' : '';
                        echo "<option value='$role' $selected>$role</option>";
                    endforeach;
                    ?>
                </select>
            </div>

            <!-- Special Ability -->
            <div class="mb-3">
                <label for="special_ability" class="form-label">Special Ability</label>
                <select name="special_ability" id="special_ability" class="form-control">
                    <option value="">Select Special Ability</option>
                    <?php foreach ($pitch_types as $type): ?>
                        <option value="<?= $type['ability_name'] ?>"
                            <?= (isset($player['special_ability']) && $player['special_ability'] == $type['ability_name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['ability_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tournament -->
            <div class="mb-3">
                <label for="tournament" class="form-label">Tournament</label>
                <select name="tournament_id" id="tournament" class="form-control" required>
                    <option value="">Select Tournament</option>
                    <?php foreach ($tournaments as $tournament): ?>
                        <option value="<?= $tournament['id'] ?>" <?= (isset($selected_tournament) && $selected_tournament == $tournament['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tournament['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Team -->
            <div class="mb-3">
                <label for="team_id" class="form-label">Team</label>
                <select name="team_id" id="team_id" class="form-control" required>
                    <option value="">Select Team</option>
                    <?php if (!empty($teams)): ?>
                        <?php foreach ($teams as $team): ?>
                            <option value="<?= $team['id'] ?>" <?= (isset($player) && $player['team_id'] == $team['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($team['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Fitness Status -->
            <div class="mb-3">
                <label for="fitness_status" class="form-label">Fitness Status</label>
                <select name="fitness_status" id="fitness_status" class="form-control" required>
                    <option value="">Select Fitness Status</option>
                    <option value="Fit" <?= (isset($player) && $player['fitness_status'] == 'Fit') ? 'selected' : '' ?>>Fit</option>
                    <option value="Injured" <?= (isset($player) && $player['fitness_status'] == 'Injured') ? 'selected' : '' ?>>Injured</option>
                    <option value="Doubtful" <?= (isset($player) && $player['fitness_status'] == 'Doubtful') ? 'selected' : '' ?>>Doubtful</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success"><?= isset($player) ? 'Update Player' : 'Add Player' ?></button>
            <a href="<?= base_url('DozenDreams/playerList') ?>" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tournament').change(function () {
            const tournamentId = $(this).val();
            $('#team_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: '<?= base_url('DozenDreams/getTeamsByTournament') ?>/' + tournamentId,
                method: 'GET',
                success: function (data) {
                    const teams = JSON.parse(data);
                    let options = '<option value="">Select Team</option>';
                    teams.forEach(team => {
                        options += `<option value="${team.id}">${team.name}</option>`;
                    });
                    $('#team_id').html(options);
                }
            });
        });
    });
</script>
</body>
</html>
