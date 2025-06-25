<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0" style="color: #fdd835;">Schedule List</h4>
            <a href="<?= base_url('DozenDreams/addSchedule') ?>" class="btn btn-outline-light border-accent">+ Add Schedule</a>
        </div>

        <div class="table-responsive">
            <table id="scheduleTable" class="table table-bordered table-hover table-striped" style="color: #fff;">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tournament</th>
                        <th>Team A</th>
                        <th>Team B</th>
                        <th>Match Date</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($schedules as $row): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $row['tournament_name'] ?></td>
                            <td><?= $row['team_a_name'] ?></td>
                            <td><?= $row['team_b_name'] ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($row['match_date'])) ?></td>
                            <td><?= $row['venue_name'] ?? $row['venue'] ?></td>
                            <td><?= $row['status_name'] ?? ucfirst($row['status']) ?></td>
                            <td>
                                <a href="<?= base_url('DozenDreams/editSchedule/' . $row['id']) ?>" class="btn btn-sm btn-outline-light border-accent">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($schedules)): ?>
                        <tr><td colspan="8" class="text-center text-light">No schedules available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ Include jQuery and DataTables (if not already included) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#scheduleTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "order": [[ 4, "desc" ]], // Default sort by Match Date
        "columnDefs": [
            { "orderable": false, "targets": 7 } // Disable sorting on Action column
        ]
    });
});
</script>
