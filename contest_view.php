<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #093f64; color: #ffffff;">
        <h4 class="mb-4" style="color: #fdd835;">🏆 Contests for Match #<?= htmlspecialchars($match_id) ?></h4>

        <?php if (!empty($contests)): ?>
            <?php foreach ($contests as $contest): ?>
                <div class="contest-card mb-4 p-3 rounded" style="background-color: #1c2833; box-shadow: 0 4px 6px rgba(0,0,0,0.4);">
                    <div class="row pb-2 border-bottom border-secondary">
                        <div class="col-md-8">
                            <div class="h5 text-warning mb-2">
                                💰 Prize Pool: ₹<?= number_format($contest['prize_pool']) ?> <?= htmlspecialchars($contest['prize_type']) ?>
                            </div>
                            <div class="text-white small">
                                🎯 Entry Fee: ₹<?= number_format($contest['entry_fee'], 2) ?><br>
                                🥇 1st Prize: ₹<?= number_format($contest['first_prize']) ?><br>
                                🏆 Winning %: <?= intval($contest['winning_percent']) ?>%
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="text-white small">
                                👥 Total Spots: <?= intval($contest['total_spots']) ?><br>
                                🟢 Spots Left: <?= intval($contest['spots_left']) ?><br>
                                🔁 Max Teams/User: <?= intval($contest['max_teams']) ?>
                            </div>

                            <a href="<?= site_url('DozenDreams/joinContest/' . $contest['id']) ?>" class="btn btn-sm btn-outline-warning mt-2">Join Now</a>

                            <br>
                            <a href="<?= site_url('DozenDreams/viewOrCreateTeam/' . $contest['match_id'] . '/' . $contest['id']) ?>" class="btn btn-sm btn-outline-info mt-2">View/Create Team</a>
                        </div>
                    </div>

                    <div class="text-end mt-2 text-secondary small">
                        Created: <?= !empty($contest['created_at']) ? date('d M Y, h:i A', strtotime($contest['created_at'])) : 'N/A' ?><br>
                        Updated: <?= !empty($contest['updated_at']) ? date('d M Y, h:i A', strtotime($contest['updated_at'])) : 'N/A' ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No contests found for this match.
            </div>
        <?php endif; ?>

        <div class="mt-3">
            <a href="<?= site_url('DozenDreams/allMatches') ?>" class="btn btn-secondary btn-sm">← Back to Matches</a>
        </div>
    </div>
</div>
