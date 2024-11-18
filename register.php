<?php
require 'vendor/autoload.php'; 


$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->bookstore;
$usersCollection = $database->users; 
$activityCollection = $database->activity_logs; 

// Initialize variables for form data and errors
$name = $email = $password = "";
$nameError = $emailError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    // Validate input
    $valid = true;

    if (empty($name)) {
        $nameError = "Name is required.";
        $valid = false;
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "A valid email is required.";
        $valid = false;
    }
    if (empty($password)) {
        $passwordError = "Password is required.";
        $valid = false;
    } elseif (strlen($password) < 6) {
        $passwordError = "Password should be at least 6 characters long.";
        $valid = false;
    }

    if ($valid) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the MongoDB database
        $insertResult = $usersCollection->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);

        if ($insertResult->getInsertedCount() > 0) {
            // Get the user's MongoDB _id for logging purposes
            $userId = $insertResult->getInsertedId();

            // Log the registration activity
            logUserActivity($userId, 'registered', 'User successfully registered.');

            echo "<script>alert('Registration successful! Please log in.'); window.location = 'login.php';</script>";
        } else {
            echo "<script>alert('An error occurred while registering. Please try again.');</script>";
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
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Registration Form -->
<div class="container mt-5">
    <h2 class="text-center">Create an Account</h2>
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
            <span class="text-danger"><?php echo $nameError; ?></span>
        </div>

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

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
