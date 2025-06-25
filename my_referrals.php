<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">My Referral Earnings</h4>

        <table id="referralTable" class="table table-bordered table-hover table-striped" style="background-color: #5c6bc0; color: #ffffff;">
            <thead style="background-color: #7986cb; color: #fdd835;">
                <tr>
                    <th>#</th>
                    <th>Referred User</th>
                    <th>Level</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($referrals)) {
                    foreach ($referrals as $i => $ref): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($ref->referred_name) ?></td>
                            <td><?= htmlspecialchars($ref->level) ?></td>
                            <td>₹<?= number_format($ref->amount, 2) ?></td>
                        </tr>
                    <?php endforeach;
                } else { ?>
                    <tr><td colspan="4" class="text-center text-light">No referral earnings found.</td></tr>
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

        $('#referralTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[ 0, "asc" ]]
        });
    });
</script>
