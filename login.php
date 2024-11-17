<?php
session_start();  


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
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the username exists
    if ($stmt->num_rows == 1) {
        // Bind result variables
        $stmt->bind_result($user_id, $db_username, $db_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password)) {
            // Password is correct, login successful
            $_SESSION['user_id'] = $user_id;  
            $_SESSION['username'] = $db_username;  

            // Redirect to the homepage or a user dashboard
            header("Location: homepage.html");  
            exit();
        } else {
            // Invalid password
            header("Location: loginpage.php?error=1");
            exit();
        }
    } else {
        // Invalid username
        header("Location: loginpage.php?error=1");
        exit();
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
