<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Team</title>
    <link rel="icon" type="image/png" href="<?= ASSETS_PATH ?>img/dozen/login.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #0f1a36;
            color: #fdd835;
            font-family: 'Segoe UI', sans-serif;
        }
        .top-bar {
            background: #1c2e4a;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 8px rgba(253, 216, 53, 0.2);
        }
        .nav-tabs .nav-link {
            background: transparent;
            color: #ccc;
            font-weight: 500;
            padding: 10px 15px;
        }
        .nav-tabs .nav-link.active {
            color: #fdd835;
            border-bottom: 3px solid #fdd835;
            font-weight: bold;
        }
        .player-card {
            background-color: #1c2e4a;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .player-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1 1 auto;
        }
        .player-info img {
            height: 52px;
            width: 52px;
            border-radius: 50%;
            border: 2px solid #fdd835;
        }
        .player-info strong {
            color: #fff;
        }
        .player-right {
            text-align: right;
            min-width: 90px;
        }
        .btn-submit {
            background-color: #fdd835;
            color: #1e2a5a;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
        }
        .btn-submit:hover {
            background-color: #fff176;
        }
        .badge {
            font-size: 0.75em;
        }

        /* === Circle Checkbox Style === */
        .custom-circle-checkbox {
            position: relative;
            cursor: pointer;
            width: 24px;
            height: 24px;
            display: inline-block;
        }
        .custom-circle-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .custom-circle-checkbox .checkmark {
            height: 24px;
            width: 24px;
            background-color: transparent;
            border: 2px solid #fdd835;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.3s, border-color 0.3s;
            position: relative;
        }
        .custom-circle-checkbox input:checked + .checkmark {
            background-color: #fdd835;
            border-color: #fdd835;
        }
        .custom-circle-checkbox .checkmark::after {
            content: "";
            position: absolute;
            display: none;
        }
        .custom-circle-checkbox input:checked + .checkmark::after {
            display: block;
        }
        .custom-circle-checkbox .checkmark::after {
            top: 5px;
            left: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #1e2a5a;
        }
    </style>

</head>

<body class="p-3">

<form method="post" action="<?= base_url('DozenDreams/selectCaptainView') ?>">

    <input type="hidden" name="match_id" value="<?= $match_id ?>">

    <div class="top-bar mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
            <div>Players: <strong>0/11</strong></div>
            <div><?= $match_teams ?> Teams</div>
            <div>Credits Left: <strong>100</strong></div>
        </div>
        <div class="d-flex justify-content-between flex-wrap text-light small">
            <div><strong>Pitch:</strong> <?= $selected_pitch->name ?? 'N/A' ?></div>
            <div><strong>Avg Score:</strong> <?= $selected_pitch->average_score ?? 'N/A' ?></div>
            <div><strong>Type:</strong> <?= $selected_pitch->pitch_type ?? 'N/A' ?></div>
        </div>
    </div>

    <!-- Role Tabs -->
    <ul class="nav nav-tabs mb-3" id="playerTabs">
        <?php foreach ($players_by_role as $role => $list): ?>
            <li class="nav-item">
                <a class="nav-link tab-btn <?= $role == 'WK' ? 'active' : '' ?>" data-tab="<?= $role ?>">
                    <?= $role ?> <span class="role-count" data-role="<?= $role ?>"></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Players by Role -->
    <div id="player-list">
        <?php foreach ($players_by_role as $role => $players): ?>
            <?php foreach ($players as $player): ?>
                <?php
                    $imgSrc = !empty($player->image) ? htmlspecialchars($player->image) :
                              (strtoupper($player->batting_hand_code) === 'LHB' 
                                  ? ASSETS_PATH . 'img/dozen/lhb.png' 
                                  : ASSETS_PATH . 'img/dozen/rhb.png');
                    $isChecked = isset($preselected_players) && in_array($player->id, $preselected_players);
                ?>
                <div class="player-card player-role-<?= $role ?> <?= $role !== 'WK' ? 'd-none' : '' ?>">
                    <div class="player-info">
                        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($player->name) ?>" />
                        <div>
                            <strong><?= htmlspecialchars($player->name) ?></strong><br>
                            <small><?= htmlspecialchars($player->team_name) ?></small><br>
                            <?php if (strtolower($player->fitness_status) === 'fit'): ?>
                                <span class="badge bg-success text-light">Announced</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($player->fitness_status) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="player-right text-end d-flex flex-column align-items-end">
                        <div class="fw-bold text-light mb-1"><?= $player->points ?? rand(50, 150) ?> pts</div>
                        <div class="fw-bold text-warning mb-2"><?= $player->credit_points ?> Cr</div>
                        <label class="custom-circle-checkbox">
                            <input type="checkbox" name="players[]" value="<?= $player->id ?>" <?= $isChecked ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                        </label>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-submit px-5">✅ Submit Team</button>
    </div>

</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        updatePlayerCount();
        updateAllRoleTabCounts();
        $('.tab-btn.active').trigger('click');
    });

    $('.tab-btn').on('click', function () {
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');

        const selectedTab = $(this).data('tab').toUpperCase();
        $('.player-card').addClass('d-none');
        $('.player-role-' + selectedTab).removeClass('d-none');
    });

    function updatePlayerCount() {
        const total = $('input[name="players[]"]:checked').length;
        $('.top-bar strong').eq(0).text(total + '/11');
    }

    function updateAllRoleTabCounts() {
        <?php foreach ($players_by_role as $role => $players): ?>
            const count_<?= $role ?> = $('.player-role-<?= $role ?> input[name="players[]"]:checked').length;
            $('.role-count[data-role="<?= $role ?>"]').text('(' + count_<?= $role ?> + ')');
        <?php endforeach; ?>
    }

    $('body').on('change', 'input[name="players[]"]', function () {
        const total = $('input[name="players[]"]:checked').length;

        if (total > 11) {
            $(this).prop('checked', false);
            alert('You can select a maximum of 11 players.');
        }

        updatePlayerCount();
        updateAllRoleTabCounts();
    });

    function validateTeam() {
        const roleCounts = {};
        <?php foreach ($players_by_role as $role => $players): ?>
            roleCounts['<?= $role ?>'] = 0;
        <?php endforeach; ?>

        let totalSelected = 0;
        $('input[name="players[]"]:checked').each(function () {
            const playerCard = $(this).closest('.player-card');
            const roleMatch = playerCard.attr('class').match(/player-role-([A-Z]+)/);
            if (roleMatch && roleMatch[1]) {
                const role = roleMatch[1];
                roleCounts[role] = (roleCounts[role] || 0) + 1;
            }
            totalSelected++;
        });

        if (totalSelected !== 11) {
            alert('You must select exactly 11 players. Currently selected: ' + totalSelected);
            return false;
        }

        for (let role in roleCounts) {
            if (roleCounts[role] === 0) {
                alert('Select at least one player from each role. Missing: ' + role);
                return false;
            }
        }

        return true;
    }

    $('form').on('submit', function (e) {
        if (!validateTeam()) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>
