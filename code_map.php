<!DOCTYPE html>
<html>
<head>
    <title>Code Overview Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .card-header {
            background-color: #0d6efd;
            color: #fff;
            font-weight: 500;
        }
        .item-list {
            padding-left: 1rem;
            margin: 0;
        }
        .item-list li {
            list-style-type: "👉 ";
            margin-bottom: 5px;
        }
        .key-label {
            font-weight: bold;
        }
    </style>
</head>
<body class="p-4">

<div class="container">

    <h2 class="section-title">📦 Controller Map</h2>
    <?php foreach ($controllers as $ctrl): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $ctrl['file'] ?></div>
            <div class="card-body">
                <p class="key-label">Functions:</p>
                <ul class="item-list">
                    <?php foreach ($ctrl['functions'] as $func): ?>
                        <li><?= $func ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="key-label">Views Used:</p>
                <ul class="item-list">
                    <?php foreach ($ctrl['views'] as $view): ?>
                        <li><?= $view ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>

    <h2 class="section-title">📂 Model Map</h2>
    <?php foreach ($models as $mdl): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $mdl['file'] ?></div>
            <div class="card-body">
                <p class="key-label">Functions:</p>
                <ul class="item-list">
                    <?php foreach ($mdl['functions'] as $func): ?>
                        <li><?= $func ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="key-label">Tables Used:</p>
                <ul class="item-list">
                    <?php foreach ($mdl['tables'] as $tbl): ?>
                        <li><?= $tbl ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>


