<html>
<head>
    <title>Call for Menza</title>
    <!-- empty favicon -->
    <link href="data:image/x-icon;base64,AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAgAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAA////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD//wAA+/8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400" media="all">
    <style>
        * { font-family: Roboto; }
        a { color: black; text-decoration: none; }
    </style>
</head>

<body style='background-color: #f4f4f4'>
    <h1>Call for Menza</h2>
    <?php
    include 'raw.php';
    
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

    <h5><a href='https://github.com/zouharvi/cfm'>About</a></h5>
</body>
</html>
