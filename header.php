<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DozenDreams Dashboard</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= ASSETS_PATH ?>img/dozen/login.png" />

  <!-- Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Custom Theme -->
  <style>
  body {
    background-color: #0f1a36;
    color: #e0d799;
    font-family: 'Segoe UI', sans-serif;
  }
  .text-accent { color: #e0d799 !important; }
  .border-accent { border-color: #e0d799 !important; }

  .btn-outline-light {
    border-color: #e0d799 !important;
    color: #e0d799 !important;
  }
  .btn-outline-light:hover {
    background-color: #e0d799 !important;
    color: #1c2e4a !important;
  }

  .card {
    background-color: #1c2e4a;
    color: #e0d799;
  }

  input.form-control:focus {
    border-color: #e0d799;
    box-shadow: 0 0 0 0.2rem rgba(224, 215, 153, 0.25);
  }

  .sidebar {
    background-color: #1c2e4a;
    min-height: 100vh;
    color: #e0d799;
  }

  .sidebar a {
    color: #e0d799;
    text-decoration: none;
  }
  .sidebar a:hover {
    color: #fff5cc;
    text-decoration: underline;
  }

  .profile-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border: 2px solid #e0d799;
  }

  .alert {
    margin-bottom: 0;
    border-radius: 0;
    font-weight: 500;
  }

  @media (max-width: 768px) {
    .sidebar {
      padding: 1rem 0.5rem;
    }
  }
</style>

</head>

<body>

<!-- Alerts -->
<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show text-dark" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<!-- Main Container -->
<div class="container-fluid">
  <div class="row">
