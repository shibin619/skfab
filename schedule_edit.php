<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">Edit Schedule</h4>

        <?= validation_errors('<div class="alert alert-warning">', '</div>'); ?>

        <form method="post" action="<?= base_url('DozenDreams/updateSchedule/' . $schedule['id']) ?>">
            <div class="mb-3">
                <label class="form-label">Tournament</label>
                <select name="tournament_id" class="form-select" required>
                    <option value="">Select Tournament</option>
                    <?php foreach ($tournaments as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $schedule['tournament_id'] == $t['id'] ? 'selected' : '' ?>>
                            <?= $t['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Team A</label>
                    <select name="team_a_id" class="form-select" required>
                        <option value="">Select Team A</option>
                        <?php foreach ($teams as $team): ?>
                            <option value="<?= $team['id'] ?>" <?= $schedule['team_a_id'] == $team['id'] ? 'selected' : '' ?>>
                                <?= $team['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Team B</label>
                    <select name="team_b_id" class="form-select" required>
                        <option value="">Select Team B</option>
                        <?php foreach ($teams as $team): ?>
                            <option value="<?= $team['id'] ?>" <?= $schedule['team_b_id'] == $team['id'] ? 'selected' : '' ?>>
                                <?= $team['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Match Date & Time</label>
                <input type="datetime-local" name="match_date" class="form-control"
                       value="<?= date('Y-m-d\TH:i', strtotime($schedule['match_date'])) ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="venue_id">Venue</label>
                <select name="venue_id" class="form-control" required>
                    <option value="">Select Venue</option>
                    <?php foreach ($venues as $venue): ?>
                        <option value="<?= $venue['id'] ?>"
                            <?= ($venue['id'] == $schedule['venue_id']) ? 'selected' : '' ?>>
                            <?= $venue['name'] ?> (<?= $venue['location'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">Select Status</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>"
                            <?= $schedule['status_id'] == $status['id'] ? 'selected' : '' ?>>
                            <?= $status['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-outline-light border-accent">Update Schedule</button>
        </form>
    </div>
</div>
