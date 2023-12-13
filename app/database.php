<?php
function connectToDatabase() {
  $servername = "db";
  $username = "fluchten";
  $password = "19";
  $dbname = "camagru";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
  }

  return $conn;
}
?>