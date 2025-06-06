<!DOCTYPE html>
<html>
<head>
    <title>Teams</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="d-flex justify-content-between mb-3">
        <h4>Teams</h4>
        <div>
            <a href="<?= base_url('DozenDreams/addTeams') ?>" class="btn btn-primary">Add Team</a>
            <a href="<?= base_url('DozenDreams/exportTeams') ?>" class="btn btn-success">Export</a>
        </div>
    </div>

    <form action="<?= base_url('DozenDreams/importTeams') ?>" method="post" enctype="multipart/form-data" class="mb-3">
        <div class="input-group">
            <input type="file" name="excel_file" class="form-control" required>
            <button class="btn btn-warning" type="submit">Import</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Short Name</th>
                <th>Status</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teams as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['name']) ?></td>
                    <td><?= htmlspecialchars($t['short_name']) ?></td>
                    <td><?= $t['status'] ? 'Active' : 'Inactive' ?></td>
                    <td><a href="<?= base_url('DozenDreams/editTeams/'.$t['id']) ?>" class="btn btn-sm btn-info">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

