<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee.com</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    /* Added CSS for fullscreen background image */
    body {
      position: relative;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .background-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('kape.png') no-repeat center center;
      background-size: cover;
      z-index: -1;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6); /* Adjust the opacity as needed */
      z-index: -1; /* Set a lower z-index */
    }

    .navbar {
      background-color: rgba(231, 175, 122, 0.9);
      height: 80px;
      margin: 20px;
      border-radius: 16px;
      padding: 0.5rem;
      position: relative;
      z-index: 1;
    }

    .navbar-brand {
      font-family: 'cursive', sans-serif;
    }

    .login-button {
      background-color: brown;
      color: white;
      font-size: 14px;
      padding: 8px 20px;
      border-radius: 50px;
      text-decoration: none;
      transition: 0.3s background-color;
    }

    .login-button:hover {
      background-color: brown;
    }

    .navbar-toggler {
      border: none;
      font-size: 1.25rem;
    }

    .navbar-toggler:focus,
    .btn-close:focus {
      box-shadow: none;
      outline: none;
    }

    .nav-link {
      color: #000000;
      outline: none;
      position: relative;
    }

    .nav-link:hover,
    .nav-link.active {
      color: black;
    }

    .nav-link::before {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0%;
      transform: translateX(-50);
      width: 100%;
      height: 2px;
      background-color: brown;
      visibility: hidden;
      transition: 0.3s ease-in-out;
    }

    .nav-link:hover::before,
    .nav-link.active::before {
      width: 100%;
      visibility: visible;
    }

    .about-text {
      padding: 20px; /* Added padding for better spacing */
    }
  </style>
</head>

<body>
  <!-- Background Image -->
  <div class="background-image"></div>

  <!-- Navigation Section -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand me-auto" href="#">
        <img src="logoss.png" alt="Coffee Shop Logo" height="60" class="d-inline-block align-text-top ">
      </a>
      The Coffee Shop
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Welcome!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link mx-lg-2" aria-current="page" href="scratch.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-lg-2" aria-current="page" href="scratch.php">Menu</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-lg-2" aria-current="page" href="dev.php">Developers</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-lg-2" aria-current="page" href="login.php">View Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-lg-2" aria-current="page" href="profile.php">My Profile</a>
            </li>
          </ul>
        </div>
      </div>
      <form action="" method="post">
        <button type="submit" class="login-button" name="logout">Log out</button>
      </form>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
        aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <!-- Content Section -->
  <div class="container">
    <div class="overlay"></div>
    <div class="tab-pane fade show " id="pills-developers" role="tabpanel" aria-labelledby="pills-about-tab" tabindex="0">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="about-text">
          <p class="text-center fst-italic fs-1 text-white" style="font-size: 50px;">ABOUT US</p>
          <p class="text-center text-white" style="font-size: 20px;">We believe that the Philippines is rich in culture and artistry. We are proud of our roots.</p>
          <p class="text-center text-white" style="font-size: 20px;">The coffee and service we offer are a testament to our deep love of our country. Established by Steve Benitez on June 28, 1996, we have reinvented our platform for startup businesses and social enterprises to put the global limelight on Philippine Coffee and Filipino tradition.</p>
          <p class="text-center text-white" style="font-size: 20px;">Our Bayanihan Mentality drives us to give social enterprises a voice, leaving a legacy of positive change. Bo's Coffee works with young brands from all over the Philippines such as Theo and Philo Chocolates, Bayani Brew, Anthill Fabric Gallery, Tsaa Laya, Bote Cental, AGREA Coco Sugar and Hope in a Bottle.</p>
          <p class="text-center text-white" style="font-size: 20px;">We want everyone we meet to feel welcome in a place where they can work, laugh, or bond A place to call home.</p>
          <p class="text-center fst-italic fs-1 text-white" style="font-size: 50px;">Owners</p>
          <div class="container">
            <div class="row">
              <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                  <img src="ainor.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Ainor Jamal</h5>
                    <p class="card-text">Location: Purok -8 Baranggay 16 Ong Yiu, Butuan City, Agusan Del Norte, PHILIPPINES</p>
                    <a href="https://www.facebook.com/ainor.jamal.9" class="btn btn-primary">Fb Account</a>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                  <img src="adrian1.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Adrian Deligero</h5>
                    <p class="card-text">Location: Purok -2 Baranggay La Union, Cabadbaran City, Agusan Del Norte, PHILIPPINES</p>
                    <a href=" https://www.facebook.com/profile.php?id=100009488170986" class="btn btn-primary">Fb Account</a>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                  <img src="brian.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Brian Inguito</h5>
                    <p class="card-text">Location: Purok -8 Baranggay 16 Ong Yiu, Butuan City, Agusan Del Norte, PHILIPPINES</p>
                    <a href="https://www.facebook.com/brian.inguito.71" class="btn btn-primary">Fb Account</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
