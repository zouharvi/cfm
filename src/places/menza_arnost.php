<?php
function menza_arnost() {
    global $dayOfWeek;
    
    $pageRaw = file_get_contents(
        "https://kamweb.ruk.cuni.cz/webkredit/Api/Ordering/Menu?Dates=" . date("Y-m-d") . "&CanteenId=5"
    );
    $obj = json_decode($pageRaw);
    $out = "";
    
    $outArr = array();

    foreach($obj->groups[1]->rows as $group) {
        $mealName = $group->item->mealName;
        $price = $group->item->price;
        $available = $group->item->countAvailable;
        
        if(strpos(strtolower($mealName), "vegan") !== false) {
            $mealName = $mealName . " 🌿";
        }
        array_push($outArr, $mealName . " (" . $available . ' left, ' . $price . " Kč)");
    }
    $out = implode("<br>", $outArr);

    return $out;
}
?>
