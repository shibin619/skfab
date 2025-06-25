<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Dream11 Style</title>
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
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.1);
        }
        .card h4 {
            color: #ffd700;
        }
        .form-label {
            color: #ffd700;
            font-weight: 500;
        }
        .form-control {
            background-color: #1a2f56;
            color: #fff;
            border: 1px solid #ffd700;
        }
        .form-control:focus {
            box-shadow: 0 0 10px #ffd700;
            border-color: #ffda44;
        }
        .btn-success {
            background-color: #ffd700;
            color: #0b1d3a;
            border: none;
            font-weight: bold;
        }
        .btn-success:hover {
            background-color: #e6c200;
            color: #000;
        }
        .btn-secondary {
            background-color: #1a2f56;
            border: 1px solid #ffd700;
            color: #ffd700;
        }
        .btn-secondary:hover {
            background-color: #ffd700;
            color: #0b1d3a;
        }
    </style>
</head>
<body class="container py-5">

    <div class="card p-4 mx-auto" style="max-width: 600px;">
        <h4 class="text-center mb-4">🎯 Edit Profile</h4>

        <form method="post" class="needs-validation" novalidate>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" value="<?= $profile['first_name'] ?>" class="form-control" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" value="<?= $profile['last_name'] ?>" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile_number" value="<?= $profile['mobile_number'] ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Aadhaar Number</label>
                <input type="text" name="aadhaar_number" value="<?= $profile['aadhaar_number'] ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">PAN Number</label>
                <input type="text" name="pan_number" value="<?= $profile['pan_number'] ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control"><?= $profile['address'] ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">Update</button>
                <a href="<?= base_url('profile') ?>" class="btn btn-secondary px-4 ms-2">Back</a>
            </div>
        </form>
    </div>

</body>
</html>
