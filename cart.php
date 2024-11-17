<?php
session_start();

// Initialize the cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: loginpage.php");
    exit;
}

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
echo "Connected Successfully.";

// Handle adding a book to the cart
if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Fetch book details from the database
    $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        
        // Add to cart session
        $cart_item = [
            'book_id' => $book['book_id'],
            'title' => $book['title'],
            'author' => $book['author'],
            'price' => $book['price'],
            'quantity' => $quantity
        ];

        // Check if the book is already in the cart
        $exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['book_id'] == $book_id) {
                $item['quantity'] += $quantity;
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $_SESSION['cart'][] = $cart_item;
        }
    }
    $stmt->close();
}

// Handle removing an item from the cart
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($remove_id) {
        return $item['book_id'] !== $remove_id;
    });
}

// Handle clearing the entire cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
}

// Handle updating quantity in the cart
if (isset($_POST['update_quantity'])) {
    $updated_quantities = $_POST['quantity'];
    foreach ($_SESSION['cart'] as &$item) {
        if (isset($updated_quantities[$item['book_id']])) {
            $item['quantity'] = intval($updated_quantities[$item['book_id']]);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     <!--Navigation bar-->
     <div>
          <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
              <div class="container-fluid">
                <a class="navbar-brand" href="homepage.html">Logo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="homepage.html">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="homepage-product.html">Products</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="homepage-contact-us.html">Contact Us</a>
                    </li>
                  </ul>
                  <form class="d-flex">
                    <a href="profile.html" class="btn btn-outline-secondary me-2">My Profile</a>
                    <a href="logout.php" class="btn btn-outline-secondary me-2">Log out</a>
                  </form>
                </div>
              </div>
            </nav>
        </div>
    <div class="container mt-5">
        <h1>Your Shopping Cart</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
            <form method="POST" action="cart.php">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Author</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item): 
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['title']) ?></td>
                                <td><?= htmlspecialchars($item['author']) ?></td>
                                <td>P<?= number_format($item['price'], 2) ?></td>
                                <td>
                                    <input type="number" name="quantity[<?= $item['book_id'] ?>]" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 60px;">
                                </td>
                                <td>P<?= number_format($item_total, 2) ?></td>
                                <td>
                                    <a href="cart.php?remove=<?= $item['book_id'] ?>" class="btn btn-danger btn-sm">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Grand Total</th>
                            <th>P<?= number_format($total, 2) ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="text-end">
                    <button type="submit" name="update_quantity" class="btn btn-warning m-2">Update Quantities</button>
                    <a href="checkout.php" class="btn btn-success m-2">Proceed to Checkout</a>
                    <a href="cart.php?clear=1" class="btn btn-danger m-4">Clear Cart</a>
                </div>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
            <a href="homepage-product.html" class="btn btn-primary m-3">Back to Products</a>
        <?php endif; ?>
    </div>
</body>
</html>