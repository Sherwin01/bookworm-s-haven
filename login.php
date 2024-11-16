<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "bookstore";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to check if username exists
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists
    if ($stmt->num_rows == 1) {
        // Bind result
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, login successful
            echo "Login successful!";
            // Redirect to a protected page or dashboard
            header("Location: /bookworm-s-haven/index.html");
            exit;
        } else {
            // Password is incorrect
            echo "Invalid password.";
        }
    } else {
        // Username does not exist
        echo "Invalid username.";
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>