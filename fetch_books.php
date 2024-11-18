<?php
// Start the session if necessary
session_start();


require 'vendor/autoload.php';  

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->bookstore;
$collection = $database->books;

// Function to fetch the best sellers (limit to 3 books)
function fetchBestSellers($collection) {
    $bestSellers = $collection->find([], ['limit' => 3]);
    return iterator_to_array($bestSellers);
}

// Function to fetch all books (limit to 4 books)
function fetchAllBooks($collection) {
    $allBooks = $collection->find([], ['limit' => 4]);
    return iterator_to_array($allBooks);
}

// Fetch data
$bestSellers = fetchBestSellers($collection);
$allBooks = fetchAllBooks($collection);

// Prepare the response
$response = [
    'bestSellers' => $bestSellers,
    'allBooks' => $allBooks
];

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
