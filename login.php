<head>
  <meta charset="UTF-8">
  <title>Login - DozenDreams</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= ASSETS_PATH ?>img/dozen/login.png" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome (optional) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Custom Theme CSS -->
  <link href="<?= base_url('assets/css/login.css'); ?>" rel="stylesheet">
</head>


<body class="bg-primary d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg p-4 border-accent rounded-4" style="min-width: 350px;">
    <div class="text-center mb-4">
      <img src="<?= ASSETS_PATH ?>img/dozen/login.png" alt="Login Icon" style="height: 270px;" class="mb-2">
      <h4 class="mt-2 text-accent">Login to DozenDreams</h4>
    </div>

    <form method="post" action="<?= base_url('DozenDreams/login'); ?>">
      <div class="mb-3">
        <label class="form-label text-accent">Email</label>
        <input type="email" name="email" class="form-control border-accent" required placeholder="Enter email">
      </div>
      <div class="mb-3">
        <label class="form-label text-accent">Password</label>
        <input type="password" name="password" class="form-control border-accent" required placeholder="Enter password">
      </div>
      <button class="btn btn-outline-light w-100" type="submit">Login</button>
    </form>

    <p class="mt-3 text-center text-accent">
      Don't have an account?
      <a href="<?= base_url('DozenDreams/register'); ?>" class="text-accent fw-bold">Register</a>
    </p>
  </div>

</body>
</html>
