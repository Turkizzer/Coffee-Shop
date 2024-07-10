<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit(); // Ensure that script execution stops here
}

// Include the database connection
include('connect.php');

try {
    // Retrieve the customer_id of the logged-in user
    $customer_id = $_SESSION['user']['customer_id'];
    
    // Prepare SQL statement to select orders for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY date_of_transac");
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle any database errors
    $errorMessage = "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('order.jpg'); 
            background-size: cover; 
            background-repeat: no-repeat; 
            background-attachment: fixed; 
            display: flex;
            justify-content: center; 
            align-items: center; 
            height: 100vh;
        }

        .container {
            padding: 20px;
            margin:auto;
        }
        

        h2 {
            color: white; /* Dark brown text color */
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(242, 226, 204, 0.8); /* Light brown background for table */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #c2a482; /* Darker brown border */
            text-align: left;
            color: black;
            font-family:Georgia;
            font-size:20px;
        }

        table th {
            rgba(194, 164, 130, 0.3); 
            font-weight: bold;
            text-transform: uppercase;
            color: #black; 
        }

        table tbody tr:hover {
            background-color: #dbc6a0; /* Lighter brown background on hover */
        }

        .btn-exit {
            background-color: #4d2e1e; /* Dark brown button background */
            color: #fff; /* White text color */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn-exit:hover {
            background-color: #6b4a30; /* Darker brown background on hover */
        }
        h2, .my {
            font-family: bold;
            font-size: 60px;
        }

        .my {
            height: 20%;
            width: 20%;
            margin: auto;
            background-color: rgba(122, 122, 122, 0.5);
            border-radius: 20px;
            display: block;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            body {
                background-image: none; /* Remove background image on small screens */
                height: auto; /* Adjust height for smaller screens */
            }

            .container {
                padding: 10px; /* Reduce padding on small screens */
            }

            h2, .my {
                font-size: 30px; /* Reduce font size on small screens */
            }

            .my {
                height: auto; /* Adjust div size for smaller screens */
                width: auto; /* Adjust div size for smaller screens */
                padding: 10px; /* Add padding to the div */
            }

            table {
                font-size: 14px; /* Reduce font size in table on small screens */
            }

            table th, table td {
                padding: 8px; /* Reduce padding in table cells on small screens */
            }

            .btn-exit {
                padding: 5px 10px; /* Reduce button padding on small screens */
                font-size: 14px; /* Reduce button font size on small screens */
            }
        }

        @media only screen and (max-width: 600px) {
    body {
        /* Adjust the background image for smaller screens */
        background-image: url('order.jpg'); 
        background-size: cover; /* Keep it as 'cover' for a full background */
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Prevent the image from repeating */
        height: auto; /* Adjust height for smaller screens */
    }

        /* You can add more media queries for larger screens as needed */
    </style>
</head>
<body>
<div class="container">
    <div>
    <a href="scratch.php" class="btn btn-exit mt-3">Exit</a>
        <div class="my"><h2>My Order</h2></div>
        <?php if (!empty($orders)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Coffee Flavor</th>
                            <th>Coffee Quantity</th>
                            <th>Dessert Flavor</th>
                            <th>Dessert Quantity</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?php echo $order['coffee_flavor']; ?></td>
                                <td><?php echo $order['coffee_quantity']; ?></td>
                                <td><?php echo $order['dessert_flavor']; ?></td>
                                <td><?php echo $order['dessert_quantity']; ?></td>
                                <td><?php echo $order['date_of_transac']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        <?php else : ?>
            <p>No order data found.</p>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
