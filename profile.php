<?php
session_start();

include('connect.php'); // Assuming this file contains your database connection

$errorMessage = ""; // Initialize error message variable
$successMessage = ""; // Initialize success message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle data update
    $customer_id = $_SESSION['user']['customer_id'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password']; // Change variable name to distinguish from the existing password field
    $confirm_password = $_POST['confirm_password'];

    if (!empty($new_password) && !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $errorMessage = "New password and confirm password do not match.";
        } else {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update email and password in the database
            $sql = "UPDATE users SET email = :email, password = :password WHERE customer_id = :customer_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->execute();

            // Set success message
            $successMessage = "Password updated successfully!";
        }
    } else {
        // Update email only if no new password is provided
        $sql = "UPDATE users SET email = :email WHERE customer_id = :customer_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();

        // Set success message
        $successMessage = "Email updated successfully!";
    }
}

// Fetch user data from the session
$customer_id = $_SESSION['user']['customer_id']; // Changed variable name
$stmt = $conn->prepare("SELECT * FROM users WHERE customer_id = :customer_id"); // Fixed column name
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body {
            background-image: url('kape.png');
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 100vh;
            position: relative;
            background-color: #d2b48c; /* Coffee color */
        }
        .exit-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 9999; /* Ensure the button stays on top of other elements */
            background-color: #8b4513; /* Brown color */
            border-color: #8b4513; /* Brown color */
        }
        .card-header.bg-primary {
            background-color: #8b4513 !important; /* Brown color */
        }
        .card-header.bg-primary.text-white {
            color: #fff !important; /* White color */
        }
        .card-body {
            background-color: #d2b48c; /* Coffee color */
        }
        .btn-success {
            background-color: #8b4513; /* Brown color */
            border-color: #8b4513; /* Brown color */
        }
        .btn-success:hover {
            background-color: #8b4513; /* Brown color */
            border-color: #8b4513; /* Brown color */
        }
        .btn-secondary {
            background-color: #6c757d; /* Gray color */
            border-color: #6c757d; /* Gray color */
        }
        .btn-primary {
            background-color: #8b4513; /* Brown color */
            border-color: #8b4513; /* Brown color */
        }
        .btn-danger {
            background-color: #dc3545; /* Red color */
            border-color: #dc3545; /* Red color */
        }
        .form-label {
            color: #4b3a2a; /* Dark brown color */
        }
        .form-control {
            background-color: #f8f9fa; /* Light gray color */
        }
    </style>
</head>
<body>
<a href="scratch.php" class="btn btn-danger exit-button">Exit</a>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Account Information</h3>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($user['firstnme']) ? $user['firstnme'] : ''; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($user['lasttnme']) ? $user['lasttnme'] : ''; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="region" class="form-label">Region:</label>
                                <input type="text" class="form-control" id="region" name="region" value="<?php echo isset($user['region']) ? $user['region'] : ''; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="province" class="form-label">Province:</label>
                                <input type="text" class="form-control" id="province" name="province" value="<?php echo isset($user['province']) ? $user['province'] : ''; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City:</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo isset($user['city']) ? $user['city'] : ''; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="barangay" class="form-label">Barangay:</label>
                                <input type="text" class="form-control" id="barangay" name="barangay" value="<?php echo isset($user['barangay']) ? $user['barangay'] : ''; ?>" readonly>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Email & Password</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" id="passwordChangeForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php echo $user['password']; ?>" readonly>
                            </div>
                            <div id="passwordFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password:</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#confirmPasswordModal">Edit Password</button>
                                <button type="button" class="btn btn-secondary cancel-btn" style="display: none;">Cancel</button>
                            </div>
                            <?php if (!empty($errorMessage)): ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?php echo $errorMessage; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($successMessage)): ?>
                                <div class="alert alert-success mt-3" role="alert">
                                    <?php echo $successMessage; ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for confirming password change -->
    <div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Password Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to change your password?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPasswordBtnModal">Yes</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
    // Store original values
    let originalEmail = "<?php echo $user['email']; ?>";
    let originalPassword = "<?php echo $user['password']; ?>";

    document.getElementById('changePasswordBtn').addEventListener('click', function() {
        document.getElementById('passwordFields').style.display = 'block';
        document.getElementById('password').setAttribute('readonly', true);
        toggleCancelBtnVisibility(this);
        enableSaveChangesBtn(); // Enable the "Save Changes" button
    });

    // Function to enable the "Save Changes" button
    function enableSaveChangesBtn() {
        const saveChangesBtn = document.getElementById('confirmPasswordBtn');
        saveChangesBtn.removeAttribute('disabled');
    }

    // Function to toggle Cancel button visibility
    function toggleCancelBtnVisibility(changePasswordBtn) {
        const cancelBtn = changePasswordBtn.nextElementSibling;
        cancelBtn.style.display = 'inline-block';
        changePasswordBtn.style.display = 'none';
        document.getElementById('confirmPasswordBtn').style.display = 'block'; // Show the Save Changes button in the modal
    }

    // Add event listener for Cancel button
    const cancelBtn = document.querySelector('.cancel-btn');
    cancelBtn.addEventListener('click', function() {
        document.getElementById('passwordFields').style.display = 'none';
        document.getElementById('password').removeAttribute('readonly');
        this.style.display = 'none';
        document.getElementById('changePasswordBtn').style.display = 'inline-block';
        document.getElementById('confirmPasswordBtn').style.display = 'none'; // Hide the Save Changes button in the modal
    });

    // Handle form submission within the modal
    document.getElementById('confirmPasswordBtn').addEventListener('click', function() {
        // Show the modal for confirming password change
        $('#confirmPasswordModal').modal('show');
    });

    // Handle form submission after confirming password change
    document.getElementById('confirmPasswordBtnModal').addEventListener('click', function() {
        // Submit the form when "Save changes" is clicked in the modal
        document.getElementById('passwordChangeForm').submit();
    });
</script>

</body>
</html>
