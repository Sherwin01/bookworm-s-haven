<?php
require 'vendor/autoload.php'; 


$client = new MongoDB\Client("mongodb://localhost:27017");


$database = $client->bookstore;
$collection = $database->books;

// Fetch all available books
$allBooks = $collection->find([])->toArray(); // Fetch all books from the MongoDB collection

// Fetch best sellers (For simplicity, filter by a category or predefined logic)
$bestSellers = $collection->find(['category' => 'Fiction'])->toArray(); // Example filter
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="logo.jpg" alt="Bookstore Logo" style="height: 40px; width: auto;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="product.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about-us.php">About Us</a>
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

<!-- All Books Section -->
<div class="container mt-5">
    <h2 class="text-center">All Books</h2>
    <div class="row">
        <?php foreach ($allBooks as $book): ?>
            <div class="col-md-3 mb-4">
                <div class="book border p-3">
                    <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <h5><?php echo htmlspecialchars($book['title']); ?></h5>
                    <p><?php echo htmlspecialchars($book['author']); ?></p>
                    <p><strong>$<?php echo htmlspecialchars($book['price']); ?></strong></p>
                    <!-- Redirect to login page if user is not logged in -->
                    <a href="login.php" class="btn btn-primary btn-sm">Add to Cart</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Best Sellers Section -->
<div class="container mt-5">
    <h2 class="text-center">Best Sellers</h2>
    <div class="row">
        <?php foreach ($bestSellers as $book): ?>
            <div class="col-md-3 mb-4">
                <div class="book border p-3">
                    <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <h5><?php echo htmlspecialchars($book['title']); ?></h5>
                    <p><?php echo htmlspecialchars($book['author']); ?></p>
                    <p><strong>$<?php echo htmlspecialchars($book['price']); ?></strong></p>
                    <!-- Redirect to login page if user is not logged in -->
                    <a href="login.php" class="btn btn-primary btn-sm">Add to Cart</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- Footer -->
<div class="footer mt-5">
    <p>&copy; 2024 Bookstore. All Rights Reserved.</p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
