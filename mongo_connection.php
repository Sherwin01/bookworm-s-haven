<?php
require 'vendor/autoload.php';

use MongoDB\Client;

function getMongoDBConnection() {
    $client = new Client("mongodb://localhost:27017"); // Update with your MongoDB URI
    $db = $client->bookstore; // Replace `bookstore` with your database name
    return $db;
}
?>
