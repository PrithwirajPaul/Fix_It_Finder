<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Hell owrld</p>
    <?php
    $list = array('ken','ryu');
    echo count($list);
    for ($i = 0; $i < 5; $i++) {
        echo '<h2 class ="x">count($list)</h2>';
    }
    ?>
</body>
</html>