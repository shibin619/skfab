<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #093f64; color: #ffffff;">
        <h4 class="mb-4" style="color: #fdd835;">🎯 Match Generation Summary</h4>

        <?php if ($inserted_count > 0): ?>
            <div class="alert alert-success">✅ <?= $inserted_count ?> match(es) inserted successfully.</div>
            <ul class="list-group">
                <?php foreach ($schedules as $match): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start bg-dark text-light mb-2">
                        <div>
                            <strong><?= $match['team1_name'] ?> 🆚 <?= $match['team2_name'] ?></strong><br>
                            <small><?= $match['venue'] ?> | <?= date('d M Y, h:i A', strtotime($match['match_date'])) ?></small>
                        </div>
                        <span class="badge bg-success">Inserted</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning text-center">⚠️ No upcoming matches found for insertion.</div>
        <?php endif; ?>

        <div class="mt-4 text-end">
            <a href="<?= base_url('DozenDreams/dashboard'); ?>" class="btn btn-light btn-sm">Back to Dashboard</a>
        </div>
    </div>
</div>
