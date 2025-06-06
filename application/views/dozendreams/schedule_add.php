<!DOCTYPE html>
<html>
<head>
    <title>Add Schedule</title>
</head>
<body>
    <h1>Add New Schedule</h1>

    <form method="post" action="<?php echo site_url('DozenDreams/insertSchedule'); ?>">
        <label>Tournament:</label>
        <select name="tournament_id" required>
            <option value="">Select Tournament</option>
            <?php foreach ($tournaments as $t): ?>
                <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Team A:</label>
        <select name="team_a_id" required>
            <option value="">Select Team A</option>
            <?php foreach ($teams as $team): ?>
                <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Team B:</label>
        <select name="team_b_id" required>
            <option value="">Select Team B</option>
            <?php foreach ($teams as $team): ?>
                <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Match Date:</label>
        <input type="date" name="match_date" required><br><br>

        <label>Venue:</label>
        <input type="text" name="venue" required><br><br>

        <label>Status:</label>
        <input type="text" name="status" required><br><br>

        <button type="submit">Add Schedule</button>
    </form>

    <br>
    <a href="<?php echo site_url('DozenDreams/scheduleList'); ?>">Back to List</a>
</body>
</html>
