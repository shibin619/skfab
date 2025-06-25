<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - Dream11 Style</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b1d3a;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
        }
        .profile-header {
            background: url('https://cdn.pixabay.com/photo/2016/11/21/15/47/cricket-1848450_960_720.jpg') no-repeat center center;
            background-size: cover;
            padding: 40px 20px;
            color: white;
            position: relative;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            margin-bottom: 10px;
        }
        .stat-box {
            background: #10264b;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 0 12px rgba(255, 215, 0, 0.1);
        }
        .skill-score {
            font-size: 36px;
            font-family: 'Courier New', monospace;
            color: #ffd700;
        }
        .level-bar {
            height: 10px;
            background: #37496b;
            border-radius: 10px;
            overflow: hidden;
        }
        .level-bar-fill {
            width: 25%;
            background: #ffd700;
            height: 100%;
        }
        .form-control {
            background-color: #1a2f56;
            color: #fff;
            border: 1px solid #ffd700;
        }
        .btn-warning {
            background-color: #ffd700;
            color: #0b1d3a;
            font-weight: 600;
        }
        .copy-btn {
            background: none;
            border: none;
            color: #ffd700;
            font-size: 18px;
            margin-left: 8px;
            cursor: pointer;
        }
        .copy-btn:hover {
            color: #fff;
        }
    </style>
</head>
<body>

<div class="profile-header text-center">
    <img src="<?= ASSETS_PATH ?>img/dozen/profile.png" alt="Profile Image" class="profile-avatar">
    <h4 class="mb-0"><?= $profile['first_name'] . ' ' . $profile['last_name'] ?></h4>
    <p class="mb-0">@<?= strtolower(str_replace(' ', '', $profile['first_name'])) ?></p>
    <p class="mt-2 skill-score">Skill Score: 762</p>
</div>

<div class="container mt-4">
    <div class="row text-center mb-3">
        <div class="col">
            <p class="mb-1 fw-bold">22</p>
            <small>Followers</small>
        </div>
        <div class="col">
            <p class="mb-1 fw-bold">33</p>
            <small>Following</small>
        </div>
        <div class="col">
            <p class="mb-1 fw-bold">13</p>
            <small>Friends</small>
        </div>
    </div>

    <div class="stat-box mb-3">
        <h6>Champions Club</h6>
        <p class="text-muted mb-1">Expert Level</p>
        <p class="text-muted small">Earn 🪙 2,908 to upgrade your club level</p>
        <div class="level-bar">
            <div class="level-bar-fill"></div>
        </div>
    </div>

    <div class="stat-box mb-3">
        <div class="row text-center">
            <div class="col">
                <p class="fw-bold"><?= $profile['contests'] ?? '6,905' ?></p>
                <small>Contests</small>
            </div>
            <div class="col">
                <p class="fw-bold"><?= $profile['matches'] ?? '1,279' ?></p>
                <small>Matches</small>
            </div>
            <div class="col">
                <p class="fw-bold"><?= $profile['series'] ?? '269' ?></p>
                <small>Series</small>
            </div>
            <div class="col">
                <p class="fw-bold"><?= $profile['sports'] ?? '6' ?></p>
                <small>Sports</small>
            </div>
        </div>
    </div>

    <div class="stat-box mb-3">
        <h6>Recently Played</h6>
        <div class="d-flex justify-content-between mb-1">
            <div>
                <strong>DR vs CB</strong> <br>
                <small>Rugby - Jun 17, 2025</small>
            </div>
            <div class="text-end">
                <small>Highest Points</small><br>
                <strong>866.0</strong>
            </div>
        </div>
        <div class="text-muted">Dream Team: 946 pts | Teams Created: 5</div>
    </div>

    <!-- 🔗 Referral Code Box -->
    <div class="stat-box mb-4">
        <h6>Your Referral Code</h6>
        <div class="input-group">
            <input type="text" id="referralCode" class="form-control" value="<?= $profile['referral_code'] ?>" readonly>
            <button class="copy-btn" onclick="copyReferralCode()" title="Copy">
                📋
            </button>
        </div>
    </div>

    <div class="text-center mb-5">
        <a href="<?= base_url('DozenDreams/userProfileEdit') ?>" class="btn btn-warning btn-sm px-4">Edit Profile</a>
    </div>
</div>

<script>
    function copyReferralCode() {
        const input = document.getElementById("referralCode");
        input.select();
        input.setSelectionRange(0, 99999); // For mobile
        navigator.clipboard.writeText(input.value);
        alert("Referral Code copied: " + input.value);
    }
</script>

</body>
</html>
