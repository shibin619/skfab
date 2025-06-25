<div class="col-md-9 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-accent"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
        <span class="badge bg-warning text-dark px-3 py-2"><?= ucfirst($role); ?></span>
    </div>

    <!-- Info Cards -->
    <div class="row g-4">
<!-- Total Matches -->
<div class="col-md-4">
    <div class="card shadow-sm p-3 border-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-uppercase">Total Matches</h6>
                <h2 class="text-accent"><?= $total_matches ?></h2>
            </div>
            <div>
                <i class="fas fa-cricket text-warning fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<?php if ($role !== 'user'): ?>
    <!-- Total Players -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Total Players</h6>
                    <h2 class="text-accent"><?= $total_players ?></h2>
                </div>
                <div>
                    <i class="fas fa-users text-success fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Venues -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Total Venues</h6>
                    <h2 class="text-accent"><?= $total_venues ?></h2>
                </div>
                <div>
                    <i class="fas fa-map-marker-alt text-secondary fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pair Income -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Total Pair Income</h6>
                    <h2 class="text-accent">₹<?= number_format($pair_income, 2) ?></h2>
                </div>
                <div>
                    <i class="fas fa-link text-purple fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Direct Referral Income -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Direct Referral Income</h6>
                    <h2 class="text-accent">₹<?= number_format($referral_direct, 2) ?></h2>
                </div>
                <div>
                    <i class="fas fa-user-plus text-info fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Indirect Referral Income -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Indirect Referral Income</h6>
                    <h2 class="text-accent">₹<?= number_format($referral_indirect, 2) ?></h2>
                </div>
                <div>
                    <i class="fas fa-user-friends text-primary fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Total Users</h6>
                    <h2 class="text-accent"><?= $total_users ?></h2>
                </div>
                <div>
                    <i class="fas fa-user text-dark fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Investments -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Total Investments</h6>
                    <h2 class="text-accent">₹<?= number_format($total_investments, 2) ?></h2>
                </div>
                <div>
                    <i class="fas fa-coins text-warning fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Gain -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Total Gain</h6>
                    <h2 class="text-accent">₹<?= number_format($total_gain, 2) ?></h2>
                </div>
                <div>
                    <i class="fas fa-arrow-up text-success fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Total Points: Only for user -->
<?php if ($role === 'user'): ?>
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Total Points</h6>
                    <h2 class="text-accent"><?= $total_points ?></h2>
                </div>
                <div>
                    <i class="fas fa-star text-danger fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Wallets: Only for user -->
<?php if ($role === 'user'): ?>
    <?php foreach ($wallets as $wallet): ?>
        <div class="col-md-4">
            <div class="card shadow-sm p-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase"><?= ucfirst($wallet['type']) ?> Wallet</h6>
                        <h2 class="text-accent">₹<?= number_format($wallet['balance'], 2) ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-wallet text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Pending Crons: Only for commando and tester -->
<?php if (in_array($role, ['commando', 'tester'])): ?>
    <div class="col-md-4">
        <div class="card shadow-sm p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Pending Crons</h6>
                    <h2 class="text-accent"><?= $pending_crons ?></h2>
                </div>
                <div>
                    <i class="fas fa-clock text-info fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


    </div>

    <!-- Future Graphs or Stats -->
    <div class="mt-5">
        <h5 class="text-accent mb-3">Performance Overview</h5>
        <canvas id="performanceChart" height="120"></canvas>
    </div>
</div>

<script>
  const ctx = document.getElementById('performanceChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Matches', 'Players', 'Points'],
      datasets: [{
        label: 'Overview',
        data: [<?= $total_matches ?>, <?= $total_players ?>, <?= $total_points ?>],
        backgroundColor: ['#c4c28f', '#4caf50', '#f44336'],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
</script>
