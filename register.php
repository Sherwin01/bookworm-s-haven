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
echo "Connected Successfully.";

$username = "";
$email = "";
$password = "";
$confirmPassword = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    do {
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $errorMessage = "All fields are required.";
            break;
        }

        // Insert new user into database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $username = "";
    $email = "";
    $password = "";
    $confirmPassword = "";

        $successMessage = "Product successfully added.";

        header("location: /bookworm-s-haven/index.html");
        exit;

    } while (false);
}


$conn->close();
?>
