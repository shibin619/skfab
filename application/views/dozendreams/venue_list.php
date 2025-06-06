<?php
// VIEW: venue_list.php
// Description: Displays list of venues with pagination and options to edit/export/import
?>
<!DOCTYPE html>
<html>
<head>
    <title>Venue List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Venue List</h2>
    <a href="<?= base_url('DozenDreams/addVenue') ?>" class="btn btn-primary mb-2">Add New Venue</a>
    <a href="<?= base_url('DozenDreams/exportVenue') ?>" class="btn btn-success mb-2">Export Venues</a>

    <form action="<?= base_url('DozenDreams/importVenue') ?>" method="post" enctype="multipart/form-data" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <input type="file" name="excel_file" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-info">Import Excel</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($venues as $v): ?>
            <tr>
                <td><?= $v['name'] ?></td>
                <td><?= $v['location'] ?></td>
                <td><?= $v['capacity'] ?></td>
                <td>
                    <a href="<?= base_url('DozenDreams/editVenue/' . $v['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div><?= $pagination ?></div>
</div>
</body>
</html>
