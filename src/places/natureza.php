<?php
// deprecated
function natureza_plain() {
    global $dayOfWeek;

    $childIndex = array(1, 4, 7, 10, 13)[$dayOfWeek];
    $page = new DOMDocument();
    $pageRaw = getURL('https://naturezaveget.cz/cs/o-nas');
    $pageRaw = strip_tags($pageRaw);
    $pageRaw = str_replace(array("\n", "\r", "\t"), '<br>', $pageRaw);
    $pageRaw = preg_replace('/(<br>)+/', "<br>", $pageRaw);

    // It is almost guaranteed that the <br>D<br> will break. For debugging
    // echo $pageRaw and see what the current situation looks like.
    // -zouharvi 23 Aug 2019

    $separators = array("Pondělí, \d+ [^\s]+", "Úterý, \d+ [^\s]+", "Středa, \d+ [^\s]+", "Čtvrtek, \d+ [^\s]+", "Pátek, \d+ [^\s]+", "D<br>");
    $sepA = $separators[$dayOfWeek];
    $sepB = $separators[$dayOfWeek+1];
    preg_match('/.*' . $sepA . '(\s|<br>)+(.*)<br>\s*' . $sepB . '.*/', $pageRaw, $menuDirty);
    $menuClean = preg_replace("/(<br>|\s|&nbsp;)+<br>/", "<br>", $menuDirty[2]);
    $menuClean = preg_replace("/<br>/", "\n", $menuClean);
    $menuClean = preg_replace("/[\n]+/", "\n", $menuClean);
    
    // Tmp hack to drop empty lines full of random white characters, \s does not match them
    $tmp = explode("\n", $menuClean);
    $result = '';
    foreach($tmp as $i) {
        if (strlen($i) < 150) {
            $result = $result . "\n" . $i;
        }
    }
    return $result;
}

function natureza() {
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

    $menuJSON = getURL("https://developers.zomato.com/api/v2.1/dailymenu?res_id=16507635", false, $context); 
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
?>
