<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">📊 Player Statistics List</h4>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="<?= base_url('dozendreams/playerStatisticsAdd') ?>" class="btn btn-sm btn-light border-accent text-accent">
                <i class="fas fa-plus"></i> Add New
            </a>
            <a href="<?= base_url('dozendreams/playerStatisticsExport') ?>" class="btn btn-sm btn-warning">
                <i class="fas fa-file-export"></i> Export
            </a>
            <form action="<?= base_url('dozendreams/playerStatisticsImport') ?>" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
                <input type="file" name="excel_file" class="form-control form-control-sm me-2" accept=".xls,.xlsx" required>
                <button type="submit" class="btn btn-info btn-sm">Import</button>
            </form>
        </div>

        <div class="table-responsive">
            <table id="statsTable" class="table table-bordered table-striped table-hover" style="background-color: #5c6bc0; color: #ffffff;">
                <thead style="background-color: #7986cb; color: #fdd835;">
                    <tr>
                        <th>#</th>
                        <th>Player</th>
                        <th>Runs</th>
                        <th>Balls</th>
                        <th>4s</th>
                        <th>6s</th>
                        <th>Wickets</th>
                        <th>Overs</th>
                        <th>Conceded</th>
                        <th>Catches</th>
                        <th>Stumpings</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($playersStats)): ?>
                        <?php $i = 1; foreach ($playersStats as $stat): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($stat['player_name']) ?></td>
                                <td><?= $stat['runs'] ?></td>
                                <td><?= $stat['balls_faced'] ?? '-' ?></td>
                                <td><?= $stat['fours'] ?? '-' ?></td>
                                <td><?= $stat['sixes'] ?? '-' ?></td>
                                <td><?= $stat['wickets'] ?></td>
                                <td><?= $stat['overs_bowled'] ?? '-' ?></td>
                                <td><?= $stat['runs_conceded'] ?? '-' ?></td>
                                <td><?= $stat['catches'] ?></td>
                                <td><?= $stat['stumpings'] ?? '-' ?></td>
                                <td>
                                    <?php
                                        if (isset($stat['status_id'])) {
                                            echo ($stat['status_id'] == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                                        } else {
                                            echo '-';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('DozenDreams/playerStatisticsEdit/' . $stat['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="13" class="text-center text-light">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#statsTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'asc']]
        });
    });
</script>
