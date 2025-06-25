<div class="container mt-4">
  <h4>Referral Earnings</h4>
  <?php if (!empty($referrals)): ?>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Referred User</th>
          <th>Level</th>
          <th>Amount</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($referrals as $ref): ?>
          <tr>
            <td><?= $ref->referred_name ?></td>
            <td>Level <?= $ref->level ?></td>
            <td>₹<?= number_format($ref->amount, 2) ?></td>
            <td><?= date('d-m-Y', strtotime($ref->created_at)) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No referral bonuses earned yet.</p>
  <?php endif; ?>
</div>
