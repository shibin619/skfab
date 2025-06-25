<style>
  table.dataTable tbody tr {
    background-color: #1a2f56;
}
table.dataTable tbody tr:hover {
    background-color: #28416b;
}
table.dataTable th {
    background-color: #28416b;
    color: #ffd700;
}
.dataTables_wrapper .dataTables_filter input {
    background-color: #10264b;
    color: #fff;
    border: 1px solid #ffd700;
}

</style>
<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #10264b; color: #ffffff; border: 1px solid #ffd70055;">
        <h4 class="mb-4" style="color: #ffd700;">💸 All User Referral Earnings</h4>

        <table id="referralsTable" class="table table-bordered table-hover table-striped mb-0" style="background-color: #1a2f56; color: #ffffff;">
            <thead style="background-color: #28416b; color: #ffd700;">
                <tr>
                    <th>#</th>
                    <th>👤 Parent User</th>
                    <th>👥 Referred User</th>
                    <th>🏷️ Level</th>
                    <th>💰 Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($referrals)) {
                    foreach ($referrals as $i => $ref): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($ref->parent_name) ?></td>
                            <td><?= htmlspecialchars($ref->referred_name) ?></td>
                            <td><?= htmlspecialchars($ref->level) ?></td>
                            <td>₹<?= number_format($ref->amount, 2) ?></td>
                        </tr>
                    <?php endforeach;
                } else { ?>
                    <tr><td colspan="5" class="text-center text-warning">⚠️ No referral earnings found.</td></tr>
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
        $('#referralsTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[ 0, "asc" ]],
            language: {
                emptyTable: "No referrals to show",
                search: "🔍 Search:",
                lengthMenu: "Show _MENU_ entries"
            }
        });
    });
</script>
