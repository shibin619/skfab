<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Captain & Vice-Captain</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121b3d;
            color: #fdd835;
        }
        .player-card {
            background-color: #1e2a5a;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0 6px rgba(255, 215, 53, 0.15);
        }
        .player-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .player-info img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            border: 2px solid #fdd835;
        }
        .role-select input {
            margin-right: 5px;
        }
        .btn-success {
            background-color: #fdd835;
            color: #1e2a5a;
            font-weight: bold;
        }
        .btn-success:hover {
            background-color: #fff176;
            color: #1e2a5a;
        }
    </style>
</head>
<body class="p-3">

<h3 class="text-center mb-4">👑 Select Captain & Vice-Captain</h3>

<form method="post" action="<?= base_url('DozenDreams/saveCreatedTeam') ?>" novalidate>

    <input type="hidden" name="match_id" value="<?= $match_id ?>">

    <?php foreach ($players as $player): ?>
        <input type="hidden" name="players[]" value="<?= $player->id ?>">

        <?php
            $imgSrc = !empty($player->image)
                ? htmlspecialchars($player->image)
                : (strtoupper($player->batting_hand_code) === 'LHB'
                    ? ASSETS_PATH . 'img/dozen/lhb.png'
                    : ASSETS_PATH . 'img/dozen/rhb.png');
        ?>

        <div class="player-card">
            <div class="player-info">
                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($player->name) ?>">
                <div>
                    <strong><?= htmlspecialchars($player->name) ?></strong><br>
                    <small><?= htmlspecialchars($player->team_name) ?> | <?= htmlspecialchars($player->role_name ?? 'Role') ?></small>
                </div>
            </div>
            <div class="role-select d-flex gap-3">
                <label><input type="radio" name="captain_id" value="<?= $player->id ?>" required> Captain</label>
                <label><input type="radio" name="vice_captain_id" value="<?= $player->id ?>" required> Vice-Captain</label>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="text-center mt-4 d-flex justify-content-center gap-3">
    <button type="submit" name="action" value="finalize" class="btn btn-success px-4">✅ Finalize Team</button>

<button type="submit" name="action" value="back" class="btn btn-outline-light px-4">🔙 Back to Edit Players</button>

    </div>
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let submitAction = 'finalize'; // default

    // Track which button was clicked
    $('button[type="submit"]').on('click', function () {
        submitAction = $(this).val();
    });

    $('form').on('submit', function (e) {
        e.preventDefault();

        const captain = $('input[name="captain_id"]:checked').val();
        const viceCaptain = $('input[name="vice_captain_id"]:checked').val();

        if (submitAction === 'back') {
            // 👈 Go to createTeamViewFromCaptain without validation
            this.action = "<?= base_url('DozenDreams/createTeamViewFromCaptain') ?>";
            this.submit();
            return;
        }

        if (!captain || !viceCaptain) {
            alert("Please select both Captain and Vice-Captain.");
            return;
        }

        if (captain === viceCaptain) {
            alert("Captain and Vice-Captain must be different players.");
            return;
        }

        // ✅ All good, post to saveCreatedTeam
        this.action = "<?= base_url('DozenDreams/saveCreatedTeam') ?>";
        this.submit();
    });
</script>



</body>
</html>
