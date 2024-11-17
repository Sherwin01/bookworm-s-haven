<?php
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id == 0) {
    header("Location: cart.php");
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

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    // Invalid order or not belonging to the current user
    header("Location: cart.php");
    exit;
}

// Fetch order items
$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Order Confirmation</h1>
        <h4>Order ID: <?= htmlspecialchars($order['order_id']) ?></h4>
        <p>Thank you for your purchase! Your order has been successfully placed.</p>

        <h5>Order Details:</h5>
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

                while ($item = $order_items_result->fetch_assoc()) {
                    // Fetch price from books table using book_id
                    $book_id = $item['book_id'];
                    $book_stmt = $conn->prepare("SELECT price, title, author FROM books WHERE book_id = ?");
                    $book_stmt->bind_param("i", $book_id);
                    $book_stmt->execute();
                    $book_result = $book_stmt->get_result();
                    $book = $book_result->fetch_assoc();
                
                    if ($book) {
                        $item_total = $book['price'] * $item['quantity'];
                        $total_amount += $item_total;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td>P<?= number_format($book['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>P<?= number_format($item_total, 2) ?></td>
                        </tr>
                    <?php
                    }
                    $book_stmt->close(); 
                    }?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Grand Total</th>
                    <th>P<?= number_format($total_amount, 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="mt-3">
            <a href="homepage-product.html" class="btn btn-primary">Continue Shopping</a>
            <a href="order_history.php" class="btn btn-secondary">View Order History</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>