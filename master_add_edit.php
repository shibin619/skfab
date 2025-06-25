<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #1e2a78; color: #e0e0e0;">
        <h4 class="mb-4" style="color: #f9c846;">
            <?= isset($master) ? 'Edit Master' : 'Add Master' ?>
        </h4>

        <form method="post" action="<?= isset($master) ? base_url('DozenDreams/updateMaster/' . $master->id) : base_url('DozenDreams/insertMaster') ?>">
            
            <div class="mb-3">
                <label class="form-label text-light">Master Type</label>
                <select name="master_type_id" class="form-select bg-dark text-light" required>
                    <option value="">Select</option>
                    <?php foreach ($master_types as $type): ?>
                        <option value="<?= $type->id ?>" <?= isset($master) && $master->master_type_id == $type->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Name</label>
                <input type="text" name="name" class="form-control bg-dark text-light" required value="<?= isset($master) ? htmlspecialchars($master->name) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Short Code</label>
                <input type="text" name="short_code" class="form-control bg-dark text-light" value="<?= isset($master) ? htmlspecialchars($master->short_code) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Status</label>
                <select name="status" class="form-select bg-dark text-light">
                    <option value="1" <?= isset($master) && $master->status == 1 ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= isset($master) && $master->status == 0 ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><?= isset($master) ? 'Update' : 'Submit' ?></button>
            <a href="<?= base_url('DozenDreams/mastersList') ?>" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
