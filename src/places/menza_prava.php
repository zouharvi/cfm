<?php

function menza_prava() {
    global $dayOfWeek;
    $pageRaw = file_get_contents("https://kamweb.ruk.cuni.cz/WebKredit/rss?canteenId=7&language=Cz");
    
    $state = 0;
    $out = '';
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $pageRaw) as $line){
        // do stuff with $line
        if($state == 0 & (strpos($line, "Menu") !== false || strpos($line, "Hlavní") !== false)) {
            $state = 1;
            continue;
        }
        if($state == 1) {
            if(strpos($line, "Bageta") !== false || strpos($line, "updated") !== false) {
                $state = 2;
                break;
            } else {
                $out = $out . $line;
            }
        }
    } 
    $out = str_replace(array("&", "gt", "lt", "h2", ";", "/", "ul"), '', $out);
    $out = preg_replace("/li\s*li/", "<br>", $out);
    $out = preg_replace("/\s+li/", '', $out);
    return $out;
}

function menza_prava_deprecated() {
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
