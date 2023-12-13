<?php
// header("refresh: 0");

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
?>

<!DOCTYPE html>
<html>
<head>
  <title>camagru</title>
</head>
<body>
  <h1>Users</h1>
  <table id="myTable">
    <thead>
      <tr>
        <th>id</th>
        <th>username</th>
        <th>password</th>
      </tr>
    </thead>
    <tbody>
      <?php
      echo "<script>";
      echo "var jsonData = " . json_encode($users) . ";";
      echo "</script>";
      ?>
    </tbody>
  </table>
  <script>
    const myTable = document.getElementById('myTable');
    const tbody = myTable.getElementsByTagName('tbody')[0];
    jsonData.forEach((data) => {
      const row = tbody.insertRow();
      const cell1 = row.insertCell(0);
      const cell2 = row.insertCell(1);
      const cell3 = row.insertCell(2);
      cell1.innerHTML = data.id;
      cell2.innerHTML = data.username;
      cell3.innerHTML = data.password;
    });
  </script>
</body>
</html>
