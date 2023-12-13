<?php
require 'database.php';

$conn = connectToDatabase();

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
if ($result) {
  $users = array();
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
} else {
  echo "Error: " . $conn->error;
}

$conn->close();

// Renvoie les données au format JSON
echo json_encode($users);
?>
