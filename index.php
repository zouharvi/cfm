<html>
<head>
    <title>Call for Menza</title>
    <link rel="shortcut icon" href="https://ufal.mff.cuni.cz/sites/all/themes/drufal/ico/favicon.ico" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400" media="all">
    <style>
        * { font-family: Roboto; }
        a { color: black; text-decoration: none; }
    </style>
</head>

<body style='background-color: #f4f4f4'>
    <h1>Call for Menza</h1>
    <?php
    include 'src/raw.php';
    
    if ($isWeekend) {
        echo "<p>It's weekend now, so I'm showing last available lunch menus.</p>";
    }

    foreach($response as $place) {
        echo '<h3><a href="' . $place['href'] . '">' . $place['name'] . '</a></h3>';
        $menu = $place['menu'];
        $menu = str_replace("\n", '<br>', $menu);
        echo '<p>' . $menu . '</p>';
    }
    ?>

    <br>
    <div>
        Made by Vilda with the help of others from MS.
    </div>
    
    <div style='font-weight: bold;'>
        <a href="https://github.com/zouharvi/cfm">About/GitHub</a>, <a href="statistics.php">Statistics</a>
    </div>
</body>
</html>
