<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">Dream11 Point Rules</h4>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>

        <div class="mb-3 text-end">
            <a href="<?= site_url('dozendreams/pointsAdd') ?>" class="btn btn-warning">➕ Add New Rule</a>
        </div>

        <table id="pointsTable" class="table table-bordered table-hover table-striped" style="background-color: #5c6bc0; color: #ffffff;">
            <thead style="background-color: #7986cb; color: #fdd835;">
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Role</th>
                    <th>Points</th>
                    <th>Match Type</th>
                    <th>Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($points)): ?>
                    <?php $i = 1; foreach ($points as $point): 
                ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($point['action']) ?></td>
                            <td><?= $roles[$point['role_id']] ?? 'Unknown' ?></td>
                            <td><?= htmlspecialchars($point['points']) ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($point['match_type'] ?? 'N/A') ?></span></td>
                            <td>
                                <a href="<?= base_url('DozenDreams/pointsEdit/' . $point['id']) ?>" class="btn btn-sm btn-light">✏️ Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-light">No point rules found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#pointsTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, "asc"]]
        });
    });
</script>
