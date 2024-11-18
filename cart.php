<?php
session_start(); 

// Connect to MongoDB
require 'vendor/autoload.php'; 
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->bookstore;
$cartCollection = $database->carts;

$userId = 'user123'; 

// Fetch the user's cart from MongoDB
$userCart = $cartCollection->findOne(['user_id' => $userId]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="logo.jpg" alt="Bookstore Logo" style="height: 40px; width: auto;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="homepage.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Cart Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Shopping Cart</h2>

    <?php if ($userCart && isset($userCart['items'])): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $total = 0;
                    foreach ($userCart['items'] as $item): 
                        $totalPrice = $item['price'] * $item['quantity'];
                        $total += $totalPrice;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?php echo number_format($totalPrice, 2); ?></td>
                        <td><a href="removeFromCart.php?book_id=<?php echo $item['book_id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <h3>Total: $<?php echo number_format($total, 2); ?></h3>
            <div>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                <a href="homepage-products.php" class="btn btn-secondary">Continue Shopping</a>
            </div>
        </div>
        
    <?php else: ?>
        <p>Your cart is empty. <a href="homepage-products.php">Continue shopping</a> to add items to your cart.</p>
    <?php endif; ?>

</div>

<!-- Footer -->
<footer class="footer mt-5 bg-light py-3">
    <div class="container text-center">
        <p>&copy; 2024 Bookstore. All Rights Reserved.</p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
