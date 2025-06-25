<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #1e2a78; color: #e0e0e0;">
        <h4 class="mb-4" style="color: #f9c846;">Masters</h4>
        <a href="<?= base_url('DozenDreams/addMaster') ?>" class="btn btn-success mb-3">Add Master</a>

        <table id="mastersTable" class="table table-bordered table-hover table-striped" style="background-color: #2c387e; color: #ffffff;">
            <thead style="background-color: #3f51b5; color: #f9c846;">
                <tr>
                    <th>ID</th>
                    <th>Master Type</th>
                    <th>Name</th>
                    <th>Short Code</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($masters)) {
                    foreach ($masters as $m): ?>
                    <tr>
                        <td><?= $m->id ?></td>
                        <td><?= htmlspecialchars($m->master_type) ?></td>
                        <td><?= htmlspecialchars($m->name) ?></td>
                        <td><?= htmlspecialchars($m->short_code) ?></td>
                        <td><?= $m->status == 1 ? 'Active' : 'Inactive' ?></td>
                        <td>
                            <a href="<?= base_url('DozenDreams/editMaster/' . $m->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; } else { ?>
                    <tr><td colspan="6" class="text-center text-light">No masters found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- DataTables Scripts -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#mastersTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, "asc"]],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "→",
                    "previous": "←"
                }
            }
        });
    });
</script>
