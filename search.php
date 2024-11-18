<?php
require 'vendor/autoload.php';

// MongoDB connection details
$uri = "mongodb://localhost:27017";
$client = new MongoDB\Client($uri);

// Select the database and collection
$database = $client->selectDatabase('bookstore'); 
$collection = $database->selectCollection('books'); 

// Enable JSON response
header('Content-Type: application/json');

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $searchQuery = trim($_GET['query']);
    
    // Use MongoDB regex for case-insensitive partial matching
    $regex = new MongoDB\BSON\Regex($searchQuery, 'i');
    $cursor = $collection->find(['title' => $regex]);

    $books = [];
    foreach ($cursor as $book) {
        $books[] = [
            'title' => $book['title'],
            'author' => $book['author'],
            'price' => $book['price'],
            'image' => $book['image'] ?? 'default.jpg', // Fallback to a default image
        ];
    }

    echo json_encode($books);
} else {
    echo json_encode([]); // Return an empty array if no query
}
?>
