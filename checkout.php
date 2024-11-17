<?php
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bookstore";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cart is not empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Calculate total amount from the cart
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Handle order creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Insert order into orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total_amount);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the inserted order ID
    $stmt->close();
    
    // Insert items into order_items table
    foreach ($_SESSION['cart'] as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $order_id, $item['book_id'], $item['quantity']);
        $stmt->execute();
        $stmt->close();
    }

    // Clear the cart after placing the order
    unset($_SESSION['cart']);
    
    // Redirect to the order confirmation page
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Checkout</h1>
        <h4>Order Summary</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_amount = 0;
                foreach ($_SESSION['cart'] as $item):
                    $item_total = $item['price'] * $item['quantity'];
                    $total_amount += $item_total;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?= htmlspecialchars($item['author']) ?></td>
                        <td>P<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>P<?= number_format($item_total, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Grand Total</th>
                    <th>P<?= number_format($total_amount, 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <form method="POST" action="checkout.php">
            <button type="submit" class="btn btn-success">Place Order</button>
            <a href="cart.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</body>
</html>
