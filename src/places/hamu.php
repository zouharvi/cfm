<?php
function hamu() {
    global $dayOfWeek;
    
    $pageRaw = getURL("https://www.hamu.cz/cs/vse-o-fakulte/fakultni-kavarna/");
    $dom = new DomDocument();
    // The web is missing encoding header, so appends it manually.
    @$dom->loadHTML('<?xml encoding="utf-8" ? >' .  $pageRaw);
    $finder = new DomXPath($dom);
    $classname="wysiwyg";
    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    
    $pageRaw = strip_tags($dom->saveHTML($nodes[0]));
    $pageRaw =str_replace(array("\n", "\r"), "<br>", $pageRaw);

    $separators = array("Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "");
    $sepA = $separators[$dayOfWeek];
    $sepB = $separators[$dayOfWeek+1];
    preg_match('/.*' . $sepA . '(.*)' . $sepB . '.*/', $pageRaw, $menuDirty);
    
    $menuClean = $menuDirty[1];
    // Clean prices and weights
    $menuClean = preg_replace('/\d+g<br>/', "", $menuClean);
    // Collapse newlines
    $menuClean = preg_replace('/<br>(<br>|\s)+/', "<br>", $menuClean);
    // Some special two-char symbols
    $menuClean = preg_replace('/<br>..<br>/', "", $menuClean);
    // Drop prices
    $menuClean = preg_replace('/(<br>|\d|\.)(,|\.)-/', "\n", $menuClean);
    // Remove leading <br>s
    $menuClean = preg_replace('/<br>/', "", $menuClean);
    // Drop digits
    $menuClean = preg_replace('/\d(\d|,)*/', "", $menuClean);
    // Drop leading space
    $menuClean = preg_replace('/^[^a-zA-Z]+/', "", $menuClean);
    // Drop lines without letters
    $menuClean = preg_replace('/^[^a-zA-Z]*$/', "", $menuClean);

    return $menuClean;
}
?>
