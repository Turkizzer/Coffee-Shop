<?php
$host = "localhost"; 
$port = "5432"; 
$dbname = "users";
$user = "postgres"; 
$password = "123456"; 

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        // Retrieve form data
        $owner_name = $_POST['owner_name'];
        $price = $_POST['price'];
        $flavor = $_POST['flavor'];

        // Insert data into the database
        $sql = "INSERT INTO coffee (owner_name, price, flavor) VALUES (:owner_name, :price, :flavor)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':owner_name', $owner_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':flavor', $flavor);
        $stmt->execute();

        // Redirect back to index.php after insertion
        header("Location: login.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Coffee</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>
<body>
<div class="container">
    <h2>Add New Data</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="owner_name" class="form-label">Owner Name</label>
            <input type="text" class="form-control" id="owner_name" name="owner_name" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="flavor" class="form-label">Flavor</label>
            <input type="text" class="form-control" id="flavor" name="flavor" required>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="login.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>



