<?php
session_start();
require 'vendor/autoload.php';  

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->bookstore;
$usersCollection = $database->users;  // User collection
$ordersCollection = $database->orders;  // Orders collection

// Get current user ID from session
$userId = $_SESSION['user_id'];
$user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);

// Initialize order details
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalPrice = 0;

// Calculate total price
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Initialize error message
$shippingAddress = $paymentMethod = "";
$errorMessage = "";

// Handle form submission for order confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    if (isset($_POST['shipping_address'])) {
        $shippingAddress = htmlspecialchars(trim($_POST['shipping_address']));
    }
    if (isset($_POST['payment_method'])) {
        $paymentMethod = htmlspecialchars(trim($_POST['payment_method']));
    }

    // Validation: Ensure all fields are filled
    if (empty($shippingAddress) || empty($paymentMethod)) {
        $errorMessage = "Please fill in all fields.";
    } else {
        // Create an order document
        $order = [
            'user_id' => new MongoDB\BSON\ObjectId($userId),
            'items' => $cart,
            'shipping_address' => $shippingAddress,
            'payment_method' => $paymentMethod,
            'total' => $totalPrice,
            'order_date' => new MongoDB\BSON\UTCDateTime(),
            'status' => 'Pending',  // You can later implement status updates
        ];

        
        $ordersCollection->insertOne($order);

        // Clear the cart after successful order
        unset($_SESSION['cart']);

        
        header("Location: profile.php?order_success=true");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Checkout Section -->
<div class="container mt-5">
    <h2 class="text-center">Checkout</h2>

    <!-- Display cart summary -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h4>Order Summary</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-end"><strong>Total</strong></td>
                        <td><strong>$<?php echo number_format($totalPrice, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Checkout Form -->
    <div class="row mt-4">
        <div class="col-md-6 mx-auto">
            <h4>Shipping Information</h4>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <form method="POST" action="checkout.php">
                <div class="mb-3">
                    <label for="shipping_address" class="form-label">Shipping Address</label>
                    <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4" required><?php echo htmlspecialchars($shippingAddress); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="Credit Card" <?php echo $paymentMethod == 'Credit Card' ? 'selected' : ''; ?>>Credit Card</option>
                        <option value="PayPal" <?php echo $paymentMethod == 'PayPal' ? 'selected' : ''; ?>>PayPal</option>
                        <option value="Bank Transfer" <?php echo $paymentMethod == 'Bank Transfer' ? 'selected' : ''; ?>>Bank Transfer</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Confirm Order</button>
            </form>
        </div>
    </div>

    <!-- Cancel Button -->
    <a href="homepage.php" class="btn btn-secondary mt-3">Cancel</a>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
