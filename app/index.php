<!DOCTYPE html>
<html>
<head>
  <title>camagru</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
    <tbody id="tableBody">
    </tbody>
  </table>
  <script>
    $(document).ready(function() {
      $.ajax({
        url: 'get_users.php',
        method: 'GET',
        success: function(data) {
          const jsonData = JSON.parse(data);
          const tbody = document.getElementById('tableBody');

          jsonData.forEach(function(data) {
            const row = tbody.insertRow();
            const cell1 = row.insertCell(0);
            const cell2 = row.insertCell(1);
            const cell3 = row.insertCell(2);
            cell1.innerHTML = data.id;
            cell2.innerHTML = data.username;
            cell3.innerHTML = data.password;
          });
        }
      });
    });
  </script>
</body>
</html>
