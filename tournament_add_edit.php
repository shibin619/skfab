<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">
            <?= isset($tournament) ? 'Edit Tournament' : 'Add Tournament' ?>
        </h4>

        <form action="<?= isset($tournament) ? base_url('DozenDreams/updateTournament/' . $tournament['id']) : base_url('DozenDreams/insertTournament') ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Tournament Name</label>
                <input type="text" name="name" class="form-control" value="<?= $tournament['name'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Short Name</label>
                <input type="text" name="short_name" class="form-control" value="<?= $tournament['short_name'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?= $tournament['start_date'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?= $tournament['end_date'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="1" <?= isset($tournament) && $tournament['status'] == 1 ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= isset($tournament) && $tournament['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-success"><?= isset($tournament) ? 'Update' : 'Add' ?></button>
                <a href="<?= base_url('DozenDreams/tournamentList') ?>" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
