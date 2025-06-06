<h2>Edit Dream11 Point Rule</h2>
<?= validation_errors(); ?>
<form method="post">
    <label>Action:</label><br>
    <input type="text" name="action" value="<?= set_value('action', $point['action']) ?>"><br><br>

    <label>Role:</label><br>
    <select name="role_id">
        <?php
        $roles = [1 => 'Batsman', 2 => 'Bowler', 3 => 'All-Rounder', 4 => 'Wicket-Keeper'];
        foreach ($roles as $id => $name) {
            $selected = ($point['role_id'] == $id) ? 'selected' : '';
            echo "<option value='$id' $selected>$name</option>";
        }
        ?>
    </select><br><br>

    <label>Points:</label><br>
    <input type="text" name="points" value="<?= set_value('points', $point['points']) ?>"><br><br>

    <button type="submit">Update</button>
</form>
