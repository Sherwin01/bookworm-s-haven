<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2>List of Products</h2>
        <a class="btn btn-outline-primary" href="/bookworm-s-haven/create.php" role="Button">Add Product</a>
        <br>
        <table class="table">
        <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
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

                // read all row from database table
                $sql = "SELECT * FROM books ORDER BY book_id";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Invalid query: " . $conn->error);
                }
                
                //read data of each row
                $display_id = 1;
                while($row = $result->fetch_assoc()) {
                    echo "
                <tr>
                    <td>$display_id</td>
                    <td>$row[title]</td>
                    <td>$row[author]</td>
                    <td>$row[price]</td>
                    <td>$row[stock]</td>
                    <td>$row[category]</td> 
                    <td>
                        <a class='btn btn-outline-warning' href='/bookworm-s-haven/edit.php?id=$row[book_id]' role='Button'>Edit</a>
                        <a class='btn btn-outline-danger' href='/bookworm-s-haven/delete.php?id=$row[book_id]' role='Button'>Delete</a>
                    </td>
                </tr>
                    ";
                    $display_id++;
                }

                ?>
            </tbody>
        </table>
    </div>            
</body>
</html>