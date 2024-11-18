<?php
session_start();

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

// Fetch all categories
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);

?>

<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!--Navigation bar-->
    <div>
          <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
              <div class="container-fluid">
                <a class="navbar-brand" href="index.html">Logo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="product.html">Products</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Contact Us</a>
                    </li>
                  </ul>
                  <form class="d-flex">
                    <a href="register.html" class="btn btn-outline-secondary me-2">Register</a>
                    <a href="loginpage.php" class="btn btn-outline-secondary me-2">Log in</a>
                  </form>
                </div>
              </div>
            </nav>
      </div>
    <div class="container mt-5">
        <h1>Book Categories</h1>

        <?php if ($categories_result->num_rows > 0): ?>
            <?php while ($category = $categories_result->fetch_assoc()): ?>
                <div class="category-section mt-4">
                    <h3><?= htmlspecialchars($category['name']) ?></h3>
                    <p><?= htmlspecialchars($category['description']) ?></p>

                    <?php
                    // Fetch books for this category
                    $category_id = $category['category_id'];
                    $books_query = "SELECT * FROM books WHERE category = ?";
                    $stmt = $conn->prepare($books_query);
                    $stmt->bind_param("i", $category_id);
                    $stmt->execute();
                    $books_result = $stmt->get_result();
                    ?>

                    <?php if ($books_result->num_rows > 0): ?>
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Author</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($book = $books_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($book['title']) ?></td>
                                        <td><?= htmlspecialchars($book['author']) ?></td>
                                        <td>P<?= number_format($book['price'], 2) ?></td>
                                        <td>
                                            <a href="cart.php?book_id=<?= $book['book_id'] ?>" class="btn btn-primary btn-sm">Add to Cart</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No books available in this category.</p>
                    <?php endif; ?>

                    <?php $stmt->close(); ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
