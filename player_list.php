<!DOCTYPE html>
<html>
<head>
    <title>DozenDreams - Player Selection</title>
    <link rel="icon" type="image/png" href="<?= ASSETS_PATH ?>img/dozen/login.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #0b1d3a;
        color: #ffffff;
        font-family: 'Segoe UI', sans-serif;
    }

    .card {
        background-color: #10264b;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.1);
    }

    .form-label, h3 {
        color: #ffd700;
    }

    .form-control,
    .form-select {
        background-color: #1a2f56;
        color: #ffffff;
        border: 1px solid #ffd700;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 10px #ffd700;
        border-color: #ffd700;
    }

    .btn-success {
        background-color: #ffd700;
        color: #0b1d3a;
        font-weight: bold;
        border: none;
    }

    .btn-success:hover {
        background-color: #e6c200;
    }

    .form-check-label {
        font-size: 0.95rem;
    }

    .hidden {
        display: none !important;
    }

    #playerSelectionSection {
        min-height: 150px;
    }

    #playerCheckboxes {
        min-height: 150px;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
        border: 1px dashed #ffd700;
        padding: 0.5rem;
        border-radius: 0.5rem;
    }

    #playerCheckboxes .form-check {
        background-color: #1c355e;
        padding: 0.5rem;
        border-radius: 0.5rem;
    }

    #playerCheckboxes .form-check-label {
        font-size: 0.95rem;
        color: #ffd700; /* ✅ Gold for visibility */
        font-weight: 500;
    }

    #playerCheckboxes:empty::before {
        content: "No players available";
        color: #ccc;
        font-style: italic;
    }
</style>



