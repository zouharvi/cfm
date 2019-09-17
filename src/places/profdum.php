<?php
function profdum() {
    /* Zomato requires API key, so here it is. This is actually quite sane were it not for the auth key
     * complications and the 1000 requests per day limits.
     * -zouharvi 23 Aug 2019
     */
    $key = file_get_contents('zomato.key');
    $key = preg_replace('/\n$/', '', $key);

    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Accept: application/json\r\n" .
                        "user_key: " . $key
        ]
    ];
    $context = stream_context_create($opts);

    $menuJSON = getURL("https://developers.zomato.com/api/v2.1/dailymenu?res_id=16506988", false, $context); 
    $menu = json_decode($menuJSON)->daily_menus;
    if (count($menu) < 1) {
        return 'Not available';
    }
    $menu = $menu[0]->daily_menu->dishes;
    $dishes = array();
    foreach($menu as $dish) {
        $food = $dish->dish->name;
        $food = preg_replace('/\d\d+/', '', $food);
        array_push($dishes, $food);  
    }
    return implode("\n", $dishes);
}

function profdum_plain() {
    // Broken for days other than mondays
    // not used anyway, since Zomato is somewhat more reliable
    global $dayOfWeek;
    
    $pageRaw = getURL("http://www.ms.mff.cuni.cz/profdum/jidelnicek.htm");
    $pageRaw = iconv('windows-1250', 'utf-8', $pageRaw);
    $pageRaw = strip_tags($pageRaw);
    $pageRaw = str_replace(array("\n", "\r"), "<br>", $pageRaw);
    $pageRaw = str_replace('&nbsp;', ' ', $pageRaw);
    $pageRaw = preg_replace('/\s+/', ' ', $pageRaw);

    $separators = array("Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "");
    $sepA = $separators[$dayOfWeek];
    $sepB = $separators[$dayOfWeek+1];
    preg_match('/.*' . $sepA . '.*<br><br> <br><br>(.*)<br><br> <br><br>.*' . $sepB . '.*/', $pageRaw, $menuDirty);

    $menuClean = $menuDirty[1];

    // Clean prices and weights
    $menuClean = preg_replace('/\d+g<br>/', "", $menuClean);
    // Collapse newlines
    $menuClean = preg_replace('/<br>(<br>|\s)+/', "<br>", $menuClean);
    // Drop prices
    $menuClean = preg_replace('/(<br>|\d|\.)(,|\.)-/', "\n", $menuClean);
    // Remove leading <br>s
    $menuClean = preg_replace('/<br>/', "", $menuClean);
    // Remove grs
    $menuClean = preg_replace('/gr/', "", $menuClean);
    // Drop digits
    $menuClean = preg_replace('/\d(\d|,)*/', "", $menuClean);
    // Drop leading space
    $menuClean = preg_replace('/^[^a-zA-Z]+/', "", $menuClean);
    $menuClean = preg_replace('/\n[^a-zA-Z]+/', "\n", $menuClean);

    return $menuClean;
}
?>
