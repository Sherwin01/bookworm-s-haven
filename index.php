<?php
// Include the Composer autoloader if you're using Composer
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
    <title>Bookworm's Haven</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
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

<!-- Hero Section -->
<div class="hero-section">
    <h1>Welcome to Our Bookstore</h1>
    <p>Explore the best books at amazing prices</p>
    <a href="homepage-products.php" class="btn btn-primary">Shop Now</a>
</div>

        <!-- Best Seller Section (Carousel) -->
<div class="container mt-5">
    <h2 class="text-center">Best Sellers</h2>
    <div id="bestSellersCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php 
            $active = true;  // Start by making the first item active
            foreach ($bestSellers as $book): ?>
                <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                    <!-- Wrap the content in a smaller div -->
                    <div class="col-md-4 mx-auto">
                        <div class="carousel-item-content">
                            <!-- Display the image and text -->
                            <img src="<?php echo htmlspecialchars($book['image']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($book['title']); ?>">
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?php echo htmlspecialchars($book['title']); ?></h5>
                                <p><?php echo htmlspecialchars($book['author']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                $active = false;  // After the first iteration, set active to false
            endforeach; ?>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
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
        <a href="product.php" class="btn btn-primary">View All Products</a>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Bookstore. All Rights Reserved.</p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>