<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Players List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>All Players</h4>
            <a href="<?= base_url('DozenDreams/addPlayer') ?>" class="btn btn-primary">Add New Player</a>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('msg')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('msg') ?></div>
        <?php endif; ?>

        <!-- Export/Import Section -->
        <div class="d-flex justify-content-between mb-3">
            <a href="<?= base_url('DozenDreams/exportPlayers') ?>" class="btn btn-outline-primary">Export to Excel</a>

            <form action="<?= base_url('DozenDreams/importPlayers') ?>" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
                <input type="file" name="excel_file" class="form-control me-2" accept=".xls,.xlsx" required>
                <button type="submit" class="btn btn-outline-success">Import</button>
            </form>
        </div>

        <!-- Players Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Special Ability</th>
                        <th>Fitness Status</th>
                        <th>Team</th>
                        <th>Tournament</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($players)) : ?>
                        <?php $i = 1; foreach ($players as $player) : ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td><?= htmlspecialchars($player['name']) ?></td>
                                <td><?= htmlspecialchars($player['role']) ?></td>
                                <td><?= htmlspecialchars($player['special_ability']) ?></td> 
                                <td class="text-center">
                                    <?php 
                                        $status = strtolower($player['fitness_status']);
                                        $badgeClass = 'secondary';
                                        if ($status == 'fit') $badgeClass = 'success';
                                        elseif ($status == 'injured') $badgeClass = 'danger';
                                        elseif ($status == 'doubtful') $badgeClass = 'warning';
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= ucfirst($player['fitness_status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($player['team_name']) ?></td>
                                <td><?= htmlspecialchars($player['tournament_name']) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('DozenDreams/editPlayer/' . $player['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="8" class="text-center">No players found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            <?= $pagination ?>
        </div>
    </div>
</div>
</body>
</html>
