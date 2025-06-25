<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">Contests List</h4>

        <table id="contestTable" class="table table-bordered table-hover table-striped" style="background-color: #5c6bc0; color: #ffffff;">
            <thead style="background-color: #7986cb; color: #fdd835;">
                <tr>
                    <th>#</th>
                    <th>Prize Pool</th>
                    <th>Entry Fee</th>
                    <th>Spots</th>
                    <th>First Prize</th>
                    <th>Win %</th>
                    <th>Max Teams</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($contests)) {
                    $i = 1;
                    foreach ($contests as $c): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>₹<?= number_format($c['prize_pool']) ?> <?= $c['prize_type'] ?></td>
                            <td>₹<?= $c['entry_fee'] ?></td>
                            <td><?= number_format($c['spots_left']) ?> / <?= number_format($c['total_spots']) ?></td>
                            <td>₹<?= number_format($c['first_prize']) ?></td>
                            <td><?= $c['winning_percent'] ?>%</td>
                            <td><?= $c['max_teams'] ?></td>
                        </tr>
                <?php endforeach;
                } else { ?>
                    <tr><td colspan="7" class="text-center text-light">No contests found.</td></tr>
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
        $('#contestTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[ 0, "asc" ]]
        });
    });
</script>
