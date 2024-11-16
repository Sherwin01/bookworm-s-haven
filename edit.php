<?php
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

$book_id = "";
$title = "";
$author = "";
$price = "";
$stock = "";
$category = "";

$errorMessage = "";
$successMessage = "";

// Check if product ID is set in the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch the product's current data
    $sql = "SELECT * FROM books WHERE book_id='$book_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $author = $row['author'];
        $price = $row['price'];
        $stock = $row['stock'];
        $category = $row['category'];
    } else {
        $errorMessage = "Product not found.";
    }
}

// Check if the form has been submitted to update the data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $category = $_POST["category"];

    // Validate form data
    do {
        if (empty($title) || empty($author) || empty($price) || empty($stock) || empty($category)) {
            $errorMessage = "All fields are required.";
            break;
        }

        // Update product information
        $sql = "UPDATE books SET title='$title', author='$author', price='$price', stock='$stock', category='$category' WHERE book_id='$book_id'";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Error updating record: " . $conn->error;
            break;
        }

        $successMessage = "Product updated successfully.";
        header("location: /bookworm-s-haven/admin.php");
        exit;

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>Edit Product</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>$errorMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        }
        if (!empty($successMessage)) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>$successMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        }
        ?>

        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Author</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="author" value="<?php echo $author; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Price</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="price" value="<?php echo $price; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Stock</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="stock" value="<?php echo $stock; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Category</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="category" value="<?php echo $category; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/bookworm-s-haven/admin.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>