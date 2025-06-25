
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 p-3">
            <!-- User Sidebar -->
            <div class="bg-white shadow-sm rounded p-3" style="width: 100%; max-width: 280px;">
                <!-- Profile Info -->
                <div class="d-flex align-items-center mb-3">
                    <img src="https://i.imgur.com/Xpt0IXH.png" alt="Profile" class="rounded-circle me-2" width="50" height="50">
                    <div>
                        <strong>Royal Spartanzz</strong><br>
                        <small class="text-muted">Skill Score: <b>767</b></small>
                    </div>
                </div>

                <!-- Wallet Info -->
                <div class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-wallet text-success me-2"></i><strong>My Balance</strong></span>
                    <span>₹0.62</span>
                </div>
                <div class="text-center mb-3">
                    <button class="btn btn-sm btn-success w-100">Add Cash</button>
                </div>

                <!-- Menu -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-comments me-2 text-primary"></i> Chat</span>
                        <span class="badge bg-danger rounded-pill">99+</span>
                    </li>
                    <li class="list-group-item"><i class="fas fa-user-plus me-2 text-success"></i> Refer & Win</li>
                    <li class="list-group-item"><i class="fas fa-trophy me-2 text-warning"></i> Winners</li>
                    <li class="list-group-item"><i class="fas fa-cog me-2 text-secondary"></i> My Info & Settings</li>
                    <li class="list-group-item"><i class="fas fa-gamepad me-2 text-danger"></i> How to Play</li>
                    <li class="list-group-item"><i class="fas fa-handshake me-2 text-info"></i> Responsible Play</li>
                    <li class="list-group-item"><i class="fas fa-ellipsis-h me-2 text-dark"></i> More</li>
                </ul>

                <!-- Support -->
                <div class="mt-4 text-center">
                    <small class="text-muted"><i class="fas fa-headset me-1"></i> 24x7 Help & Support</small>
                </div>

                <hr>

                <!-- More From Dream Sports -->
                <!-- <div>
                    <strong>More From Dream Sports</strong>
                    <div class="row text-center mt-2">
                        <div class="col-6 mb-3">
                            <img src="https://img.icons8.com/color/48/shop.png" width="24"><br><small>DreamShop</small>
                        </div>
                        <div class="col-6 mb-3">
                            <img src="https://img.icons8.com/color/48/football2.png" width="24"><br><small>FanCode</small>
                        </div>
                        <div class="col-6 mb-2">
                            <img src="https://img.icons8.com/color/48/quiz.png" width="24"><br><small>CriQ</small>
                        </div>
                        <div class="col-6 mb-2">
                            <img src="https://img.icons8.com/color/48/controller.png" width="24"><br><small>Supa</small>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 p-3">
            <h2>Welcome to the Dashboard</h2>
            <p>This is where your user-related stats and content will appear.</p>
        </div>
    </div>
</div>

</body>
</html>
