<?php

require 'vendor/autoload.php'; 

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017"); 
$db = $client->bookstore;  

// Get the collections
$booksCollection = $db->books;

// Fetch Best Seller Books (Top 3 books)
$bestSellers = $booksCollection->find([], ['limit' => 3]);

// Fetch All Books (Top 4 books)
$allBooks = $booksCollection->find([], ['limit' => 4]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="homepage.php">
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
                        <a class="nav-link" href="homepage-products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="homepage-about-us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Welcome to Our Bookstore</h1>
        <p>Explore the best books at amazing prices</p>
        <a href="homepage-products.php" class="btn btn-primary">Shop Now</a>
    </div>

    <!-- Best Seller Section (Grid layout) -->
    <div class="container mt-5">
        <h2 class="text-center">Best Sellers</h2>
        <div class="row">
            <?php foreach ($bestSellers as $book): ?>
                <div class="col-md-3">
                    <div class="book">
                        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="img-fluid">
                        <h5><?php echo htmlspecialchars($book['title']); ?></h5>
                        <p><?php echo htmlspecialchars($book['author']); ?></p>
                        <p><strong>$<?php echo htmlspecialchars($book['price']); ?></strong></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- All Books Section -->
    <div class="container mt-5">
        <h2 class="text-center">All Books</h2>
        <div class="row">
            <?php foreach ($allBooks as $book): ?>
                <div class="col-md-3">
                    <div class="book">
                        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <h5><?php echo htmlspecialchars($book['title']); ?></h5>
                        <p><?php echo htmlspecialchars($book['author']); ?></p>
                        <p><strong>$<?php echo htmlspecialchars($book['price']); ?></strong></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="homepage-products.php" class="btn btn-primary">View All Products</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Bookstore. All Rights Reserved.</p>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
