<!DOCTYPE html>
<html>
<head>
    <title>Dream Tree View</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background: #f9f9f9;
    }

    ul {
        padding-left: 20px;
        list-style-type: none;
        position: relative;
    }

    ul ul::before {
        content: '';
        border-left: 1px solid #ccc;
        position: absolute;
        top: 0;
        left: 10px;
        bottom: 0;
    }

    li {
        margin: 10px 0;
        padding-left: 20px;
        position: relative;
    }

    li::before {
        content: '';
        border-top: 1px solid #ccc;
        position: absolute;
        top: 15px;
        left: 0;
        width: 10px;
    }

    li strong {
        color: #3f51b5;
    }

    /* Tooltip styles */
    .tooltip {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 220px;
        background-color: #333;
        color: #fff;
        text-align: left;
        border-radius: 5px;
        padding: 10px;
        position: absolute;
        z-index: 1;
        top: -5px;
        left: 110%;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 13px;
        line-height: 1.4;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>

</head>
<body>
    <h2>🌳 Dream Tree Structure</h2>
    <ul>
        <?= $tree ?>
    </ul>
</body>
</html>
