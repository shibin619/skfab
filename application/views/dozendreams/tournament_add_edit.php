<!DOCTYPE html>
<html>
<head>
    <title><?= isset($tournament) ? 'Edit' : 'Add' ?> Tournament</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h4><?= isset($tournament) ? 'Edit' : 'Add' ?> Tournament</h4>
    <form action="<?= isset($tournament) ? base_url('DozenDreams/updateTournament/'.$tournament['id']) : base_url('DozenDreams/insertTournament') ?>" method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= $tournament['name'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Short Name</label>
            <input type="text" name="short_name" class="form-control" value="<?= $tournament['short_name'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="<?= $tournament['start_date'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="<?= $tournament['end_date'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" <?= isset($tournament) && $tournament['status'] == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= isset($tournament) && $tournament['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button class="btn btn-success"><?= isset($tournament) ? 'Update' : 'Add' ?></button>
        <a href="<?= base_url('DozenDreams/tournament_list') ?>" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>
