<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$servername = "localhost";
$username = "root";
$password = "";
$database = "bookstore";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$user_stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
if ($user_stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_username = $_POST['username'];
    $updated_email = $_POST['email'];
    $updated_password = $_POST['password'];
    $updated_confirm_password = $_POST['confirmPassword'];

    // Validate password
    if ($updated_password !== $updated_confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Update username and email
    $update_stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $update_stmt->bind_param("ssi", $updated_username, $updated_email, $user_id);
    $update_stmt->execute();

    // If password is updated, hash and update it
    if (!empty($updated_password)) {
        $hashed_password = password_hash($updated_password, PASSWORD_BCRYPT);
        $update_password_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $update_password_stmt->bind_param("si", $hashed_password, $user_id);
        $update_password_stmt->execute();
    }

    echo "Profile updated successfully!";
    header("Refresh: 2; url=profile.php"); // Redirect to profile after 2 seconds
}

// Get order history
$order_stmt = $conn->prepare("SELECT order_id, total_amount, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
        <!--Profile form-->
        <h1>Your Profile</h1>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>

        <!-- Order History Section -->
        <h2 class="mt-5">Your Order History</h2>
        <?php if ($order_result->num_rows > 0): ?>
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
                    <?php while ($order = $order_result->fetch_assoc()): ?>
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

$user_stmt->close();
$order_stmt->close();
$conn->close();
?>
