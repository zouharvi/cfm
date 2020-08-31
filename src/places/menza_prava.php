<?php
function menza_prava() {
    global $dayOfWeek;
    
    $pageRaw = file_get_contents(
        "https://kamweb.ruk.cuni.cz/webkredit/Api/Ordering/Menu?Dates=" . date("Y") . "-" . date("m") . "-" . date("d") . "&CanteenId=7"
    );
    $obj = json_decode($pageRaw);
    $out = "";
    
    $outArr = array();

    foreach($obj->groups[1]->rows as $group) {
        $mealName = $group->item->mealName;
        $price = $group->item->price;
        if(strpos(strtolower($mealName), "vegan") !== false) {
            $mealName = $mealName . " 🌿";
        }
        array_push($outArr, $mealName . " (" . $price . " Kč)");
    }
    $out = implode("<br>", $outArr);

    return $out;
}
?>
