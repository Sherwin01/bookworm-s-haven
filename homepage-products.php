<?php
require 'vendor/autoload.php'; 

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");


$database = $client->bookstore;
$collection = $database->books;

// Fetch all available books
$allBooks = $collection->find([])->toArray(); 

// Fetch best sellers (For simplicity, filter by a category or predefined logic)
$bestSellers = $collection->find(['category' => 'Fiction'])->toArray(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
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
                    <a class="nav-link" href="homepage-products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="home-about-us.php">About Us</a>
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
                        <button class="btn btn-primary btn-sm">
                            <a href="add_to_cart.php?book_id=<?php echo urlencode($book['_id']); ?>&title=<?php echo urlencode($book['title']); ?>&price=<?php echo urlencode($book['price']); ?>&image=<?php echo urlencode($book['image']); ?>" class="text-white text-decoration-none">Add to Cart</a>
                        </button>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
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
            <div class="col-md-3 mb-4">
                <div class="book border p-3">
                    <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <h5><?php echo htmlspecialchars($book['title']); ?></h5>
                    <p><?php echo htmlspecialchars($book['author']); ?></p>
                    <p><strong>$<?php echo htmlspecialchars($book['price']); ?></strong></p>
                    <button class="btn btn-primary btn-sm">
                        <a href="add_to_cart.php?book_id=<?php echo urlencode($book['_id']); ?>&title=<?php echo urlencode($book['title']); ?>&price=<?php echo urlencode($book['price']); ?>&image=<?php echo urlencode($book['image']); ?>" class="text-white text-decoration-none">Add to Cart</a>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<div class="footer mt-5">
    <p>&copy; 2024 Bookstore. All Rights Reserved.</p>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
