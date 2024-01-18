<?php
function getAllFilters($db) {
    $query = "SELECT * FROM filter";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $filters = $stmt->fetchAll();
    return ($filters ? $filters : null);
}

function getFilterPath($filterName) {
    $filters = getAllFilters();
    return ($filters[$filterName] ? $filters[$filterName] : null);
}
?>
