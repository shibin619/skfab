<h2>Add Dream11 Point Rule</h2>
<?= validation_errors(); ?>
<form method="post">
    <label>Action:</label><br>
    <input type="text" name="action" value="<?= set_value('action') ?>"><br><br>

    <label>Role:</label><br>
    <select name="role_id">
        <option value="">Select Role</option>
        <option value="1">Batsman</option>
        <option value="2">Bowler</option>
        <option value="3">All-Rounder</option>
        <option value="4">Wicket-Keeper</option>
    </select><br><br>

    <label>Points:</label><br>
    <input type="text" name="points" value="<?= set_value('points') ?>"><br><br>

    <button type="submit">Add</button>
</form>
