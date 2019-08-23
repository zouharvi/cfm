<?php

$dayOfWeek = date('w');

if ($dayOfWeek == 6 || $dayOfWeek == 7) {
    die('[]');
}

$dayOffset = $dayOfWeek-1;
$dayOffset = 4;

function anantasea() {
    // anantasea could follow suit of natureza (strip tags, lots of greps)
    global $dayOffset;

    $childIndex = array(1, 4, 7, 10, 13)[$dayOffset];
    $page = new DOMDocument();
    $pageRaw = file_get_contents('http://www.anantasesa.cz/tydenni-menu');
    // Sanitize HTML
    $pageRaw = str_replace(array("\r", "\n"), '', $pageRaw);
    @$page->loadHTML($pageRaw);
    $content = $page->getElementById('content');

    $menu = $content->childNodes->item($childIndex)->C14N();
    $menu = str_replace(array("<br></br>", "<p>", "</p>"), array("\n", "", ""), $menu);
    return $menu;
}

function natureza() {
    global $dayOffset;

    $childIndex = array(1, 4, 7, 10, 13)[$dayOffset];
    $page = new DOMDocument();
    $pageRaw = file_get_contents('https://naturezaveget.cz/cs/o-nas');
    $pageRaw = strip_tags($pageRaw);
    $pageRaw = str_replace(array("\n", "\r", "\t"), '<br>', $pageRaw);
    $pageRaw = preg_replace('/(<br>)+/', "<br>", $pageRaw);

    // It is almost guaranteed that the <br>D<br> will break. For debugging
    // echo $pageRaw and see what the current situation looks like.
    // -zouharvi 23 Aug 2019

    $separators = array("Pondělí, \d+ [^\s]+", "Úterý, \d+ [^\s]+", "Středa, \d+ [^\s]+", "Čtvrtek, \d+ [^\s]+", "Pátek, \d+ [^\s]+", "<br>D<br>");
    $sepA = $separators[$dayOffset];
    $sepB = $separators[$dayOffset+1];
    preg_match('/.*' . $sepA . ' <br>(.*)<br> ' . $sepB . '.*/', $pageRaw, $menuDirty);
    $menuClean = preg_replace('/<br>/', "\n", $menuDirty[1]);
    return $menuClean;
}

function profdum() {
    return '';
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

    $menuJSON = file_get_contents("https://developers.zomato.com/api/v2.1/dailymenu?res_id=16506988", false, $context); 
    $menu = json_decode($menuJSON)->daily_menus;
    if (count($menu) < 1) {
        return 'Not available';
    }
    $menu = $menu[0]->daily_menu->dishes;
    $dishes = array();
    foreach($menu as $dish) {
        array_push($dishes, $dish->dish->name);  
    }
    print_r($dishes);
    return $dishes;
}

function ferdinanda() {
    $pageRaw = file_get_contents("http://www.ferdinanda.cz/cs/mala-strana/menu/denni-menu/main.html?ajax=1");
    $pageRaw = strip_tags($pageRaw);
    preg_match('/HLAVNÍ JÍDLA(.*)SALÁTY/', $pageRaw, $mainClean);
    $mainClean = $mainClean[1];
    // Entries end with the price
    $mainClean = preg_replace('/\d+&nbsp;Kč/', "\n", $mainClean);
    // Clean up weights
    $mainClean = preg_replace('/\d+g/', "", $mainClean);
    // Clean up leading spaces
    $mainClean = preg_replace('/^\s+/', "", $mainClean);
    // Clean up trailing spaces and newlines
    $mainClean = preg_replace('/[\s\n]+$/', "", $mainClean);
    return $mainClean;
}

$places = array('anantasea', 'natureza', 'profdum', 'ferdinanda');
$response = array();
foreach($places as $place) {
    $menu = $place();
    array_push($response, $menu);
} 

print_r($response);
//echo(json_encode($response));
?>
