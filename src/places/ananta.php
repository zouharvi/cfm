<?php
function ananta() {
    // ananta could follow suit of natureza (strip tags, lots of greps)
    global $dayOfWeek;

    $childIndex = array(1, 4, 7, 10, 13)[$dayOfWeek];
    $page = new DOMDocument();
    $pageRaw = getURL('http://www.anantasesa.cz/tydenni-menu');
    // Sanitize HTML
    $pageRaw = str_replace(array("\r", "\n"), '', $pageRaw);
    @$page->loadHTML($pageRaw);
    $content = $page->getElementById('content');

    $menu = $content->childNodes->item($childIndex)->C14N();
    $menu = str_replace(array("<br></br>", "<p>", "</p>"), array("\n", "", ""), $menu);
    return $menu;
}
?>
