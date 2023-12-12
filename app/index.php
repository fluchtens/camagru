<?php

$servername = "db";
$username = "fluchten";
$password = "19";
$dbname = "camagru";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Error: " . $conn->connect_error);
}

echo "Connected to the database successfully";

$conn->close();

header("refresh: 3"); 

?>

<!DOCTYPE html>
<html>
<head>
  <title>camagru</title>
</head>
<body>
  <?php echo '<p>Hello World</p>'; ?>
</body>
</html>
