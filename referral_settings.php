<div class="container mt-4">
    <h4>Edit Referral Levels</h4>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <form method="post">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Percentage (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($levels as $level): ?>
                    <tr>
                        <td>Level <?= $level->level ?></td>
                        <td>
                            <input type="number" name="levels[<?= $level->id ?>]" value="<?= $level->percentage ?>" class="form-control" step="0.01" min="0" max="100" required>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-primary">Update Levels</button>
    </form>
</div>
