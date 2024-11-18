<?php
require 'vendor/autoload.php'; // Composer autoloader for MongoDB

// MongoDB connection settings
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->selectDatabase("user_activity_db");
$collection = $database->selectCollection("user_activity_logs");

function logUserActivity($userId, $action, $additionalInfo = []) {
    global $collection; // MongoDB collection reference

    // Prepare the log data
    $logData = [
        'user_id' => $userId,
        'action' => $action,
        'timestamp' => new MongoDB\BSON\UTCDateTime(),  // Current timestamp
        'additional_info' => $additionalInfo
    ];

    try {
        // Insert the log data into the MongoDB collection
        $collection->insertOne($logData);
    } catch (Exception $e) {
        // Handle any errors that occur during the insert
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}

// Example of logging user activity
logUserActivity("123456", "registration");
logUserActivity("123456", "login");
logUserActivity("123456", "book_search", ['search_term' => 'web development']);
logUserActivity("123456", "order_placement", ['order_id' => 'ORD123', 'total_price' => 29.99]);

function getUserActivityLogs($userId) {
    global $collection; // MongoDB collection reference

    try {
        // Fetch the logs for the specified user
        $logs = $collection->find(['user_id' => $userId]);

        // Convert the logs to an array and return
        return iterator_to_array($logs);
    } catch (Exception $e) {
        // Handle any errors that occur during the fetch
        echo "Error: " . htmlspecialchars($e->getMessage());
        return [];
    }
}

$userId = "123456"; // This should be dynamically retrieved, e.g., from session

// Get the user's activity logs
$activityLogs = getUserActivityLogs($userId);

// Display the logs
echo "<h3> User Activity Logs </h3>";
foreach ($activityLogs as $log) {
    echo "<p><strong>Action:</strong> " . htmlspecialchars($log['action']) . "<br>";
    echo "<strong>Timestamp:</strong> " . $log['timestamp']->toDateTime()->format('Y-m-d H:i:s') . "<br>";
    
    if (!empty($log['additional_info'])) {
        echo "<strong>Details:</strong><br>";
        foreach ($log['additional_info'] as $key => $value) {
            echo htmlspecialchars("$key: $value") . "<br>";
        }
    }
    echo "</p><hr>";
}
?>
