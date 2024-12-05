<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceat";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle filtering
$filter = "";
if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone_number = $_POST['email'];
    $filter = "WHERE name LIKE '%$name%' AND email LIKE '%$email%'";
}

// Filter by last month
if(isset($_POST['last_month'])) {
    $last_month = date('Y-m-d', strtotime('-1 month'));
    $filter .= " AND created_at >= '$last_month'";
}

// Filter by last two weeks
if(isset($_POST['last_two_weeks'])) {
    $last_two_weeks = date('Y-m-d', strtotime('-2 weeks'));
    $filter .= " AND created_at >= '$last_two_weeks'";
}

// Retrieve data
$sql = "SELECT * FROM contact $filter";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Report</title>
    <link rel="stylesheet" type="text/css" href="contact_report_css.css">
</head>
<body>

<h2>Contact Report</h2>

<form method="post">
    Name: <input type="text" name="name">
    Phone Number: <input type="text" name="phone_number">
    <input type="checkbox" name="last_month" value="1"> Last Month
    <input type="checkbox" name="last_two_weeks" value="1"> Last Two Weeks
    <input type="submit" name="submit" value="Filter">
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Created At</th>
    </tr>
    <?php
    // Display data
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['message']."</td>";
            echo "<td>".$row['created_at']."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No records found</td></tr>";
    }
    ?>
</table>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
