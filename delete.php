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

// Check if product_id is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // SQL to delete the product with the provided product_id
    $sql = "DELETE FROM books WHERE book_id = ?";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Redirect to the main inventory page after deletion
        header("Location: /bookworm-s-haven/admin.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    // If id is not set or invalid, redirect back to the main inventory page
    header("Location: /bookworm-s-haven/admin.php");
    exit;
}

// Close the connection
$conn->close();
?>