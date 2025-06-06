<!DOCTYPE html>
<html>
<head>
    <title><?= isset($team) ? 'Edit' : 'Add' ?> Team</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h4><?= isset($team) ? 'Edit' : 'Add' ?> Team</h4>
    <form action="<?= isset($team) ? base_url('DozenDreams/updateTeams/'.$team['id']) : base_url('DozenDreams/insertTeams') ?>" method="post">
        <div class="mb-3">
            <label>Team Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($team['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Short Name</label>
            <input type="text" name="short_name" class="form-control" value="<?= htmlspecialchars($team['short_name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="1" <?= isset($team) && $team['status'] == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= isset($team) && $team['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success"><?= isset($team) ? 'Update' : 'Add' ?></button>
        <a href="<?= base_url('DozenDreams/teamList') ?>" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>
