<?php
function ananta() {
    // ananta could follow suit of natureza (strip tags, lots of greps)
    global $dayOfWeek;

    $separators = array("PONDĚLÍ", "ÚTERÝ", "STŘEDA", "ČTVRTEK", "PÁTEK", "Menu");
    $sepA = $separators[$dayOfWeek];
    $sepB = $separators[$dayOfWeek+1];

    $page = new DOMDocument();
    $pageRaw = getURL('http://www.anantasesa.cz/tydenni-menu');
    // Sanitize HTML
    $pageRaw = str_replace(array("\r", "\n"), '', $pageRaw);
    @$page->loadHTML($pageRaw);
    $content = $page->getElementById('content')->C14N();

    preg_match('/.*' . $sepA . '<\/h2>(.*)' . $sepB . '.*/', $content, $menuDirty);
    $menuClean = $menuDirty[1];
    $menuClean = preg_replace('/<\/p><p>/', "<br>", $menuClean);
    $menuClean = preg_replace('/(<p>|<\/p>)/', "", $menuClean);
    $menuClean = preg_replace('/<\/?h\d>/', "", $menuClean);
    $menuClean = preg_replace('/<br><\/br>/', "<br>", $menuClean);
    $menuClean = preg_replace('/Jogurt<br>/', "Jogurt", $menuClean);

    return $menuClean;
}
?>
