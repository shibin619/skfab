<!DOCTYPE html>
<html>
<head>
    <title>Contest Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-box {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .green-btn {
            background-color: #198754;
            color: #fff;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
        }
        .green-btn:disabled {
            background-color: #adb5bd !important;
            color: #fff;
            cursor: not-allowed;
        }
        .progress-bar {
            background-color: #28a745;
        }
        .prize-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .prize-rank {
            font-weight: bold;
            font-size: 1rem;
        }
        .prize-amount {
            color: #198754;
            font-size: 1.1rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="p-2">

<div class="container-fluid px-2">

    <div class="card p-3 mb-3">
        <h5 class="mb-1">Contest #<?= $contest['id'] ?> — <span class="text-muted"><?= $match_teams ?></span></h5>
        <small class="text-muted">Match Starts In: <span id="timeLeft">Calculating...</span></small>
    </div>

    <div class="card-box text-center">
        <h6 class="mb-2">🏆 Prize Pool</h6>
        <h3 class="text-success mb-0">₹<?= rtrim(rtrim($contest['prize_pool'], '0'), '.') ?> <?= strtoupper($contest['prize_type']) ?></h3>
        <small class="text-muted d-block">1st Prize: ₹<?= number_format($contest['first_prize']) ?></small>
        <small>Winning %: <?= $contest['winning_percent'] ?>%</small>
    </div>

    <div class="progress mb-2" style="height: 8px;">
        <div class="progress-bar" role="progressbar" style="width: <?= ($contest['total_spots'] > 0) ? (100 - (($contest['spots_left'] / $contest['total_spots']) * 100)) : 0 ?>%"></div>
    </div>
    <div class="d-flex justify-content-between mb-3">
        <small><?= number_format($contest['spots_left']) ?> Spots Left</small>
        <small><?= number_format($contest['total_spots']) ?> Total</small>
    </div>

    <?php if (isset($user_teams_joined) && $user_teams_joined > 0): ?>
        <div class="alert alert-info text-center fw-semibold">
            ✅ You have already joined with <strong><?= $user_teams_joined ?></strong> team<?= $user_teams_joined > 1 ? 's' : '' ?>.
        </div>
    <?php endif; ?>

    <div class="text-center mb-4">
        <?php
            $maxTeams = $contest['entry_limit'] ?? $contest['max_teams'] ?? 1;
            $remainingSpots = $contest['spots_left'] ?? 0;
            $canJoin = isset($user_teams_joined) && $user_teams_joined < $maxTeams && $remainingSpots > 0;
            $btnText = $canJoin
                ? "JOIN ₹" . number_format($contest['entry_fee']) . " (Max $maxTeams Teams)"
                : "JOINED WITH $user_teams_joined TEAM" . ($user_teams_joined > 1 ? 'S' : '');
        ?>
        <button class="green-btn w-100"
                onclick="<?= $canJoin ? 'goToTeamSelection(' . $contest['id'] . ')' : 'alert(`You have already joined with the maximum allowed teams.`)' ?>"
                <?= $canJoin ? '' : 'disabled' ?>>
            <?= $btnText ?>
        </button>
    </div>

    <h6 class="mb-3">🎁 Prize Breakup (Top <?= number_format($contest['winning_percent']) ?>%)</h6>

    <?php if (!empty($prize_breakup)): ?>
        <div class="row">
            <?php foreach ($prize_breakup as $row): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="prize-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="prize-rank">
                                <?= ($row['start_rank'] == $row['end_rank'])
                                    ? "#{$row['start_rank']}"
                                    : "#{$row['start_rank']} – #{$row['end_rank']}" ?>
                            </div>
                            <div class="prize-amount">
                                ₹<?= number_format($row['prize_per_user']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center text-muted">Prize breakup not available</div>
    <?php endif; ?>
</div>

<script>
    const timeLeftElem = document.getElementById("timeLeft");
    const matchTime = new Date("<?= isset($match_time) ? date('Y-m-d H:i:s', strtotime($match_time)) : date('Y-m-d H:i:s') ?>").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = matchTime - now;

        if (distance <= 0) {
            timeLeftElem.innerText = "Match Started";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

        timeLeftElem.innerText = `${hours}h ${mins}m`;
    }

    updateCountdown(); // Initial call
    setInterval(updateCountdown, 60000); // Every minute

    function goToTeamSelection(contestId) {
        window.location.href = "<?= site_url('DozenDreams/selectTeam/') ?>" + contestId;
    }
</script>

</body>
</html>
