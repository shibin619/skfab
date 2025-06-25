<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Team</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(to bottom, #4caf50 0%, #2e7d32 100%);
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .ground {
            padding: 20px 10px;
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.1);
        }

        .role-label {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-size: 14px;
            color: #eee;
        }

        .player-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .player {
            margin: 10px;
            text-align: center;
            position: relative;
            width: 120px;
        }

        .player img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            border: 2px solid #fff;
            object-fit: cover;
        }

        .player-name {
            margin-top: 5px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .player-meta {
            font-size: 11px;
            color: #ddd;
            line-height: 1.2;
        }

        .badge-role {
            position: absolute;
            top: -8px;
            background: gold;
            color: black;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 50%;
            user-select: none;
        }

        .badge-c {
            right: -8px;
        }

        .badge-vc {
            right: 20px;
            background: silver;
        }

        .back-btn {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="ground container py-4">
    <h2 class="text-center mb-2 text-warning"><?= $team_name ?? 'My Team' ?></h2>
    <p class="text-center small text-light mb-4">Match #<?= $match_id ?><?php if (!empty($created_at)): ?> | Created on <?= date('d M Y, h:i A', strtotime($created_at)) ?><?php endif; ?></p>

    <?php
    function render_players_by_role($players, $role) {
        $role_map = [
            'WK' => ['Wicket-Keeper', 'WK'],
            'BAT' => ['Batsman', 'BAT'],
            'AR'  => ['All-Rounder', 'AR'],
            'BOWL'=> ['Bowler', 'BOWL']
        ];

        $hasPlayers = false;

        foreach ($players as $p) {
            if (in_array($p->role_name, $role_map[$role])) {
                $hasPlayers = true;

                $imgSrc = !empty($p->image)
                ? base_url($p->image)
                : (strtoupper($p->batting_hand ?? '') === 'LHB'
                    ? ASSETS_PATH . 'img/dozen/lhb.png'
                    : ASSETS_PATH . 'img/dozen/rhb.png');
            

                echo '<div class="player">';
                echo '<img src="' . $imgSrc . '" alt="' . htmlspecialchars($p->name) . '">';

                if (!empty($p->c)) {
                    echo '<span class="badge-role badge-c">C</span>';
                }

                if (!empty($p->vc)) {
                    echo '<span class="badge-role badge-vc">VC</span>';
                }

                echo '<div class="player-name" title="' . htmlspecialchars($p->name) . '">' . htmlspecialchars($p->name) . '</div>';
                echo '<div class="player-meta">' . htmlspecialchars($p->team_name) . '</div>';
                echo '<div class="player-meta">' . htmlspecialchars($p->role_name) . '</div>';
                echo '</div>';
            }
        }

        if (!$hasPlayers) {
            echo '<div class="text-center text-light small">No players in this category.</div>';
        }
    }
    ?>

    <div class="role-label">Wicket-Keeper</div>
    <div class="player-row">
        <?php render_players_by_role($players, 'WK'); ?>
    </div>

    <div class="role-label">Batters</div>
    <div class="player-row">
        <?php render_players_by_role($players, 'BAT'); ?>
    </div>

    <div class="role-label">All-Rounders</div>
    <div class="player-row">
        <?php render_players_by_role($players, 'AR'); ?>
    </div>

    <div class="role-label">Bowlers</div>
    <div class="player-row">
        <?php render_players_by_role($players, 'BOWL'); ?>
    </div>

    <div class="back-btn">
        <a href="<?= site_url('DozenDreams/viewOrCreateTeam/' . $match_id) ?>" class="btn btn-light btn-sm">← Back to My Teams</a>
    </div>
</div>
</body>
</html>
