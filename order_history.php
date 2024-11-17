<?php
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit;
}

$user_id = $_SESSION['user_id'];

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


$stmt = $conn->prepare("SELECT order_id, total_amount, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
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
                    <a href="profile.php" class="btn btn-outline-secondary me-2">My Profile</a>
                    <a href="logout.php" class="btn btn-outline-secondary me-2">Log out</a>
                  </form>
                </div>
              </div>
            </nav>
      </div>
    <div class="container mt-5">
        <h1>Order History</h1>
        <?php if ($orders_result->num_rows > 0): ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td>P<?= number_format($order['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                            <td>
                                <a href="order_confirmation.php?order_id=<?= $order['order_id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not placed any orders yet.</p>
            <a href="homepage-product.html" class="btn btn-primary">Start Shopping</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
