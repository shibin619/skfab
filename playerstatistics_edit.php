<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">✏️ Edit Player Statistics</h4>

        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
        <?= form_open('DozenDreams/playerStatisticsEdit/' . $stat->id); ?>

            <div class="mb-3">
                <label class="form-label text-warning">Player</label>
                <select name="player_id" class="form-select bg-light text-dark" required>
                    <option value="">Select Player</option>
                    <?php foreach ($players as $player): ?>
                        <option value="<?= $player->id ?>" <?= $player->id == $stat->player_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($player->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Runs</label>
                <input type="number" name="runs" class="form-control bg-light text-dark" value="<?= $stat->runs ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Balls Faced</label>
                <input type="number" name="balls_faced" class="form-control bg-light text-dark" value="<?= $stat->balls_faced ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Fours</label>
                <input type="number" name="fours" class="form-control bg-light text-dark" value="<?= $stat->fours ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Sixes</label>
                <input type="number" name="sixes" class="form-control bg-light text-dark" value="<?= $stat->sixes ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Wickets</label>
                <input type="number" name="wickets" class="form-control bg-light text-dark" value="<?= $stat->wickets ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Overs Bowled</label>
                <input type="number" step="0.1" name="overs_bowled" class="form-control bg-light text-dark" value="<?= $stat->overs_bowled ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Runs Conceded</label>
                <input type="number" name="runs_conceded" class="form-control bg-light text-dark" value="<?= $stat->runs_conceded ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Catches</label>
                <input type="number" name="catches" class="form-control bg-light text-dark" value="<?= $stat->catches ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Stumpings</label>
                <input type="number" name="stumpings" class="form-control bg-light text-dark" value="<?= $stat->stumpings ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Status</label>
                <select name="status_id" class="form-select bg-light text-dark" required>
                    <option value="">Select Status</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status->id ?>" <?= $status->id == $stat->status_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">✅ Update</button>
            <a href="<?= site_url('dozendreams/playerStatisticsList') ?>" class="btn btn-light">← Cancel</a>
        <?= form_close(); ?>
    </div>
</div>
