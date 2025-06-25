<!-- Sidebar -->
<div class="col-md-3 p-3">
  <div class="sidebar shadow-sm rounded p-3" style="background-color: #1c2e4a; color: #fdd835;">
    
    <!-- Profile -->
    <div class="d-flex align-items-center mb-4">
      <img src="<?= ASSETS_PATH ?>img/dozen/profile.png" alt="Profile" class="rounded-circle me-3 profile-img">
      <div>
        <strong class="text-accent"><?= $this->session->userdata('user_name'); ?></strong><br>
        <small class="text-light">Role: <b><?= ucfirst($this->session->userdata('user_role')); ?></b></small>
      </div>
    </div>

    <!-- Wallet -->
    <div class="bg-dark rounded p-2 mb-3 d-flex justify-content-between align-items-center" style="color: #fdd835;">
      <span><i class="fas fa-wallet me-2 text-success"></i>My Balance</span>
      <span>₹<?= number_format($this->dozen->getWalletBalance($this->session->userdata('user_id')), 2) ?></span>
    </div>
    <a href="<?= base_url('DozenDreams/addCash'); ?>" class="btn btn-sm btn-warning w-100 mb-4 fw-bold">
      <i class="fas fa-plus-circle me-1"></i> Add Cash
    </a>

    <!-- Navigation -->
    <ul class="list-group list-group-flush mb-3">
      <li class="list-group-item bg-transparent border-0">
        <a href="<?= base_url('DozenDreams/dashboard'); ?>" class="text-accent">
          <i class="fas fa-tachometer-alt me-2 text-warning"></i> Dashboard
        </a>
      </li>

      <?php
        $role = $this->session->userdata('user_role');
        $links = [];

        if ($this->session->userdata('is_superadmin')) {
          $links = [
            ['Manage Users','usersList'], ['Masters','mastersList'], ['Venue','venueList'],
            ['Teams','teamList'], ['Tournaments','tournamentList'], ['Schedule','scheduleList'],
            ['Points','pointsList'], ['Player Statistics','playerStatisticsList'],
            ['Create Contest','createContest'],['Contests','showContests'],
            ['Generate Teams','index'],['Referral Income','viewAllReferrals'],
            ['Referral Settings','referralSettings'],['Wallet Transactions','walletTransactions'],
            ['Referral Bonus','referralBonus'],['Referral Bonus Slabs','referralBonusSlabs'],
            ['Pair Bonus','pairBonus'],['Pair Bonus Slabs','pairBonusSlabs'],
            ['Pitch Types','pitchTypes'],['Prize Breakup','prizeBreakup'],
            ['Dream Tree','dreamTree'],['Match Type','matchType'],
            ['Roles','roles'],['User Joined Contests','userJoinedContets'],
            ['User Points','userPoints'],['User Generated Teams','userGeneratedTeams'],
            ['Pitch Types','pitchTypes'],['Prize Breakup','prizeBreakup'],
            ['Wallet Types','walletTypes']

          ];
        } elseif ($this->session->userdata('is_admin')) {
          $links = [
            ['Teams','teamList'], ['Tournaments','tournamentList'],
            ['Contests','showContests'], ['Create Team','createTeam'],
            ['View Team','viewTeam'], ['Player Stats','playerStatisticsList'],
            ['Generate Teams','index'], ['Referral Income','viewAllReferrals'],
            ['Wallet Transactions','walletTransactions']
          ];
        } elseif ($this->session->userdata('is_user')) {
          $links = [
            ['Upcoming Matches','allMatches'], ['Points','pointsList'],
            ['User Profile','userProfile'], 
            ['Player Stats','playerStatisticsList'], ['Schedule','scheduleList'],
            ['Referral Income','myReferrals'], ['Wallet','wallet']
          ];
        } elseif ($this->session->userdata('is_commando') || $this->session->userdata('is_tester')) {
          $links = [
            ['Contests','showContests'], ['Create Contest','createContest'],
            ['Generate Matches','generateUpcomingMatches'], ['Run Crons','cronList']
          ];
        }

        foreach ($links as $lnk): ?>
          <li class="list-group-item bg-transparent border-0">
            <a href="<?= base_url("DozenDreams/{$lnk[1]}"); ?>" class="text-accent">
              <i class="fas fa-chevron-circle-right me-2 text-info"></i> <?= $lnk[0] ?>
            </a>
          </li>
      <?php endforeach; ?>

      <!-- Common Links -->
      <li class="list-group-item bg-transparent border-0">
        <a href="#"><i class="fas fa-comments me-2 text-primary"></i> Chat <span class="badge bg-danger rounded-pill">99+</span></a>
      </li>
      <li class="list-group-item bg-transparent border-0">
        <a href="<?= base_url('DozenDreams/referUser'); ?>"><i class="fas fa-user-plus me-2 text-success"></i> Refer & Win</a>
      </li>
      <li class="list-group-item bg-transparent border-0">
        <a href="#"><i class="fas fa-trophy me-2 text-warning"></i> Winners</a>
      </li>
      <li class="list-group-item bg-transparent border-0">
        <a href="#"><i class="fas fa-cog me-2 text-secondary"></i> My Info & Settings</a>
      </li>
      <li class="list-group-item bg-transparent border-0">
        <a href="#"><i class="fas fa-gamepad me-2 text-danger"></i> How to Play</a>
      </li>
      <li class="list-group-item bg-transparent border-0">
        <a href="#"><i class="fas fa-handshake me-2 text-info"></i> Responsible Play</a>
      </li>
      <li class="list-group-item bg-transparent border-0">
        <a href="<?= base_url('DozenDreams/logout'); ?>"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout</a>
      </li>
    </ul>

    <!-- Footer -->
    <div class="mt-4 text-center">
      <small><i class="fas fa-headset me-1 text-light"></i> 24x7 Help & Support</small>
    </div>
    <hr class="border-accent">
  </div>
</div>
