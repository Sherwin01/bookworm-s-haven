<?php
require 'vendor/autoload.php'; // MongoDB PHP library

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017"); // MongoDB connection
$database = $client->bookstore; // 
$cartCollection = $database->carts; // Collection for carts


$userId = 'user123'; 

// Check if 'book_id' is passed in the URL
if (isset($_GET['book_id'], $_GET['title'], $_GET['price'], $_GET['image'])) {
    $bookId = $_GET['book_id']; 
    $title = $_GET['title'];
    $price = $_GET['price']; 
    $image = $_GET['image']; 

    // Check if the user already has a cart
    $userCart = $cartCollection->findOne(['user_id' => $userId]);

    // If user cart exists, update it
    if ($userCart) {
        // Check if the book already exists in the cart
        $existingBookIndex = -1; // Start with no match
        foreach ($userCart['items'] as $index => $item) {
            if ($item['book_id'] === $bookId) {
                $existingBookIndex = $index; // Save the index of the existing book
                break;
            }
        }

        if ($existingBookIndex >= 0) {
            // If the book is found, increase the quantity by 1
            $userCart['items'][$existingBookIndex]['quantity'] += 1;
        } else {
            // If the book is not found, add it to the cart
            $userCart['items'][] = [
                'book_id' => $bookId,
                'title' => $title,
                'price' => $price,
                'quantity' => 1,
                'image' => $image
            ];
        }

        // Update the cart in MongoDB
        $cartCollection->updateOne(
            ['user_id' => $userId],
            ['$set' => ['items' => $userCart['items']]]
        );
    } else {
        // If the cart doesn't exist, create a new one
        $cartCollection->insertOne([
            'user_id' => $userId,
            'items' => [
                [
                    'book_id' => $bookId,
                    'title' => $title,
                    'price' => $price,
                    'quantity' => 1,
                    'image' => $image
                ]
            ]
        ]);
    }

    
    header('Location: cart.php');
    exit();
}
?>
