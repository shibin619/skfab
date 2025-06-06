<!DOCTYPE html>
<html>
<head>
    <title>Schedule List</title>
</head>
<body>
    <h1>Schedule List</h1>
    <a href="<?php echo site_url('DozenDreams/addSchedule'); ?>">Add New Schedule</a>
    <a href="<?php echo site_url('DozenDreams/exportSchedules'); ?>">Export to Excel</a>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tournament</th>
                <th>Team A</th>
                <th>Team B</th>
                <th>Match Date</th>
                <th>Venue</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($schedules)): ?>
                <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><?php echo $schedule['id']; ?></td>
                        <td><?php echo htmlspecialchars($schedule['tournament_name']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['team_a_name']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['team_b_name']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['match_date']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['venue']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['status']); ?></td>
                        <td>
                            <a href="<?php echo site_url('DozenDreams/editSchedule/'.$schedule['id']); ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No schedules found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <form action="<?php echo site_url('DozenDreams/importSchedules'); ?>" method="post" enctype="multipart/form-data" style="margin-top:20px;">
        <label>Import Schedules (Excel):</label>
        <input type="file" name="excel_file" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
