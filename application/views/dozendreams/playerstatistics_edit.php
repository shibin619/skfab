<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Player Statistic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Player Statistic</h2>
    <form method="post" action="<?= site_url('playerstatistics/update/'.$stat['id']) ?>">
        <div class="mb-3">
            <label for="player_id" class="form-label">Player</label>
            <select name="player_id" id="player_id" class="form-select" required>
                <option value="">Select Player</option>
                <?php foreach($players as $player): ?>
                    <option value="<?= $player['id'] ?>" <?= $stat['player_id'] == $player['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($player['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="matches" class="form-label">Matches</label>
            <input type="number" class="form-control" name="matches" id="matches" value="<?= $stat['matches'] ?>" min="0" required>
        </div>
        <div class="mb-3">
            <label for="runs" class="form-label">Runs</label>
            <input type="number" class="form-control" name="runs" id="runs" value="<?= $stat['runs'] ?>" min="0" required>
        </div>
        <div class="mb-3">
            <label for="wickets" class="form-label">Wickets</label>
            <input type="number" class="form-control" name="wickets" id="wickets" value="<?= $stat['wickets'] ?>" min="0" required>
        </div>
        <div class="mb-3">
            <label for="catches" class="form-label">Catches</label>
            <input type="number" class="form-control" name="catches" id="catches" value="<?= $stat['catches'] ?>" min="0" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1" <?= $stat['status'] == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $stat['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="<?= site_url('playerstatistics') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
