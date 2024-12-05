<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "ceat";

// Get the row ID from the URL parameter
$id = $_GET['id'];

// Create connection (mysqli method)
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to delete row with the given ID
$sql = "DELETE FROM contact WHERE id = $id";

// Execute the query
if ($conn->query($sql) === TRUE) {
    // Row deleted successfully
    echo "Row deleted successfully";
} else {
    // Error deleting row
    echo "Error deleting row: " . $conn->error;
}

// Close connection
$conn->close();
?>
