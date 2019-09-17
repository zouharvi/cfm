<?php

function cantina() {
    global $dayOfWeek;
    
    $baseURL = "http://www.restauracecantina.cz";

    // Get the daily menu link from the website menu Cantina changes the link (almost?) every week
    $pageRaw = getURL($baseURL . "/lang/en/");
    $dom = new DomDocument();
    // The web is missing encoding header, so appends it manually.
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' .  $pageRaw);
    $finder = new DomXPath($dom);
    $nodes = $finder->query("//span[contains(normalize-space(),'Daily offer')]/..");
    
    $menuURL = $nodes[0]->getAttribute('href');

    // Get the daily/weekly menu
    $pageRaw = getURL($baseURL . $menuURL);

    $dom = new DomDocument();
    // The web is missing encoding header, so appends it manually.
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' .  $pageRaw);
    $finder = new DomXPath($dom);
    $nodes = $finder->query("//div[@id='content']");
    
    // (the menu contains only UPPERCASE, which looks ugly)
    // Title-Case the diet titles
    $titles = $finder->query("//*[@class='dietTitle']/strong", $nodes[0]);
    foreach ($titles as $t) {
        $t->textContent = trim(mb_convert_case($t->textContent, MB_CASE_TITLE));
    }
    // And lowercase the ingredients 
    $ingredients = $finder->query("//*[@class='dietComposition']", $nodes[0]);
    foreach ($ingredients as $i) {
        $i->textContent = '(' . trim(mb_convert_case($i->textContent, MB_CASE_LOWER)) . ')';
    }
    
    $pageRaw = strip_tags($dom->saveHTML($nodes[0]));
    $pageRaw = str_replace(array("\n", "\r"), "<br>", $pageRaw);
    
    $separators = array("Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "");
    $sepA = $separators[$dayOfWeek];
    $sepB = $separators[$dayOfWeek+1];
    preg_match('/.*' . $sepA . '(.*)' . $sepB . '.*/i', $pageRaw, $menuDirty);
    
    $menuClean = $menuDirty[1];
    
    // Remove the date
    $menuClean = preg_replace('/\d+\.\d+\./', "", $menuClean);
    // Remove order ("1.", "2.", ...)
    $menuClean = preg_replace('/\d\. /', "", $menuClean);
    // Clean prices
    $menuClean = preg_replace('/\d+,- Kč<br>/', "", $menuClean);
    // Remove weight
    $menuClean = preg_replace('/\d+[gG]/', "", $menuClean);
    // Collapse newlines
    $menuClean = preg_replace('/<br>(<br>|\s)+/', "<br>", $menuClean);
    // Drop leading whitespace
    $menuClean = preg_replace('/^ *<br>/', "", $menuClean);

    return $menuClean;
}

?>
