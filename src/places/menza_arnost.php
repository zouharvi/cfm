<?php
function menza_arnost() {
    global $dayOfWeek;
    $pageRaw = file_get_contents("https://kamweb.ruk.cuni.cz/WebKredit/rss?canteenId=5&language=Cz");
    
    $state = 0;
    $out = '';
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $pageRaw) as $line){
        // do stuff with $line
        if($state == 0 & strpos($line, "Menu") !== false) {
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
?>
