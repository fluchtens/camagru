<?php
function getAllFilters($db) {
    $query = "SELECT * FROM filter";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $filters = $stmt->fetchAll();
    return ($filters ? $filters : null);
}

function getFilter($db, $id) {
    $query = "SELECT * FROM filter WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $filter = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($filter ? $filter : null);
}
?>
