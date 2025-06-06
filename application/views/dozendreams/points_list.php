<h2>Dream11 Point Rules</h2>
<a href="<?= site_url('DozenDreams/points_add') ?>">Add New Rule</a>
<?= $this->session->flashdata('success') ?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Action</th>
        <th>Role</th>
        <th>Points</th>
        <th>Action</th>
    </tr>
    <?php foreach($points as $point): ?>
    <tr>
        <td><?= $point['id'] ?></td>
        <td><?= $point['action'] ?></td>
        <td>
            <?php
                $roles = [1 => 'Batsman', 2 => 'Bowler', 3 => 'All-Rounder', 4 => 'Wicket-Keeper'];
                echo $roles[$point['role_id']];
            ?>
        </td>
        <td><?= $point['points'] ?></td>
        <td>
            <a href="<?= site_url('DozenDreams/points_edit/' . $point['id']) ?>">Edit</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
