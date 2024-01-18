<?php
function getAllFilters() {
    $filters = array(
        "fire" => "fire.png",
        "pikachu" => "pikachu.png",
    );
    return ($filters);
}

function getFilterPath($filterName) {
    $filters = getAllFilters();
    return ($filters[$filterName] ? $filters[$filterName] : null);
}
?>
