<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;"><?= isset($team) ? 'Edit Team' : 'Add Team' ?></h4>

        <form action="<?= isset($team) ? base_url('DozenDreams/updateTeams/' . $team['id']) : base_url('DozenDreams/insertTeams') ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Team Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($team['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="short_name" class="form-label">Short Name</label>
                <input type="text" id="short_name" name="short_name" class="form-control" value="<?= htmlspecialchars($team['short_name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1" <?= isset($team) && $team['status'] == 1 ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= isset($team) && $team['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><?= isset($team) ? 'Update' : 'Add' ?></button>
                <a href="<?= base_url('DozenDreams/teamList') ?>" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
