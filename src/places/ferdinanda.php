<?php
function ferdinanda() {
    $pageRaw = getURL("http://www.ferdinanda.cz/cs/mala-strana/menu/denni-menu/main.html?ajax=1");
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
?>
