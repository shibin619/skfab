<!-- Entry Info -->
<div class="d-flex justify-content-between align-items-center text-white mb-3">
    <span>You can enter with only <?= $contest['entry_limit'] ?> team(s)</span>
    <span>My Teams: <?= count($user_teams) ?></span>
</div>

<form id="teamForm" method="post">
    <?php foreach ($user_teams as $index => $team): ?>
        <?php
            $teamKey = $team['team_key'];
            $teamKeyClean = str_replace("team", "", $teamKey); // for submission
            $matchId = $match_info['id'];

            $captain = $team['captain'];
            $captainImgSrc = $captain['image'] ?? ASSETS_PATH . 'img/dozen/' . (strtoupper($captain['batting_hand_code'] ?? '') === 'LHB' ? 'lhb.png' : 'rhb.png');

            $vice = $team['vice_captain'];
            $vcImgSrc = $vice['image'] ?? ASSETS_PATH . 'img/dozen/' . (strtoupper($vice['batting_hand_code'] ?? '') === 'LHB' ? 'lhb.png' : 'rhb.png');

            $ownerImage = $team['owner_image'] ?? ASSETS_PATH . 'img/dozen/profile.png';
            $viewUrl = site_url("DozenDreams/viewTeam/{$matchId}/" . urlencode($teamKey));
        ?>
        <div class="card mb-3 shadow-sm team-card text-white" style="border-radius: 15px; background: linear-gradient(90deg, #004d40, #00695c);">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Team <?= $index + 1 ?></h5>
                    <input type="checkbox" class="team-checkbox" name="selected_teams[]" value="<?= $teamKeyClean ?>">
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <small><?= $match_info['team1_short'] ?? 'Team 1' ?> <?= $team['team1_count'] ?></small>
                    <small><?= $match_info['team2_short'] ?? 'Team 2' ?> <?= $team['team2_count'] ?></small>
                </div>

                <div class="row mb-2">
                    <div class="col"><small>WK <?= $team['wk_count'] ?? 0 ?></small></div>
                    <div class="col"><small>BAT <?= $team['bat_count'] ?? 0 ?></small></div>
                    <div class="col"><small>AR <?= $team['ar_count'] ?? 0 ?></small></div>
                    <div class="col"><small>BOWL <?= $team['bowl_count'] ?? 0 ?></small></div>
                </div>

                <div class="d-flex align-items-center mb-2">
                    <div class="me-2 text-center">
                        <img src="<?= $captainImgSrc ?>" class="rounded-circle" style="width: 40px; height: 40px; border: 2px solid #ffc107;">
                        <div class="small fw-bold">C</div>
                        <div class="small"><?= htmlspecialchars($captain['name'] ?? 'N/A') ?></div>
                    </div>
                    <div class="me-2 text-center">
                        <img src="<?= $vcImgSrc ?>" class="rounded-circle" style="width: 40px; height: 40px; border: 2px solid #e0e0e0;">
                        <div class="small fw-bold">VC</div>
                        <div class="small"><?= htmlspecialchars($vice['name'] ?? 'N/A') ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="<?= $ownerImage ?>" class="rounded-circle me-2" style="width: 30px; height: 30px;">
                        <span class="badge bg-light text-dark"><?= $contest['short_code'] ?? 'H2H' ?></span>
                    </div>
                    <a href="<?= $viewUrl ?>" class="btn btn-sm btn-warning text-dark">👁 View</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Hidden Fields -->
    <input type="hidden" name="match_id" value="<?= $match_info['match_id'] ?>">
    <input type="hidden" name="contest_id" value="<?= $contest['id'] ?>">

    <!-- Error Message -->
    <div id="validationMsg" class="text-danger text-center my-2 fw-bold d-none"></div>

    <!-- Total Join Amount Display -->
    <div class="text-white mb-2 mt-3 text-center">
        <strong>Total Join Amount:</strong>
        ₹<span id="totalJoinAmount">0</span>
    </div>

    <!-- Join Button -->
    <div class="text-center mt-2">
        <button type="submit" id="joinBtn" class="btn btn-light w-100 fw-bold">JOIN ₹0</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const entryFee = <?= (float) $contest['entry_fee'] ?>;
    const form = document.getElementById('teamForm');
    const checkboxes = form.querySelectorAll('.team-checkbox'); // ✅ scoped within form
    const totalAmountEl = document.getElementById('totalJoinAmount');
    const validationMsg = document.getElementById('validationMsg');
    const joinBtn = document.getElementById('joinBtn');
    const entryLimit = <?= (int) $contest['entry_limit'] ?>;

    function updateTotalAmount() {
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        const selectedCount = selectedCheckboxes.length;
        const totalAmount = selectedCount * entryFee;

        totalAmountEl.textContent = totalAmount.toFixed(2);
        joinBtn.textContent = `JOIN ₹${totalAmount.toFixed(2)}`;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            validationMsg.classList.add('d-none');
            updateTotalAmount();
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
        const selectedTeamKeys = selectedCheckboxes.map(cb => cb.value);
        const selectedCount = selectedTeamKeys.length;

        if (selectedCount === 0) {
            validationMsg.textContent = "❌ Please select at least one team to join.";
            validationMsg.classList.remove('d-none');
            return;
        }

        if (selectedCount > entryLimit) {
            validationMsg.textContent = `❌ You can only join with maximum ${entryLimit} team(s).`;
            validationMsg.classList.remove('d-none');
            return;
        }

        const match_id = form.match_id.value;
        const contest_id = form.contest_id.value;

        joinBtn.disabled = true;
        joinBtn.textContent = "Joining...";

        fetch("<?= base_url('DozenDreams/submitContestEntry') ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                match_id: match_id,
                contest_id: contest_id,
                team_keys: selectedTeamKeys
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = "<?= base_url('DozenDreams/contestDetailsView/') ?>" + contest_id;
            } else {
                validationMsg.textContent = data.message || "❌ Failed to join contest.";
                validationMsg.classList.remove('d-none');
                joinBtn.disabled = false;
                updateTotalAmount();
            }
        })
        .catch(err => {
            console.error(err);
            validationMsg.textContent = "❌ Something went wrong.";
            validationMsg.classList.remove('d-none');
            joinBtn.disabled = false;
            updateTotalAmount();
        });
    });

    updateTotalAmount();
});
</script>




