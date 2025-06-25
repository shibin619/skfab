<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Teams</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
        }
        .team-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }
        .team-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .badge-role {
            padding: 3px 7px;
            font-size: 0.75rem;
            border-radius: 5px;
            color: white;
        }
        .badge-role-WK { background-color: #007bff; }
        .badge-role-BAT { background-color: #28a745; }
        .badge-role-AR { background-color: #ffc107; color: black; }
        .badge-role-BOWL { background-color: #17a2b8; }
        .badge-role-IMP { background-color: #6f42c1; }
        .captain-tag, .vc-tag {
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 5px;
            color: white;
        }
        .captain-tag { background-color: #e83e8c; }
        .vc-tag { background-color: #fd7e14; }
    </style>
</head>
<body class="p-3">

    <h4 class="mb-4">My Teams for Match #<?= $match_id ?></h4>

    <?php if (!empty($teams)): ?>
        <?php foreach ($teams as $team): ?>
            <div class="team-card">
                <div class="team-header">
                    <strong><?= $team['team_id'] ?></strong>
                    <small class="text-muted">Created: <?= date('d M Y, h:i A', strtotime($team['created_at'])) ?></small>
                </div>

                <?php
                    $player_ids = json_decode($team['players'], true);
                    $captain_id = $team['captain_id'];
                    $vc_id = $team['vice_captain_id'];
                    $players = $this->dozen->getPlayersByPlayerIds($player_ids); // You need this function in model
                ?>

                <ul class="list-group">
                    <?php foreach ($players as $p): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= $p['name'] ?></strong>
                                <span class="badge-role badge-role-<?= $p['role'] ?>"><?= $p['role'] ?></span>
                                <?php if ($p['id'] == $captain_id): ?>
                                    <span class="captain-tag">C</span>
                                <?php elseif ($p['id'] == $vc_id): ?>
                                    <span class="vc-tag">VC</span>
                                <?php endif; ?>
                                <br>
                                <small class="text-muted"><?= $p['team_name'] ?></small>
                            </div>
                            <div class="text-end">
                                <div><?= $p['points'] ?? rand(50,150) ?> pts</div>
                                <div><small><?= $p['credit_points'] ?> Cr</small></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">No teams created yet for this match.</div>
    <?php endif; ?>

</body>
</html>
