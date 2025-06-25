<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - DozenDreams</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= ASSETS_PATH ?>img/dozen/login.png" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Theme CSS -->
  <link href="<?= base_url('assets/css/login.css'); ?>" rel="stylesheet">
</head>

<body class="bg-primary d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg p-4 border-accent rounded-4" style="min-width: 320px; max-width: 400px; width: 100%;">
    <div class="text-center mb-3">
      <img src="<?= ASSETS_PATH ?>img/dozen/register.png" alt="Register Icon" style="height: 330px;" class="mb-2">
      <h4 class="mt-2 text-accent">Create Account</h4>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('DozenDreams/register'); ?>">
      <div class="mb-3">
        <label class="form-label text-accent">Full Name</label>
        <input type="text" name="name" class="form-control border-accent" required>
      </div>

      <div class="mb-3">
        <label class="form-label text-accent">Email</label>
        <input type="email" name="email" class="form-control border-accent" required>
      </div>

      <div class="mb-3">
        <label class="form-label text-accent">Password</label>
        <input type="password" name="password" class="form-control border-accent" required>
      </div>
      <div class="mb-3">
        <label class="form-label text-accent">Referral Code (Optional)</label>
        <input type="text" name="referral_code" class="form-control border-accent" placeholder="Enter referrer's email or user ID">
      </div>


      <div class="mb-3">
        <button type="submit" class="btn btn-outline-light w-100">Register</button>
      </div>
    </form>

    <p class="mt-3 text-center text-accent">
      Already have an account?
      <a href="<?= base_url('DozenDreams/login'); ?>" class="text-accent fw-bold">Login</a>
    </p>
  </div>

  <!-- FontAwesome (still loaded in case needed elsewhere) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</body>
</html>
