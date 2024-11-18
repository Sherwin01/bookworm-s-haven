<?php
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $searchQuery = trim($_GET['query']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bookstore";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <div>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html">Logo</a>
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
        <h1>Search Results</h1>

        <?php
        if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
            $searchQuery = trim($_GET['query']);

            
            $stmt = $conn->prepare("SELECT book_id, title, author, price, stock FROM books WHERE title LIKE ?");
            $searchTerm = "%" . $searchQuery . "%";
            $stmt->bind_param("s", $searchTerm);

            $stmt->execute();
            $result = $stmt->get_result();

            
            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['title']) . "</td>
                            <td>" . htmlspecialchars($row['author']) . "</td>
                            <td>$" . number_format($row['price'], 2) . "</td>
                            <td>" . htmlspecialchars($row['stock']) . "</td>
                            <td>
                                <a href='cart.php?book_id=" . $row['book_id'] . "' class='btn btn-primary btn-sm'>Add to Cart</a>
                            </td>
                          </tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p class='no-results'>No results found for '" . htmlspecialchars($searchQuery) . "'.</p>";
            }

            
            $stmt->close();
        } else {
            echo "<p>Please enter a search query.</p>";
        }

    
        $conn->close();
        ?>

    </div>
</body>
</html>

<?php
ob_end_flush();
?>
