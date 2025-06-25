<!-- application/views/dozendreams/create_team.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Team</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .player-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
        }
        .player-info {
            display: flex;
            align-items: center;
        }
        .player-info img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .top-bar {
            background: #ffffff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .tab-btn {
            cursor: pointer;
        }
        .tab-btn.active {
            border-bottom: 2px solid red;
            font-weight: bold;
        }
        .announced {
            font-size: 0.8em;
            color: green;
        }
    </style>
</head>
<body class="p-3">

<div class="top-bar">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>Players: <strong>5/11</strong></div>
        <div>MI: <strong>4</strong> | DC: <strong>1</strong></div>
        <div>Credits Left: <strong>46.5</strong></div>
    </div>
    <div class="d-flex justify-content-between">
        <div><small>Pitch:</small> <strong><?= $selected_pitch->name ?? 'N/A' ?></strong></div>
        <div><small>Avg Score:</small> <strong><?= $selected_pitch->average_score ?? 'N/A' ?></strong></div>
        <div><small>Spinners:</small> <strong><?= $selected_pitch->spin_support ?? 'N/A' ?></strong></div>
    </div>
</div>

<!-- Category Tabs -->
<ul class="nav nav-tabs mb-3" id="playerTabs">
    <li class="nav-item">
        <a class="nav-link tab-btn active" data-tab="WK">WK (<?= count($players_by_role['WK']) ?>)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link tab-btn" data-tab="BAT">BAT (<?= count($players_by_role['BAT']) ?>)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link tab-btn" data-tab="AR">AR (<?= count($players_by_role['AR']) ?>)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link tab-btn" data-tab="BOWL">BOWL (<?= count($players_by_role['BOWL']) ?>)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link tab-btn" data-tab="IMP">IMP (<?= count($players_by_role['IMP']) ?>)</a>
    </li>
</ul>

<div class="mb-2"><strong>Select 3 - 6 Players</strong></div>

<!-- Player List -->
<div id="player-list">
    <?php foreach ($players_by_role['WK'] as $player): ?>
        <div class="player-card player-role-WK">
            <div class="player-info">
                <img src="<?= $player->image ?: 'https://via.placeholder.com/50' ?>" alt="<?= $player->name ?>">
                <div>
                    <strong><?= $player->name ?></strong><br>
                    <small><?= $player->team_name ?></small><br>
                    <?php if ($player->fitness_status === 'Fit'): ?>
                        <span class="announced">Announced</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div><?= $player->points ?? rand(50,150) ?> pts</div>
                <div><?= $player->credit_points ?> Cr</div>
                <button class="btn btn-success btn-sm">+</button>
            </div>
        </div>
    <?php endforeach; ?>

    <?php foreach ($players_by_role['BAT'] as $player): ?>
        <div class="player-card player-role-BAT d-none">
            <div class="player-info">
                <img src="<?= $player->image ?: 'https://via.placeholder.com/50' ?>" alt="<?= $player->name ?>">
                <div>
                    <strong><?= $player->name ?></strong><br>
                    <small><?= $player->team_name ?></small><br>
                    <?php if ($player->fitness_status === 'Fit'): ?>
                        <span class="announced">Announced</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div><?= $player->points ?? rand(50,150) ?> pts</div>
                <div><?= $player->credit_points ?> Cr</div>
                <button class="btn btn-success btn-sm">+</button>
            </div>
        </div>
    <?php endforeach; ?>

    <?php foreach ($players_by_role['AR'] as $player): ?>
        <div class="player-card player-role-AR d-none">
            <div class="player-info">
                <img src="<?= $player->image ?: 'https://via.placeholder.com/50' ?>" alt="<?= $player->name ?>">
                <div>
                    <strong><?= $player->name ?></strong><br>
                    <small><?= $player->team_name ?></small><br>
                    <?php if ($player->fitness_status === 'Fit'): ?>
                        <span class="announced">Announced</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div><?= $player->points ?? rand(50,150) ?> pts</div>
                <div><?= $player->credit_points ?> Cr</div>
                <button class="btn btn-success btn-sm">+</button>
            </div>
        </div>
    <?php endforeach; ?>

    <?php foreach ($players_by_role['BOWL'] as $player): ?>
        <div class="player-card player-role-BOWL d-none">
            <div class="player-info">
                <img src="<?= $player->image ?: 'https://via.placeholder.com/50' ?>" alt="<?= $player->name ?>">
                <div>
                    <strong><?= $player->name ?></strong><br>
                    <small><?= $player->team_name ?></small><br>
                    <?php if ($player->fitness_status === 'Fit'): ?>
                        <span class="announced">Announced</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div><?= $player->points ?? rand(50,150) ?> pts</div>
                <div><?= $player->credit_points ?> Cr</div>
                <button class="btn btn-success btn-sm">+</button>
            </div>
        </div>
    <?php endforeach; ?>

    <?php foreach ($players_by_role['IMP'] as $player): ?>
        <div class="player-card player-role-IMP d-none">
            <div class="player-info">
                <img src="<?= $player->image ?: 'https://via.placeholder.com/50' ?>" alt="<?= $player->name ?>">
                <div>
                    <strong><?= $player->name ?></strong><br>
                    <small><?= $player->team_name ?></small><br>
                    <?php if ($player->fitness_status === 'Fit'): ?>
                        <span class="announced">Announced</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div><?= $player->points ?? rand(50,150) ?> pts</div>
                <div><?= $player->credit_points ?> Cr</div>
                <button class="btn btn-success btn-sm">+</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $('.tab-btn').click(function () {
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        const tab = $(this).data('tab').toUpperCase();

        // Hide all
        $('.player-card').addClass('d-none');
        // Show selected
        $('.player-role-' + tab).removeClass('d-none');
    });

    $(document).ready(function () {
        $('.tab-btn.active').trigger('click'); // Initial trigger for default tab
    });
</script>
</body>
</html>
