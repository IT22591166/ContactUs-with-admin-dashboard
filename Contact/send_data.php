<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ceat"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Insert the contact information into the database
$sql = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
    // Prepare WebSocket message
    $wsMessage = json_encode([
        'title' => 'New Contact Message',
        'message' => 'You have a new message from the contact form.'
    ]);

    // Send WebSocket message
    $ch = curl_init('http://localhost:8080'); // Ensure the WebSocket server is running
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $wsMessage);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo json_encode(["status" => "success", "message" => "Data inserted successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
