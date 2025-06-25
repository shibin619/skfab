<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">Users List</h4>

        <table id="usersTable" class="table table-bordered table-hover table-striped" style="background-color: #5c6bc0; color: #ffffff;">
            <thead style="background-color: #7986cb; color: #fdd835;">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) { 
                    $i = 1;
                    foreach ($users as $user) { ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['status']) ?></td>
                        <td><?= date('d M Y H:i', strtotime($user['created_at'])) ?></td>
                    </tr>
                <?php }} else { ?>
                    <tr><td colspan="6" class="text-center text-light">No users found.</td></tr>
                <?php } ?>
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
        $('#usersTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[ 0, "asc" ]]
        });
    });
</script>
