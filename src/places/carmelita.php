<?php

function carmelita() {
    global $dayOfWeek;
    
    $pageRaw = getURL("http://www.restauracecarmelita.cz/poledni-nabidka-1/");
    $dom = new DomDocument();
    // The web is missing encoding header, so appends it manually.
    @$dom->loadHTML('<?xml encoding="utf-8" ? >' .  $pageRaw);
    $finder = new DomXPath($dom);
    $nodes = $finder->query("//div[@id='content']");

    $pageRaw = strip_tags($dom->saveHTML($nodes[0]));
    $pageRaw = str_replace(array("\n", "\r"), "<br>", $pageRaw);

    $separators = array("Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "");
    $sepA = $separators[$dayOfWeek];
    $sepB = $separators[$dayOfWeek+1];
    preg_match('/.*' . $sepA . '(.*)' . $sepB . '.*/', $pageRaw, $menuDirty);
    
    $menuClean = $menuDirty[1];
    
    $menuClean = preg_replace('/\d+\.\d+\./', "", $menuClean);

    // Remove order ("1.", "2.", ...)
    $menuClean = preg_replace('/\d\. ?/', "", $menuClean);
    // Clean prices
    $menuClean = preg_replace('/\d+,- Kč<br>/', "", $menuClean);
    $menuClean = preg_replace('/\d+g/', "", $menuClean);
    // Collapse newlines
    $menuClean = preg_replace('/<br>(<br>|\s)+/', "<br>", $menuClean);
    // Drop leading whitespace
    $menuClean = preg_replace('/^ *<br>/', "", $menuClean);
    // Remove footer
    $menuClean = preg_replace('/K polednimu menu nabízíme.*/iu', "", $menuClean);
    return $menuClean;
}

?>
