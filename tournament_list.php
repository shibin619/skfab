<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">Tournament List</h4>

        <div class="d-flex justify-content-between mb-3">
            <a href="<?= base_url('DozenDreams/addTournament') ?>" class="btn btn-primary">Add Tournament</a>
            <a href="<?= base_url('DozenDreams/exportTournament') ?>" class="btn btn-success">Export</a>
        </div>

        <form action="<?= base_url('DozenDreams/importTournament') ?>" method="post" enctype="multipart/form-data" class="mb-3">
            <div class="input-group">
                <input type="file" name="excel_file" class="form-control" required>
                <button class="btn btn-warning" type="submit">Import</button>
            </div>
        </form>

        <table id="tournamentsTable" class="table table-bordered table-hover table-striped" style="background-color: #5c6bc0; color: #ffffff;">
            <thead style="background-color: #7986cb; color: #fdd835;">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($tournaments)) {
                    $i = 1;
                    foreach ($tournaments as $t) {
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($t['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($t['short_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($t['start_date'] ?? '') ?></td>
                            <td><?= htmlspecialchars($t['end_date'] ?? '') ?></td>
                            <td><?= isset($t['status']) && $t['status'] == 1 ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <a href="<?= base_url('DozenDreams/editTournament/' . $t['id']) ?>" class="btn btn-sm btn-info">Edit</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="7" class="text-center text-light">No tournaments found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#tournamentsTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, "asc"]],
            "responsive": true
        });
    });
</script>
