<?php
function show_categories() {
    include "bdconnect.php";
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($link, $sql) or die("Query failed");
    $str = "";
    while ($row = mysqli_fetch_array($result)) {
        $array_category[$row["id_cat"]] = $row["category"];
        $str = $str."<option value='".$row["category"]."'>".$row["category"]."</option>";
    }
    return $str;
}
?>