<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">✏️ Edit Dream11 Point Rule</h4>

        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="action" class="form-label text-warning">Action</label>
                <input type="text" name="action" class="form-control bg-light text-dark" value="<?= set_value('action', $point['action']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label text-warning">Role</label>
                <select name="role_id" class="form-select bg-light text-dark" required>
                    <option value="">-- Select Role --</option>
                    <?php
                    foreach ($roles as $id => $name):
                        $selected = ($point['role_id'] == $id) ? 'selected' : '';
                    ?>
                        <option value="<?= $id ?>" <?= $selected ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="type_id" class="form-label text-warning">Match Type</label>
                <select name="type_id" class="form-select bg-light text-dark" style="min-width: 200px;" required>
                    <option value="">-- Select Match Type --</option>
                    <?php foreach ($match_types as $id => $name): ?>
                        <option value="<?= $id ?>" <?= (set_value('type_id', $point['type_id']) == $id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>




            <div class="mb-3">
                <label for="points" class="form-label text-warning">Points</label>
                <input type="number" name="points" class="form-control bg-light text-dark" value="<?= set_value('points', $point['points']) ?>" step="any" required>
            </div>

            <button type="submit" class="btn btn-warning">✅ Update Rule</button>
            <a href="<?= site_url('DozenDreams/pointsList') ?>" class="btn btn-light">← Back</a>
        </form>
    </div>
</div>
