<?php
session_start();
require 'vendor/autoload.php';  


$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->bookstore;
$usersCollection = $database->users;  
$ordersCollection = $database->orders;  

// Get current user
$userId = $_SESSION['user_id'];
$user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);

// Initialize variables
$email = $password = "";
$emailError = $passwordError = "";
$orderHistory = [];

// Fetch user's order history
$orderHistory = $ordersCollection->find(['user_id' => new MongoDB\BSON\ObjectId($userId)])->toArray();

// Handle form submission to update user email or password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    if (isset($_POST["email"])) {
        $email = htmlspecialchars(trim($_POST["email"]));
    }
    if (isset($_POST["password"])) {
        $password = htmlspecialchars(trim($_POST["password"]));
    }

    $valid = true;

    // Validate email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "A valid email is required.";
        $valid = false;
    }

    // Validate password
    if (!empty($password) && strlen($password) < 6) {
        $passwordError = "Password must be at least 6 characters long.";
        $valid = false;
    }

    // Update user data if valid
    if ($valid) {
        $updateData = [];

        if (!empty($email)) {
            $updateData['email'] = $email;
        }

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (count($updateData) > 0) {
            // Update the user document
            $usersCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($userId)],
                ['$set' => $updateData]
            );

            // Refresh user data
            $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Profile Section -->
<div class="container mt-5">
    <h2 class="text-center">User Profile</h2>

    <!-- Update Profile Form -->
    <div class="row mt-4">
        <div class="col-md-6 mx-auto">
            <h4>Update Your Information</h4>
            <form method="POST" action="profile.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <span class="text-danger"><?php echo $emailError; ?></span>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                    <span class="text-danger"><?php echo $passwordError; ?></span>
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>

            <!-- Cancel Button -->
            <a href="homepage.php" class="btn btn-secondary mt-3">Cancel</a>
        </div>
    </div>

    <!-- Order History Section -->
    <div class="row mt-5">
        <div class="col-md-12">
            <h4>Your Order History</h4>
            <?php if (count($orderHistory) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order Date</th>
                            <th>Order Total</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderHistory as $order): ?>
                            <tr>
                                <td><?php echo date('F j, Y', $order['order_date']->toDateTime()->getTimestamp()); ?></td>
                                <td>$<?php echo number_format($order['total'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><a href="order_details.php?order_id=<?php echo $order['_id']; ?>" class="btn btn-info btn-sm">View Details</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have not placed any orders yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