</head>
<body class="py-5">
<div class="container">
    <div class="card mx-auto" style="max-width: 900px;">
        <h3 class="text-center mb-4">🧢 Select Your Players + CAP + VC</h3>
        <form method="post" action="<?= site_url('DozenDreams/generate') ?>" id="playerForm">

            <!-- Match Selection -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="match_id" class="form-label">Select Match</label>
                    <select name="match_id" id="match_id" class="form-select" required>
                        <option value="">-- Choose Match --</option>
                        <?php foreach ($scheduled_matches as $match): ?>
                            <option value="<?= $match['match_id'] ?>" <?= ($selectedMatch == $match['match_id']) ? 'selected' : '' ?>>
                                <?= $match['tournament'] ?>: <?= $match['team_a'] ?> vs <?= $match['team_b'] ?> (<?= date('d M, h:i A', strtotime($match['match_date'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Captain & Vice Captain -->
            <div class="row mb-4 hidden" id="captainViceSection">
                <div class="col-md-4">
                    <label class="form-label">Captain</label>
                    <select name="captain" id="captain" class="form-select" ></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vice Captain</label>
                    <select name="vice_captain" id="vice_captain" class="form-select" ></select>
                </div>
            </div>

            <!-- Player Selection -->
            <div class="mb-4 hidden" id="playerSelectionSection">
                <label class="form-label">Select Players (Max 9):</label>
                <div class="row" id="playerCheckboxes"></div>
            </div>

            <!-- Opponent Team -->
            <div class="mb-4">
                <label class="form-label d-block">Generate Based on Opponent Team:</label>
                <div class="btn-group" role="group" aria-label="Opponent Toggle">
                    <input type="radio" class="btn-check" name="opponent_team" id="oppoYes" value="Yes" autocomplete="off" <?= ($opponentTeamId == 'Yes') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="oppoYes">Yes</label>

                    <input type="radio" class="btn-check" name="opponent_team" id="oppoNo" value="No" autocomplete="off" <?= ($opponentTeamId == 'No') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="oppoNo">No</label>
                </div>
            </div>

            <!-- Pitch Type -->
            <div class="mb-4">
                <label class="form-label d-block">Generate Basen on Pitch Type:</label>
                <div class="btn-group" role="group" aria-label="Pitch Type Toggle">
                    <input type="radio" class="btn-check" name="pitch_type" id="pitchYes" value="Yes" autocomplete="off" <?= ($pitchType == 'Yes') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="pitchYes">Yes</label>

                    <input type="radio" class="btn-check" name="pitch_type" id="pitchNo" value="No" autocomplete="off" <?= ($pitchType == 'No') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="pitchNo">No</label>
                </div>
            </div>

            <!-- Weather -->
            <div class="mb-4">
                <label class="form-label d-block">Generate Based on Weather Condition:</label>
                <div class="btn-group" role="group" aria-label="Weather Toggle">
                    <input type="radio" class="btn-check" name="weather" id="weatherYes" value="Yes" autocomplete="off" <?= ($weatherCondition == 'Yes') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="weatherYes">Yes</label>

                    <input type="radio" class="btn-check" name="weather" id="weatherNo" value="No" autocomplete="off" <?= ($weatherCondition == 'No') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="weatherNo">No</label>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label d-block">Generate Based on Knockouts:</label>
                <div class="btn-group" role="group" aria-label="Knockouts Toggle">
                    <input type="radio" class="btn-check" name="knockout_stage" id="knockoutYes" value="Yes" autocomplete="off" <?= ($knockoutStage == 'Yes') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="knockoutYes">Yes</label>

                    <input type="radio" class="btn-check" name="knockout_stage" id="knockoutNo" value="No" autocomplete="off" <?= ($knockoutStage == 'No') ? 'checked' : '' ?>>
                    <label class="btn btn-outline-warning" for="knockoutNo">No</label>
                </div>
            </div>

            <?php
            // Fallback names if no match is selected
            $teamA = $selectedMatchData['team_a'] ?? 'Team A';
            $teamB = $selectedMatchData['team_b'] ?? 'Team B';
            ?>
            <div class="mb-4">
                <label class="form-label d-block">🧮 Players Distribution Between Teams (Total 11)</label>

                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                    <div class="text-start" style="width: 45%;">
                        <span class="d-block text-warning fw-bold"><?= htmlspecialchars($teamA) ?></span>
                        <span class="fs-6">Players: <strong id="teamACount" style="color: #ffd700;">5</strong></span>
                    </div>
                    <div class="text-end" style="width: 45%;">
                        <span class="d-block text-warning fw-bold"><?= htmlspecialchars($teamB) ?></span>
                        <span class="fs-6">Players: <strong id="teamBCount" style="color: #ffd700;">6</strong></span>
                    </div>
                </div>

                <input type="range" name="team_a_count" id="teamSlider" min="1" max="10" value="5" class="form-range">

                <input type="hidden" name="team_b_count" id="teamBInput" value="6">
            </div>

            <!-- Team Limit -->
            <div class="mb-4">
                <label class="form-label">Max Teams to Generate</label>
                <input type="number" name="limit" value="<?= htmlspecialchars($teamLimit) ?>" class="form-control" min="1" max="40" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">🚀 Generate Teams</button>
            </div>
            <div id="softWarning" class="text-warning text-center mt-3"></div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    const allPlayers = <?= json_encode($players) ?>;
    const selectedMatchId = "<?= $selectedMatch ?>";

    document.addEventListener('DOMContentLoaded', function () {
        const slider = document.getElementById('teamSlider');
        const teamACount = document.getElementById('teamACount');
        const teamBCount = document.getElementById('teamBCount');
        const teamBInput = document.getElementById('teamBInput');
        const total = 11;

        function updateCounts() {
            const a = parseInt(slider.value);
            const b = total - a;
            teamACount.textContent = a;
            teamBCount.textContent = b;
            teamBInput.value = b;
        }

        slider.addEventListener('input', updateCounts);
        updateCounts(); // initial run
    });

    // Soft warning on submit, but don't prevent submission
    document.getElementById('playerForm').addEventListener('submit', function(e) {
        const selectedPlayers = document.querySelectorAll('.player-checkbox:checked').length;
        const cap = captainDropdown.value;
        const vc = viceCaptainDropdown.value;
        let warning = document.getElementById('softWarning');

        if (!warning) {
            warning = document.createElement('div');
            warning.id = 'softWarning';
            warning.className = 'text-warning text-center mt-3';
            this.appendChild(warning);
        }

        let message = '';
        if (!cap) message += '⚠️ Captain not selected.<br>';
        if (!vc) message += '⚠️ Vice Captain not selected.<br>';
        if (selectedPlayers === 0) message += '⚠️ No players selected.<br>';

        warning.innerHTML = message;
    });


    document.addEventListener('DOMContentLoaded', function () {
        const matchDropdown = document.getElementById("match_id");
        const playerContainer = document.getElementById("playerCheckboxes");
        const captainDropdown = document.getElementById("captain");
        const viceCaptainDropdown = document.getElementById("vice_captain");
        const capViceSection = document.getElementById("captainViceSection");
        const playerSelectionSection = document.getElementById("playerSelectionSection");

        function renderPlayers(players) {
            playerContainer.innerHTML = '';
            captainDropdown.innerHTML = '<option value="">-- Select Captain --</option>';
            viceCaptainDropdown.innerHTML = '<option value="">-- Select Vice Captain --</option>';

            if (!Array.isArray(players) || players.length === 0) {
                capViceSection.classList.add('hidden');
                playerSelectionSection.classList.add('hidden');
                return;
            }

            capViceSection.classList.remove('hidden');
            playerSelectionSection.classList.remove('hidden');

            players.forEach(p => {
                const playerId = p.player_id ?? p.id;
                const playerName = p.player_name ?? p.name;
                const playerRole = p.player_role;
                const credit = p.credit_points;

                const label = `${playerName} (${playerRole} - ${credit} pts)`;

                playerContainer.innerHTML += `
                    <div class="col-md-4 col-sm-6 mb-2">
                        <div class="form-check">
                            <input type="checkbox" name="players[]" value="${playerId}" class="form-check-input player-checkbox" id="player${playerId}">
                            <label class="form-check-label" for="player${playerId}">${label}</label>
                        </div>
                    </div>`;

                captainDropdown.innerHTML += `<option value="${playerId}">${playerName}</option>`;
                viceCaptainDropdown.innerHTML += `<option value="${playerId}">${playerName}</option>`;
            });

            preventDuplicateCaptainVC();
        }

        function preventDuplicateCaptainVC() {
            const cap = captainDropdown.value;
            const vc = viceCaptainDropdown.value;

            // Re-enable all options
            [...captainDropdown.options].forEach(opt => opt.disabled = false);
            [...viceCaptainDropdown.options].forEach(opt => opt.disabled = false);

            // Prevent selecting same CAP/VC
            if (cap) {
                [...viceCaptainDropdown.options].forEach(opt => {
                    if (opt.value === cap) opt.disabled = true;
                });
            }
            if (vc) {
                [...captainDropdown.options].forEach(opt => {
                    if (opt.value === vc) opt.disabled = true;
                });
            }

            // Re-enable all checkboxes
            document.querySelectorAll('.player-checkbox').forEach(cb => cb.disabled = false);

            // Disable CAP/VC player from checkbox list
            if (cap) {
                const cb = document.getElementById('player' + cap);
                if (cb) cb.disabled = true;
            }
            if (vc) {
                const cb = document.getElementById('player' + vc);
                if (cb) cb.disabled = true;
            }

            updatePlayerLimit(); // revalidate limit
        }

        function updatePlayerLimit() {
            const checked = document.querySelectorAll('.player-checkbox:checked').length;
            const all = document.querySelectorAll('.player-checkbox');
            const max = 9;
            all.forEach(cb => cb.disabled = !cb.checked && checked >= max || cb.disabled); // keep disabled ones untouched
        }

        // Initial load
        if (selectedMatchId && allPlayers.length > 0) {
            renderPlayers(allPlayers);
        }

        matchDropdown.addEventListener('change', function () {
            const matchId = this.value;
            if (!matchId) {
                capViceSection.classList.add('hidden');
                playerSelectionSection.classList.add('hidden');
                captainDropdown.innerHTML = '';
                viceCaptainDropdown.innerHTML = '';
                playerContainer.innerHTML = '';
                return;
            }

            fetch("<?= base_url('DozenDreams/getPlayersForMatch/') ?>" + matchId, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data)) {
                    renderPlayers(data);
                } else {
                    alert("Unexpected data received");
                    console.log(data);
                }
            })
            .catch(err => {
                alert("Unable to load player data. Try again");
                console.error("Fetch error:", err);
            });
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('player-checkbox')) updatePlayerLimit();
            if (e.target.id === 'captain' || e.target.id === 'vice_captain') preventDuplicateCaptainVC();
        });
    });
</script>

</body>
</html>
