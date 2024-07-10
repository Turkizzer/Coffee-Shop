<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<style>
     body {
            padding: 100px;
            background-image: url('shop5.png'); /* Set the background image */
            background-size: cover; /* Cover the entire background */
            background-repeat: no-repeat; /* Do not repeat the background image */
            background-attachment: fixed; /* Fix the background image so it doesn't scroll */
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 50px;
            box-shadow: rgba(100,100,111,0.4)  0px 5px 27px 0px;
            background-color: rgba(255, 255, 255, 0.8); /* Add a semi-transparent white background */
        }
        .form-group {
            margin-bottom: 30px;
        }
</style>
<body>
<?php
include('connect.php');

$successMessage = ""; // Initialize a variable to hold the success message
$passwordsMatchError = ""; // Initialize a variable to hold the password match error message

if (isset($_POST["submit"])) {
    $flavor = $_POST["flavor"];
    $quantity = $_POST["quantity"];
   
    // Insert data into the database
    $sql = "INSERT INTO orders (flavor, quantity) VALUES (:flavor, :quantity)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':flavor', $flavor);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    $successMessage = "Order submitted successfully!";
}
?>
<div class="container">
    <form action="" method="POST">
        <div class="form-group">
            <label for="flavor">Flavor</label>
            <select name="flavor" id="flavor" class="form-control">
                <option>Mocha</option> 
                <option>French Vanilla</option>    
                <option>Caramel</option>    
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" class="form-control" name="quantity" placeholder="0" required>
        </div>
        <!-- Button to submit the form -->
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </form>
    <?php if (!empty($successMessage)) : ?>
    <div class="alert alert-success mt-3" role="alert">
        <?php echo $successMessage; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>
