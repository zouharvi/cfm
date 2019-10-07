<?php
function menza_prava() {
    global $dayOfWeek;
    // This may get broken soon. I don't know how to choose the lawyer's menza explicitly 
    $pageRaw = getURL("https://kamweb.ruk.cuni.cz/webkredit/ZalozkaObjednavani.aspx");
    $dom = new DomDocument();
    // The web is missing encoding header, so appends it manually.
    @$dom->loadHTML('<?xml encoding="utf-8" ? >' .  $pageRaw);
    $alternatives = array(5, 7, 9, 11, 13, 15, 17, 19, 21); 
    $menu = array();
    foreach($alternatives as $alternative) {
        $menuEl = $dom->getElementById('Jidelnicek1_AlternativaTxt' . $alternative);
        if (isset($menuEl)) {
            array_push($menu, $menuEl->textContent);
        }
    } 
    return implode("\n", $menu);
}
?>
