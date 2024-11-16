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
echo "Connected Successfully.";

$title = "";
$author = "";
$price = "";
$stock = "";
$category = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $category = $_POST["category"];

    do {
        if (empty($title) || empty($author) || empty($price) || empty($stock) || empty($category)) {
            $errorMessage = "All fields are required.";
            break;
        }

        // Insert new product into database
        $sql = "INSERT INTO books (title, author, price, stock, category) VALUES ('$title', '$author', '$price', '$stock', '$category')";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $title = "";
        $author = "";
        $price = "";
        $stock = "";
        $category = "";

        $successMessage = "Product successfully added.";

        header("location: /bookworm-s-haven/admin.php");
        exit;

    } while (false);
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookworm's Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2>Add Product</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong><?= $errorMessage ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?= $title ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Author</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="author" value="<?= $author ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Price</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="price" value="<?= $price ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Stock</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="stock" value="<?= $stock ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Category</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="category" value="<?= $category ?>">
                </div>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-6">
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong><?= $successMessage ?></strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/bookworm-s-haven/admin.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>