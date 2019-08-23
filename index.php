<html>
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400" media="all">
    <style>
        * { font-family: Roboto; }
    </style>
</head>

<body style='background-color: #f4f4f4'>
    <h1>Call for Menza</h2>
    <?php
    include 'raw.php';

    foreach($response as $place) {
        echo '<h3>' . $place['name'] . '</h3>';
        $menu = $place['menu'];
        $menu = str_replace("\n", '<br>', $menu);
        echo '<p>' . $menu . '</p>';
    }
    ?>

    <a href='https://github.com/zouharvi/cfm'>About</a>
</body>
</html>
