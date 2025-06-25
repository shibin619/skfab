<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0" style="color: #fdd835;">Teams List</h4>
            <div>
                <a href="<?= base_url('DozenDreams/addTeams') ?>" class="btn btn-sm btn-primary">Add Team</a>
                <a href="<?= base_url('DozenDreams/exportTeams') ?>" class="btn btn-sm btn-success">Export</a>
            </div>
        </div>

        <form action="<?= base_url('DozenDreams/importTeams') ?>" method="post" enctype="multipart/form-data" class="mb-3">
            <div class="input-group">
                <input type="file" name="excel_file" class="form-control" required>
                <button class="btn btn-warning" type="submit">Import</button>
            </div>
        </form>

        <table id="teamsTable" class="table table-bordered table-hover table-striped" style="background-color: #5c6bc0; color: #ffffff;">
            <thead style="background-color: #7986cb; color: #fdd835;">
                <tr>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Status</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['name']) ?></td>
                        <td><?= htmlspecialchars($t['short_name']) ?></td>
                        <td><?= $t['status'] ? 'Active' : 'Inactive' ?></td>
                        <td>
                            <a href="<?= base_url('DozenDreams/editTeams/' . $t['id']) ?>" class="btn btn-sm btn-info">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#teamsTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, "asc"]]
        });
    });
</script>
