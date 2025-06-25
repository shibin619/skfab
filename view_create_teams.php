
<!-- Main Content -->
<div class="col-lg-9 col-md-8">
    <div class="p-3 rounded shadow-sm" style="background-color: #1c2e4a; color: #f1f1f1; box-shadow: 0 0 14px rgba(253, 216, 53, 0.15);">
    
    <h4 class="mb-4 text-center fw-bold" style="color: #fdd835;">
        📋 My Teams for Match:
        <?= htmlspecialchars($match_info['t1_short'] ?? $match_info['team1_name']) ?>
        vs
        <?= htmlspecialchars($match_info['t2_short'] ?? $match_info['team2_name']) ?>
    </h4>

    <?php if (!empty($user_teams)): ?>
        <div class="row g-3">
        <?php foreach ($user_teams as $index => $team): ?>
            <div class="col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm h-100" style="background-color: #26325b; color: #ffffff; border-radius: 12px;">
                <div class="card-body d-flex flex-column">
                
                <h5 class="card-title text-warning mb-3">
                    🧠 Team <?= $index + 1 ?>
                </h5>

                <div class="mb-2">
                    <strong>👑 Captain:</strong><br>
                    <?php if (!empty($team['captain'])): ?>
                    <img src="<?= base_url($team['captain']['image']) ?>" alt="Captain" class="rounded-circle me-2" style="width: 32px; height: 32px; border: 2px solid #fdd835;">
                    <?= htmlspecialchars($team['captain']['name']) ?>
                    <?php else: ?>N/A<?php endif; ?>
                </div>

                <div class="mb-2">
                    <strong>🧢 Vice-Captain:</strong><br>
                    <?php if (!empty($team['vice_captain'])): ?>
                    <img src="<?= base_url($team['vice_captain']['image']) ?>" alt="Vice Captain" class="rounded-circle me-2" style="width: 32px; height: 32px; border: 2px solid #fdd835;">
                    <?= htmlspecialchars($team['vice_captain']['name']) ?>
                    <?php else: ?>N/A<?php endif; ?>
                </div>

                <div class="my-3">
                    <span class="badge bg-primary me-1 rounded-pill px-3 py-1">
                    🟦 <?= htmlspecialchars($team['team1_name']) ?>: <?= $team['team1_count'] ?> players
                    </span><br>
                    <span class="badge bg-danger mt-2 rounded-pill px-3 py-1">
                    🟥 <?= htmlspecialchars($team['team2_name']) ?>: <?= $team['team2_count'] ?> players
                    </span>
                </div>

                <a href="<?= site_url('DozenDreams/viewTeam/' . $match_info['match_id'] . '/' . urlencode($team['team_key'])) ?>"
                    class="btn btn-sm btn-outline-warning w-100 mt-auto fw-semibold">
                    👁️ View Team
                </a>
                </div>
            </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4 rounded shadow-sm">
        🚫 No teams created yet for this match.
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="<?= site_url('DozenDreams/createTeam/' . $match_info['match_id']) ?>" class="btn btn-warning fw-bold px-4 py-2" style="border-radius: 30px; color: #1c2e4a;">
        ➕ Create New Team
        </a>
    </div>
    </div>
</div>

