<!DOCTYPE html>
<html>
<head>
    <title><?= empty($venue) ? 'Add' : 'Edit' ?> Venue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2><?= empty($venue) ? 'Add' : 'Edit' ?> Venue</h2>
    <form method="post" action="<?= base_url('DozenDreams/' . (empty($venue) ? 'insertVenue' : 'updateVenue/'.$venue['id'])) ?>">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= $venue['name'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="<?= $venue['location'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label>Capacity</label>
            <input type="number" name="capacity" class="form-control" value="<?= $venue['capacity'] ?? '' ?>">
        </div>
        
        <div class="mb-3">
            <label>Pitch Types</label><br>
            <?php foreach ($pitch_types as $pt): ?>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="pitch_type_ids[]" class="form-check-input"
                           value="<?= $pt['id'] ?>"
                           <?= in_array($pt['id'], $selected_pitch_types) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $pt['type_name'] ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-success"><?= empty($venue) ? 'Add' : 'Update' ?></button>
        <a href="<?= base_url('DozenDreams/venueList') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
