<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #093f64; color: #ffffff;">
        <h4 class="mb-4" style="color: #fdd835;">🛠️ Cron Jobs Management</h4>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if (!empty($crons)): ?>
            <ul class="list-group">
                <?php foreach ($crons as $cron): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start bg-dark text-light mb-3 rounded">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold"><?= $cron['name']; ?></div>
                            <small><?= $cron['description']; ?></small><br>

                            <span class="badge bg-<?= ($cron['status_name'] == 'Completed') ? 'success' : (($cron['status_name'] == 'Pending') ? 'warning' : 'danger'); ?> mt-2">
                                <?= $cron['status_name']; ?>
                            </span><br>

                            <?php if (!empty($cron['updated_by'])): ?>
                                <small class="text-muted">
                                    Last Run By: <strong><?= $cron['updated_by']; ?></strong> (<?= ucfirst($cron['last_run_role']); ?>)<br>
                                    Time: <?= date('d M Y, h:i A', strtotime($cron['last_run_time'])); ?>
                                </small>
                            <?php else: ?>
                                <small class="text-muted">Not run yet.</small>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('DozenDreams/runCron/' . $cron['id']); ?>" class="btn btn-sm btn-outline-warning mt-2">▶️ Run Now</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info text-center">📭 No cron jobs available for today.</div>
        <?php endif; ?>

        <div class="text-end mt-4">
            <a href="<?= base_url('DozenDreams/dashboard'); ?>" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
        </div>
    </div>
</div>
