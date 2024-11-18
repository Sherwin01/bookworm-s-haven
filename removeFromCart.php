<?php
session_start(); 


require 'vendor/autoload.php'; 
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->bookstore;
$cartCollection = $database->carts;


$userId = 'user123';

// Check if a book_id is passed in the URL
if (isset($_GET['book_id'])) {
    $bookId = $_GET['book_id']; // Get the book ID from the URL

    // Find the cart for the user
    $userCart = $cartCollection->findOne(['user_id' => $userId]);

    if ($userCart && isset($userCart['items'])) {
        // Loop through the cart items to find the item to remove
        $updatedItems = array_filter($userCart['items'], function($item) use ($bookId) {
            return $item['book_id'] !== $bookId; // Remove the item with the matching book_id
        });

        // Re-index the array (important for MongoDB update)
        $updatedItems = array_values($updatedItems);

        // Update the cart in MongoDB
        $updateResult = $cartCollection->updateOne(
            ['user_id' => $userId],
            ['$set' => ['items' => $updatedItems]]
        );

        if ($updateResult->getModifiedCount() > 0) {
            // Redirect back to the cart page
            header("Location: cart.php");
            exit();
        } else {
            echo "Error: Could not update cart.";
        }
    }
} else {
    echo "Error: No book_id provided.";
}
?>
