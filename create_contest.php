<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #3f51b5; color: #f1f1f1;">
        <h4 class="mb-4" style="color: #fdd835;">Create New Contest</h4>

        <form method="post" action="<?= base_url('DozenDreams/saveContest') ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Match</label>
                    <select name="match_id" class="form-control" required>
                        <option value="">-- Select Match --</option>
                        <?php foreach ($matches as $match): ?>
                            <option value="<?= $match['id'] ?>">
                                <?= $match['team_a'] ?> vs <?= $match['team_b'] ?> - <?= date('d M Y, h:i A', strtotime($match['match_date'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Prize Pool</label>
                    <input type="number" step="0.01" name="prize_pool" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Prize Type</label>
                    <select name="prize_type" class="form-control">
                        <option value="Lakhs">Lakhs</option>
                        <option value="Crores">Crores</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Entry Fee</label>
                    <input type="number" step="0.01" name="entry_fee" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">First Prize</label>
                    <input type="number" name="first_prize" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Total Spots</label>
                    <input type="number" name="total_spots" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Spots Left</label>
                    <input type="number" name="spots_left" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Winning Percentage</label>
                    <input type="number" name="winning_percent" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-warning">Max Teams Allowed</label>
                    <input type="number" name="max_teams" class="form-control" required>
                </div>
            </div>

            <!-- Optional: CSRF protection -->
            <!--
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
                   value="<?= $this->security->get_csrf_hash(); ?>" />
            -->

            <button class="btn btn-warning text-dark">Create Contest</button>
        </form>
    </div>
</div>
