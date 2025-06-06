<!DOCTYPE html>
<html lang="en">
<head>
    <title>Player Statistics List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Player Statistics List</h2>
    <div class="mb-3">
        <a href="<?= site_url('playerstatistics/add') ?>" class="btn btn-primary">Add New Statistic</a>
        <a href="<?= site_url('playerstatistics/export') ?>" class="btn btn-success">Export to Excel</a>
    </div>

    <!-- Import form -->
    <form action="<?= site_url('playerstatistics/import') ?>" method="post" enctype="multipart/form-data" class="mb-4">
        <div class="input-group">
            <input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx" required>
            <button type="submit" class="btn btn-info">Import Excel</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Player Name</th>
                <th>Matches</th>
                <th>Runs</th>
                <th>Wickets</th>
                <th>Catches</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($players_stats)): ?>
                <?php foreach ($players_stats as $stat): ?>
                    <tr>
                        <td><?= $stat['id'] ?></td>
                        <td><?= htmlspecialchars($stat['player_name']) ?></td>
                        <td><?= $stat['matches'] ?></td>
                        <td><?= $stat['runs'] ?></td>
                        <td><?= $stat['wickets'] ?></td>
                        <td><?= $stat['catches'] ?></td>
                        <td><?= $stat['status'] ? 'Active' : 'Inactive' ?></td>
                        <td>
                            <a href="<?= site_url('playerstatistics/edit/'.$stat['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
