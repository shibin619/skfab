<div class="col-md-9 p-3">
    <div class="shadow-sm rounded p-4" style="background-color: #093f64; color: #ffffff;">
        <h4 class="mb-4" style="color: #e0d799;">🏏 All Matches</h4>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="matchTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">Upcoming</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">Completed</button>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content" id="matchTabsContent">
            <!-- Upcoming Matches -->
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                <?php if (!empty($upcoming_matches)): ?>
                    <?php foreach ($upcoming_matches as $match): ?>
                        <div class="match-card mb-4 p-3 rounded" style="background-color: #1c2833; box-shadow: 0 4px 6px rgba(0,0,0,0.4);">
                            <div class="row match-header align-items-center pb-2 border-bottom border-secondary">
                                <div class="col-md-8">
                                    <div class="team-name h5 mb-1" style="color: #e0d799;">
                                        <?= htmlspecialchars($match['team1_name']) ?> 🆚 <?= htmlspecialchars($match['team2_name']) ?>
                                    </div>
                                    <div class="text-muted small"><?= htmlspecialchars($match['venue']) ?></div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="match-time" style="color: #f39c12; font-weight: bold;">
                                        <?= date('d M, h:i A', strtotime($match['match_date'])) ?>
                                    </div>
                                    <?php if ($match['lineup_status_code'] == '1'): ?>
                                        <span class="badge bg-success">Lineup Out</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Lineup Not Out</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-3 text-end">
                                <a href="<?= site_url('DozenDreams/getContestsByMatch/' . $match['match_id']) ?>" class="btn btn-warning btn-sm">View Contests</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">No upcoming matches.</div>
                <?php endif; ?>
            </div>

            <!-- Completed Matches -->
            <div class="tab-pane fade" id="completed" role="tabpanel">
                <?php if (!empty($completed_matches)): ?>
                    <?php foreach ($completed_matches as $match): ?>
                        <div class="match-card mb-4 p-3 rounded" style="background-color: #1c2833; box-shadow: 0 4px 6px rgba(0,0,0,0.4);">
                            <div class="row match-header align-items-center pb-2 border-bottom border-secondary">
                                <div class="col-md-8">
                                    <div class="team-name h5 mb-1" style="color: #e0d799;">
                                        <?= htmlspecialchars($match['team1_name']) ?> 🆚 <?= htmlspecialchars($match['team2_name']) ?>
                                    </div>
                                    <div class="text-muted small"><?= htmlspecialchars($match['venue']) ?></div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="match-time text-secondary fw-bold">
                                        <?= date('d M, h:i A', strtotime($match['match_date'])) ?>
                                    </div>
                                    <span class="badge bg-secondary">Match Over</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">No completed matches.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
