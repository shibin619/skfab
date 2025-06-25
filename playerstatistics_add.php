<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">➕ Add Player Statistics</h4>

        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
        <?= form_open('DozenDreams/playerStatisticsAdd'); ?>

            <div class="mb-3">
                <label class="form-label text-warning">Player</label>
                <select name="player_id" class="form-select bg-light text-dark" required>
                    <option value="">Select Player</option>
                    <?php foreach ($players as $player): ?>
                        <option value="<?= $player->id ?>" <?= set_select('player_id', $player->id) ?>>
                            <?= htmlspecialchars($player->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Runs</label>
                <input type="number" name="runs" class="form-control bg-light text-dark" value="<?= set_value('runs') ?>" min="0" step="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Balls Faced</label>
                <input type="number" name="balls_faced" class="form-control bg-light text-dark" value="<?= set_value('balls_faced') ?>" min="0" step="1">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Fours</label>
                <input type="number" name="fours" class="form-control bg-light text-dark" value="<?= set_value('fours') ?>" min="0" step="1">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Sixes</label>
                <input type="number" name="sixes" class="form-control bg-light text-dark" value="<?= set_value('sixes') ?>" min="0" step="1">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Wickets</label>
                <input type="number" name="wickets" class="form-control bg-light text-dark" value="<?= set_value('wickets') ?>" min="0" step="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Overs Bowled</label>
                <input type="number" step="0.1" min="0" name="overs_bowled" class="form-control bg-light text-dark" value="<?= set_value('overs_bowled') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Runs Conceded</label>
                <input type="number" name="runs_conceded" class="form-control bg-light text-dark" value="<?= set_value('runs_conceded') ?>" min="0" step="1">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Catches</label>
                <input type="number" name="catches" class="form-control bg-light text-dark" value="<?= set_value('catches') ?>" min="0" step="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Stumpings</label>
                <input type="number" name="stumpings" class="form-control bg-light text-dark" value="<?= set_value('stumpings') ?>" min="0" step="1">
            </div>

            <div class="mb-3">
                <label class="form-label text-warning">Status</label>
                <select name="status_id" class="form-select bg-light text-dark" required>
                    <option value="">Select Status</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>" <?= set_select('status_id', $status['id']) ?>>
                            <?= htmlspecialchars($status['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">➕ Add Statistics</button>
            <a href="<?= site_url('dozendreams/playerStatisticsList') ?>" class="btn btn-light">← Back</a>
        <?= form_close(); ?>
    </div>
</div>
