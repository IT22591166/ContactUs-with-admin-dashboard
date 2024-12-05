<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submissions</title>
    <link rel="stylesheet" type="text/css" href="contact_admin.css">
</head>
<body>

<h2>Contact Form Submissions</h2>

<form method="post">
    Name: <input type="text" name="name">
    Email: <input type="text" name="email">
    <input type="checkbox" name="last_month" value="1"> Last Month
    <input type="checkbox" name="last_two_weeks" value="1"> Last Two Weeks
    <input type="submit" name="submit" value="Filter">
</form>
<button class="print" onclick="printReport()">Print Report</button>
<br>
<br>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Created at</th>
            <th>Respond</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "ceat";

        // Create connection (mysqli method)
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle filtering
        $filter = "WHERE 1=1"; // Initialize with a condition that is always true
        if(isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            if ($name) {
                $filter .= " AND name LIKE '%$name%'";
            }
            if ($email) {
                $filter .= " AND email LIKE '%$email%'";
            }
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

        // SQL query to retrieve data
        $sql = "SELECT id, name, email, message, created_at FROM contact $filter";

        // Execute query
        $result = $conn->query($sql);

        // Check if there are rows returned
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["message"] . "</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "<td><button class='blue-button' onclick='respondEmail(\"" . $row["email"] . "\")'>Respond</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>0 results</td></tr>";
        }

        // Close connection
        $conn->close();
        ?>
    </tbody>
</table>

<div id="notifications"></div>

<script>
    function respondEmail(email) {
        window.location.href = 'mailto:' + email;
    }

    // Function to print the report
    function printReport() {
        // Hide the print button
        document.querySelector('button.print').style.display = 'none';
        // Print the content
        window.print();
        // Show the print button again after printing
        document.querySelector('button.print').style.display = 'block';
    }

    // WebSocket connection
    const ws = new WebSocket('ws://localhost:8080');

    ws.onopen = () => {
        console.log('Connected to WebSocket server');
    };

    ws.onmessage = event => {
        const data = JSON.parse(event.data);
        showNotification(data.title, data.message);
    };

    ws.onclose = () => {
        console.log('Disconnected from WebSocket server');
    };

    function showNotification(title, message) {
        if (Notification.permission === 'granted') {
            new Notification(title, {
                body: message
            });
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    new Notification(title, {
                        body: message
                    });
                }
            });
        }

        // Also display the notification on the page
        const notificationDiv = document.getElementById('notifications');
        const notification = document.createElement('div');
        notification.innerHTML = `<strong>${title}</strong>: ${message}`;
        notificationDiv.appendChild(notification);
    }

    // Request notification permission on page load
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
</script>

</body>
</html>
