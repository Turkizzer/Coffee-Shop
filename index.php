<?php
session_start();

include('connect.php'); // Assuming this file contains your database connection

$errorMessage = ""; // Initialize error message variable

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user data from the database based on the provided email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables and redirect to scratch.php
            $_SESSION['user'] = $user;
            header("Location: scratch.php"); // Redirect to scratch.php
            exit();
        } else {
            // Password is incorrect
            $errorMessage = "Incorrect password";
        }
    } else {
        // User with the provided email does not exist
        $errorMessage = "Email address is not registered";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@200..700&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden; /* Hide horizontal scrollbar */
        }

        .navbar-container {
            position: relative;
            z-index: 1;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            color: white; /* Text color */
            text-align: center;
            padding: 20px 0;
        }

        .video-container {
            position: relative;
            padding-bottom: 56%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            z-index: 0; /* Ensure the video is below the content */
        }

        .video-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            z-index: 0; /* Ensure the overlay is behind the video */
        }

        .bg-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Maintain aspect ratio */
            z-index: -1; /* Ensure the video is below the content */
        }

        .bg {
            /* Add styles for the background here if needed */
        }

        section {
            position: relative; /* Ensure positioning context */
        }

        .bg-login {
            margin: 0;
            padding: 100px;
            background-image: url('kape.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
        }

        .bg-login::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .container-login {
            max-width: 500px;
            background: #D2B48C; /* Coffee color */
            border-radius: 40px;
            padding: 80px 90px;
            margin-left: auto; /* Align container to the right */
            margin-right: 30px; /* Reset any default margin */
            right: 0; /* Align container to the right */
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 30px 30px -20px;
            color: #4B3A2A; /* Dark brown text color */
            position: absolute; /* Position the container absolutely */
            top: 50%; /* Center vertically */
            transform: translateY(-50%); /* Adjust vertical alignment */
        }

        .heading {
            text-align: center;
            font-weight: 900;
            font-size: 30px;
            margin-bottom: 20px;
            color: #4B3A2A; /* Dark brown text color */
        }

        .form .input {
            width: 100%;
            background: white;
            border: none;
            padding: 15px 20px;
            border-radius: 20px;
            margin-top: 15px;
            box-shadow: #cff0ff 0px 10px 10px -5px;
            border-inline: 2px solid transparent;
        }

        .form .login-button {
            display: block;
            width: 100%;
            font-weight: bold;
            background: #8B4513; /* Brown button color */
            color: white;
            padding-block: 15px;
            margin-top: 20px;
            border-radius: 20px;
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 20px 10px -15px;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .form .login-button:hover {
            transform: scale(1.03);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 23px 10px -20px;
        }

        .form .login-button:active {
            transform: scale(0.95);
            box-shadow: rgba(133, 189, 215, 0.8784313725) 0px 15px 10px -10px;
        }

        .agreement {
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .agreement a {
            text-decoration: none;
            color: black; /* Blue color */
            font-size: 9px;
        }

        /* Style for the logo */
        .logo {
            position: absolute;
            top: 50%;
            left: 10%;
            transform: translateY(-50%);
            margin-left: 20px; /* Adjust the distance from the left side */
        }

        /* Apply font-family to the navbar text */
        .navbar-brand {
            font-family: 'Yanone Kaffeesatz', sans-serif;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 60px 70px;
            }

            .logo {
                margin-left: 30px; /* Adjust the distance from the left side */
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 50px 60px;
            }

            .logo {
                display: none; /* Hide the logo on smaller screens */
            }
        }

        @media (max-width: 576px) {
    .container-login {
        padding: 60px 30px; /* Adjust padding */
    }

    .coffee-shop-text h1 {
        font-size: 50px; /* Reduce font size for smaller screens */
    }
}

/* Adjustments for extra small screens */
@media (max-width: 375px) {
    .container-login {
        padding: 50px 20px; /* Further reduce padding for smaller screens */
    }

    .coffee-shop-text h1 {
        font-size: 40px; /* Further reduce font size for smaller screens */
    }
}


        .coffee-shop-text {
    position: absolute;
    top: 50%;
    left: 10%;
    transform: translateY(-50%);
    z-index: 2; /* Ensure the text is above the video */
}

.coffee-shop-text h1 {
    font-size: 90px;
    font-weight: bold;
    background: linear-gradient(to right, #8B4513, #D2B48C); 
    -webkit-background-clip: text; 
    -webkit-text-fill-color: transparent;/
}

    </style>
</head>
<body>
<div class="navbar-container">
    <div class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none navbar-brand">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
                    <img src="logoss.png" alt="Coffee Shop Logo" height="50" class="d-inline-block align-text-top">
                    <span class="navbar-text fs-4 fw-bold text-white">The Coffee Shop</span>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <!-- Add your navbar items here -->
                </ul>
                <div class="text-end">
                    <a href="#" class="btn btn-outline-light me-2" id="loginButton">Login</a> <!-- Add an id to the login button -->
                    <a href="register.php" class="btn btn-warning">Sign-up</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="video-container">
    <video autoplay loop muted class="bg-video">
        <source src="kape.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="coffee-shop-text">
        <h1>The Coffee Shop</h1>
        <p style="color: white; font-family: 'Roboto', sans-serif; font-size: 18px;">Welcome to The Coffee Shop, where every cup tells a story.</p>
</div>
        
    </div>
</div>


<section id="login"> <!-- Add an id attribute to the login section -->
    <div class="bg-login">
        <img src="logoss.png" alt="Logo" class="logo">
        <div class="container-login">
            <div class="heading">Log In</div>
            <form class="form" method="post">
                <input
                    placeholder="E-mail"
                    id="email"
                    name="email"
                    type="email"
                    class="input"
                    required=""
                />
                <?php if ($errorMessage === "Email address is not registered") : ?>
                    <div class="error-message"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <input
                    placeholder="Password"
                    id="password"
                    name="password"
                    type="password"
                    class="input"
                    required=""
                />
                <?php if ($errorMessage === "Incorrect password") : ?>
                    <div class="error-message"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <button value="Log In" type="submit" class="login-button" name="login">Log In</button>
            </form>
            <div style="margin-top: 20px; text-align: center;">
                <p>If not registered? <a href="register.php" style="color: black;">Register here</a></p>
            </div>
            <span class="agreement"><a href="#">Learn user licence agreement</a></span>
        </div>
    </div>
</section>

<footer class="footer">
    <span class="mb-3 mb-md-0 text-muted">©TheCoffeeShop</span>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find the "Login" button
    var loginButton = document.querySelector('#loginButton');
    
    // Add click event listener to the "Login" button
    loginButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default anchor behavior
        
        // Scroll to the login section smoothly
        document.querySelector('#login').scrollIntoView({ 
            behavior: 'smooth' 
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
