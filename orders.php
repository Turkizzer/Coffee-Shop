<?php
$host = "localhost"; 
$port = "5432"; 
$dbname = "users";
$user = "postgres"; 
$password = "07082021"; 

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Function to handle data deletion
    function deleteData($conn, $owner_id) {
        $sql = "DELETE FROM coffee WHERE owner_id = :owner_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':owner_id', $owner_id);
        $stmt->execute();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if delete button clicked
        if (isset($_POST['delete'])) {
            $owner_id = $_POST['owner_id'];
            deleteData($conn, $owner_id);
        } else {
            // Handle data update
            $owner_id = $_POST['owner_id'];
            $owner_name = $_POST['owner_name'];
            $price = $_POST['price'];
            $flavor = $_POST['flavor'];

            $sql = "UPDATE coffee SET owner_name = :owner_name, price = :price, flavor = :flavor WHERE owner_id = :owner_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':owner_name', $owner_name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':flavor', $flavor);
            $stmt->bindParam(':owner_id', $owner_id);
            $stmt->execute();
        }
    }

    // Query to select all records from the table ordered by owner_id
    $stmt = $conn->query("SELECT * FROM coffee ORDER BY owner_id");
    $coffees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('shop1.png'); /* Set the background image */
            background-size: cover; /* Cover the entire background */
            background-repeat: no-repeat; /* Do not repeat the background image */
            background-attachment: fixed; /* Fix the background image so it doesn't scroll */
            display: flex;
            justify-content: center; /* Horizontally center content */
            align-items: center; /* Vertically center content */
            height: 100vh;
        }

        .container {
            padding: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }

        table tbody tr:hover {
            background-color: #f2f2f2;
        }

        .editable {
            display: none;
        }

        .btn-container {
            display: flex;
        }

        .btn-container .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div>
        <h2>Coffee Data</h2>
        <a href="add.php" class="btn btn-success mb-3">Add New</a>
        <a href="scratch.php" class="btn btn-danger mb-3 mr-3">Exit</a>
        <?php if (!empty($coffees)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Owner ID</th>
                            <th>Owner Name</th>
                            <th>Price</th>
                            <th>Flavor</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coffees as $coffee) : ?>
                            <form method="POST">
                                <tr>
                                    <td><?php echo $coffee['owner_id']; ?><input type="hidden" name="owner_id" value="<?php echo $coffee['owner_id']; ?>"></td>
                                    <td>
                                        <span class="non-editable"><?php echo $coffee['owner_name']; ?></span>
                                        <input type="text" class="form-control editable" name="owner_name" value="<?php echo $coffee['owner_name']; ?>">
                                    </td>
                                    <td>
                                        <span class="non-editable"><?php echo $coffee['price']; ?></span>
                                        <input type="text" class="form-control editable" name="price" value="<?php echo $coffee['price']; ?>">
                                    </td>
                                    <td>
                                        <span class="non-editable"><?php echo $coffee['flavor']; ?></span>
                                        <input type="text" class="form-control editable" name="flavor" value="<?php echo $coffee['flavor']; ?>">
                                    </td>
                                    <td class="btn-container">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-secondary cancel-btn">Cancel</button>
                                        <button type="button" class="btn btn-info edit-btn">Edit</button>
                                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                    </td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        <?php else : ?>
            <p>No coffee data found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    const editButtons = document.querySelectorAll('.edit-btn');
    const cancelButtons = document.querySelectorAll('.cancel-btn');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr');
            const nonEditableFields = row.querySelectorAll('.non-editable');
            const editableFields = row.querySelectorAll('.editable');

            nonEditableFields.forEach(field => {
                field.style.display = 'none';
            });

            editableFields.forEach(field => {
                field.style.display = 'block';
            });
        });
    });

    cancelButtons.forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr');
            const nonEditableFields = row.querySelectorAll('.non-editable');
            const editableFields = row.querySelectorAll('.editable');

            nonEditableFields.forEach((field, index) => {
                field.style.display = 'block';
                editableFields[index].style.display = 'none';
                editableFields[index].value = field.textContent.trim();
            });
        });
    });
</script>
</body>
</html>
