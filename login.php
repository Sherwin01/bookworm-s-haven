<?php
session_start();
require 'vendor/autoload.php'; 

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->bookstore;
$usersCollection = $database->users; // Collection to store user data
$activityCollection = $database->activity_logs; // Collection to store user activity logs

$email = $password = "";
$emailError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    // Validate input
    $valid = true;

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "A valid email is required.";
        $valid = false;
    }
    if (empty($password)) {
        $passwordError = "Password is required.";
        $valid = false;
    }

    if ($valid) {
        // Check if user exists in the database
        $user = $usersCollection->findOne(['email' => $email]);

        if ($user && password_verify($password, $user['password'])) {
            // Start session and store user data
            $_SESSION['user_id'] = (string)$user['_id'];  
            $_SESSION['user_name'] = $user['name'];  

            // Log the login activity
            logUserActivity($user['_id'], 'logged in', 'User successfully logged in.');

          
            header("Location: homepage.php");
            exit;
        } else {
            $passwordError = "Invalid email or password.";
        }
    }
}

// Helper function to log user activities
function logUserActivity($userId, $action, $details = null) {
    require 'vendor/autoload.php'; // MongoDB PHP library

    // Connect to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $database = $client->bookstore;
    $activityCollection = $database->activity_logs;

    // Prepare the activity log entry
    $activityLog = [
        'user_id' => $userId, 
        'action' => $action,
        'timestamp' => new MongoDB\BSON\UTCDateTime(), 
        'details' => $details 
    ];

    // Insert the log entry into the activity_logs collection
    $activityCollection->insertOne($activityLog);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Login Form -->
<div class="container mt-5">
    <h2 class="text-center">Login to Your Account</h2>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            <span class="text-danger"><?php echo $emailError; ?></span>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <span class="text-danger"><?php echo $passwordError; ?></span>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
