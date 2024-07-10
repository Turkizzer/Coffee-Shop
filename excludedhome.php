<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden; /* Hide horizontal scrollbar */
        }
        .bg-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Maintain aspect ratio */
            z-index: -1;
        }


    
        .bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
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
        }
    </style>
</head>

<body>

<div class="bg"></div>
<div class="navbar-container">
    <div class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 text-white">About</a></li>
                </ul>

                <div class="text-end">
                    <a href="index.php" class="btn btn-outline-light me-2">Login</a>
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
</div>


<footer class="footer">
    <span class="mb-3 mb-md-0 text-muted">©TheCoffeeShop</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
