<?php
session_start();


include('connect.php');

// Check if the user is not logged in
if (!isset($_SESSION['user'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit(); // Ensure that script execution stops here
}

// Retrieve the first name from the user information
$firstName = $_SESSION['user']['firstnme']; // Assuming the first name is stored in the 'first_name' field

// Set the page title
$pageTitle = "Welcome, $firstName";

if(isset($_POST['logout'])){
  // Destroy the session
  session_unset();
  session_destroy();
  
  // Redirect the user to the login page
  header("Location: index.php");
  exit(); // Ensure that script execution stops here
}

// Retrieve the customer_id of the logged-in user
$customer_id = $_SESSION['user']['customer_id'];

// Check if the form is submitted
if(isset($_POST['submit'])){
    // Retrieve form data
    $coffee_flavor = !empty($_POST['coffee_flavor']) ? $_POST['coffee_flavor'] : null;
    $coffee_quantity = !empty($_POST['coffee_quantity']) ? $_POST['coffee_quantity'] : null;
    $dessert_flavor = !empty($_POST['dessert_flavor']) ? $_POST['dessert_flavor'] : null;
    $dessert_quantity = !empty($_POST['dessert_quantity']) ? $_POST['dessert_quantity'] : null;
    
    // Prepare SQL statement to insert data into the "orders" table
    $sql1 = "INSERT INTO orders (date_of_transac, customer_id) 
            VALUES (CURRENT_DATE, :customer_id)";
    $stmt1 = $conn->prepare($sql1);
    
    $stmt1->bindParam(':customer_id', $customer_id);
    // Bind parameters and execute the statement
   
    
    
    
    $sql2 = "INSERT INTO order_items (coffee_id, coffee_quantity, dessert_id, dessert_quantity) 
    VALUES ( (SELECT coffee_id FROM coffee WHERE flavor = :coffee_flavor), :coffee_quantity,  (SELECT dessert FROM coffee WHERE flavor = :dessert_flavor), :dessert_quantity)";
    $stmt2 = $conn->prepare($sql2);


    $stmt2->bindParam(':coffee_quantity', $coffee_quantity);
    $stmt2->bindParam(':dessert_quantity', $dessert_quantity);
    try {
        $stmt1->execute();
        $stmt2->execute();
        $successMessage = "Order submitted successfully!";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}

// Retrieve orders associated with the logged-in user
$sql = "SELECT * FROM orders WHERE customer_id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$userOrders = $stmt->fetchAll();

// Close the database connection
$conn = null;
?>
<!DOCTYPE html>
<html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee.com</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <script>
    // Function to enable or disable the quantity input based on flavor selection
    function toggleQuantityInput() {
      var coffeeFlavor = document.getElementById('coffee_flavor').value;
      var dessertFlavor = document.getElementById('dessert_flavor').value;
      var coffeeQuantityInput = document.getElementById('coffee_quantity');
      var dessertQuantityInput = document.getElementById('dessert_quantity');

      // Check if coffee flavor is selected
      if (coffeeFlavor !== '') {
        coffeeQuantityInput.removeAttribute('disabled');
      } else {
        coffeeQuantityInput.setAttribute('disabled', 'disabled');
      }

      // Check if dessert flavor is selected
      if (dessertFlavor !== '') {
        dessertQuantityInput.removeAttribute('disabled');
      } else {
        dessertQuantityInput.setAttribute('disabled', 'disabled');
      }
    }
  </script>
  <style>
    .navbar {
      background-color: rgb(231, 175, 122);
      height: 80px;
      margin: 20px;
      border-radius: 16px;
     

    }
    body {
      overflow-x: hidden;
    }

    .navbar-brand {
      font-family: 'cursive', sans-serif;
    }
       /* CSS for styling the Welcome message */
    .navbar-text {
        margin-right: 20px; /* Adjust margin as needed */
        font-size: 16px; /* Adjust font size as needed */
        color: brown; /* Text color */
    }

    .welcome-message {
        font-weight: bold; /* Make the message bold */
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

    .hero-section {
      background: url(gwapo.jpg) no-repeat center;
      background-size: cover;
      width: 100%;
      height: 100vh;
    }

    .hero-section::before {
      background-color: rgba(0, 0, 0, 0.6);
      content: "";
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }

    .hero-section {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .menu-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .menu-text h1 {
      font-size: 70px;
      color: white;
      color: transparent;
      -webkit-text-stroke: 1px #fff;
      background: url(stripes1.png);
      -webkit-background-clip: text;
      background-position: 0 0;
      animation: back 20s linear infinite;
    }

    @keyframes back {
      100% {
        background-position: 2000px 0;
      }
    }
    .about-text{
      background: url(kape.png);
    }
    
  /* order */
  .bg {
    background-image: url(https://cdn.freecodecamp.org/curriculum/css-cafe/beans.jpg);
    font-family: sans-serif;
    padding: 20px;
  }
  /* coffee */
  .bg2{
    background-image: url(https://cdn.freecodecamp.org/curriculum/css-cafe/beans.jpg);
    font-family: bold;
    color:white;
    padding: 20px;
  }
  /* menu */
  .bg3{
    background-image: url(menu.jpg);
    font-family: bold;
    color:white;
    padding: 20px;
  }
   /* dessert */
   .bg4{
    background-image: url(dessert.jpg);
    font-family: bold;
    color:black;
    padding: 20px;
  }

  .bg5{
    background-image: url(https://cdn.freecodecamp.org/curriculum/css-cafe/beans.jpg);
    font-family: bold;
    color:black;
    padding: 20px;
  }
  
  
  h1 {
    font-size: 40px;
    margin-top: 0;
    margin-bottom: 15px;
  }
  
  h2 {
    font-size: 30px;
  }
  
  .established {
    font-style: italic;
  }
  
  h1, h2, p {
    text-align: center;
  }
  
  .menu {
    width: 80%;
    background-color: burlywood;
    
    margin-right: auto;
    padding: 20px;
    max-width: 500px;
    border-radius: 30px;
  }
  
  img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-top:-10px;
  }
  
  hr {
    height: 2px;
    background-color: brown;
    border-color: brown;
  }
  
  .bottom-line {
    margin-top: 25px;
  }
  
  .menu h1, h2 {
    font-family: Impact, serif;
  }
  
  .item p {
    display: inline-block;
    margin-top: 5px;
    margin-bottom: 5px;
    font-size: 18px;
  }
  
  .flavor, .dessert {
    text-align: left;
    width: 75%;
  }
  
  .price {
    text-align: right;
    width: 25%;
  }
  
  /* FOOTER */
  
  footer {
    font-size: 14px;
  }
  
  .address {
    margin-bottom: 5px;
  }
  
  a {
    color: black;
  }
  
  a:visited {
    color: black;
  }
  
  a:hover {
    color: brown;
  }
  
  a:active {
    color: brown;
  }
  .container {
            max-width: 400px;
            margin-top: 50px;
            margin-right: 10px;
            margin-left: 10px;
            padding: 50px;
            box-shadow: rgba(100,100,111,0.4)  0px 5px 27px 0px;
            background-color: rgba(255, 255, 255, 0.8); /* Add a semi-transparent white background */
        }
        .form-group {
            margin-bottom: 30px;
        }

        .container {
    background-color: #D2B48D; /* Coffee brown color */
    padding: 20px;
    border-radius: 30px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 20px;
}

label {
    color: #4B3A2A; /* Dark brown color for labels */
}

.btn-primary {
    background-color: #8B4513; /* Coffee brown color for submit button */
    border-color: #8B4513; /* Coffee brown color for submit button border */
}

.btn-primary:hover {
    background-color: #A0522D; /* Darker shade of coffee brown on hover */
    border-color: #A0522D; /* Darker shade of coffee brown on hover */
}
  </style>
</head>

<body>
  <!-- Navigation Section -->
  
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand me-auto" href="#">
        <img src="logoss.png" alt="Coffee Shop Logo" height="60" class="d-inline-block align-text-top ">
        
      </a>
      <div class="navbar-text">
            <span class="welcome-message">Welcome, <?php echo $firstName; ?></span>
        </div>
     
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Welcome!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link mx-lg-2" aria-current="page" href="#pills-home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-lg-2" aria-current="page" href="#pills-menu">Menu</a>
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
  <!-- Home Section -->
  
  <div class="tab-pane" id="pills-home">
    <section class="hero-section" id="hero-section">
      <div class="menu-section"></div>
      <div class="menu-text">
        <h1 class="fw-bold">The Coffee Shop</h1>
        <h4 style="display: inline; margin-left: 10px; color: #EDEDED; font-weight: 300;">The coffee that suits your taste</h4>



      </div>
    </section>

    <section class="your-home-page-section">
      <!-- Insert the accordion component here -->
      <div class="accordion" id="accordionPanelsStayOpenExample">
        <!-- Accordion items go here -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false"
              aria-controls="panelsStayOpen-collapseOne">
              Can You Drink Coffee While You're Pregnant?
            </button>
          </h2>
          <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
            <div class="accordion-body">
              <!-- Content for the first accordion item goes here -->
              <strong>Can pregnant women drink coffee?</strong> You don't have to completely kick your
              caffeine habit once you're expecting a baby. It's true that in the past, pregnant women were
              advised to avoid coffee and other forms of caffeine entirely, but experts now believe that low
              to moderate amounts are okay, as long as you take a few precautions.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
              aria-controls="panelsStayOpen-collapseTwo">
              Unique Benefits of Coffee
            </button>
          </h2>
          <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
            <div class="accordion-body">
              <strong>Boosts energy levels</strong> This is because caffeine blocks the receptors of a
              neurotransmitter called adenosine, and this increases levels of other neurotransmitters in your brain
              that regulate your energy levels, including dopamine
              <strong>Could support brain health</strong> According to one review of 13 studies, people who regularly
              consumed caffeine had a significantly lower risk of developing Parkinson's disease. What's more,
              caffeine consumption also slowed the progression of Parkinson’s disease over time.
              <strong>May promote weight management</strong> One study found that people who drank one to two cups of
              coffee per day were 17% more likely to meet recommended physical activity levels, compared with those
              who drank less than one cup per day
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
              aria-controls="panelsStayOpen-collapseThree">
              Daily Quotes
            </button>
          </h2>
          <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
            <div class="accordion-body">
              <strong >Two things are infinite: the universe and human stupidity; and I'm not sure about the universe.”
                ― Albert Einstein</strong>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Nav/Menu -->
    
     <!--Dessert-->
     
     <h1 class="bg3" id="pills-menu">Menu</h1>
     <div class="row">
  <div class="col-sm-6 mb-3 mb-sm-0">
    <div class="card">
      <div class="card-body">
        <!--end nest-->
      
    <h1 class="bg5" >Coffee</h1> 
   <!-- Coffee Flavors Carousel -->
<div id="coffeeCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="card text-center">
        <div class="card-body">
        
          <img src="cafe3.png" class="d-block w-100" alt="..." style="max-width:500px;">
          <p class="fst-italic">   French vanilla is by far one of the most popular flavors for coffee.

Vanilla is a subtle flavor with a strong aroma that goes very well with almost everything. French
vanilla, specifically, puts out a deeper and more caramelized taste. The qualities of vanilla are the
perfect complement for a strong, rich, and robust flavored coffee. Coffee connoisseurs recommend using
light or medium roast coffee beans for a much more pleasant result.

You can add French Vanilla to any coffee, such as lattes, cappuccinos, or just your traditional black
coffee for a little more flavor. French vanilla enhances the coffee with a pleasant aromatic touch
with a warm flavor. It's subtle, comforting, and fragrant.</p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="card text-center">
        <div class="card-body">
        
          <img src="cafe4.png" class="d-block w-100" alt="..." style="max-width:500px;">
          <p class="fst-italic">  Caramel is another favorite among coffee drinkers.

Adding caramel to coffee gives the drink a buttery sweetness. This flavor has a strong, smooth, and
robust profile.

If you're going to add caramel flavoring, it's not a bad idea to use light-roast coffee beans. The
toasted hints of the beans are ideal for a homemade caramel macchiato.
The sweet, roasted flavor of caramel can be the perfect complement to your brew. And, of course, it
                can be an alternative to sugar when looking to sweeten a coffee drink.</p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="card text-center">
        <div class="card-body">
          
          <img src="mocha7.png" class="d-block w-100" alt="..." style="max-width:500px;">
          <p class="fst-italic">  Mocha simply refers to chocolate and is probably the most popular coffee flavor out there.

Despite being chocolate-flavored, mocha coffee isn’t usually sweet. Often, you’ll find it’s made with
semi-sweet chocolate flavors. As a result, the bitter hints of coffee blend well with the creamy,
semi-sweet taste of chocolate. But, the coffee’s not overshadowed by them.
</p>
        </div>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#coffeeCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#coffeeCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

      <!--end nest-->
        <a href="#here" class="btn btn-primary">Order</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">

    <h1 class="bg4" >Dessert</h1> 
        <!--nest-->
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="donut.jpg" class="d-block w-100" alt="..." style="max-width:500px;">
      <p class="fst-italic">A donut, also spelled doughnut, is a type of fried dough confectionery. It's popular worldwide and comes in various shapes and sizes, typically with a hole in the center. Donuts can be either yeast-based or cake-based, and they're often sweetened and flavored with ingredients like sugar, cinnamon, chocolate, or fruit flavors. They can be glazed, frosted, or dusted with powdered sugar, and sometimes filled with cream, jam, or other sweet fillings. Donuts are commonly enjoyed as a snack or dessert, and they're often paired with coffee or other beverages.</p>
    </div>
    <div class="carousel-item">
      <img src="cinnamon.jpg" class="d-block w-100" alt="..." style="max-width:500px;">
      <p class="fst-italic">
Cinnamon, an esteemed spice renowned for its sweet, slightly spicy flavor profile, is derived from the inner bark of carefully selected tree species. Its rich aroma, redolent of warmth and sweetness, infuses kitchens across the globe, enhancing the taste of a wide array of culinary creations, from indulgent donuts to savory dishes. Beyond its culinary prowess, cinnamon holds a revered status for its potential health benefits, including its reputed anti-inflammatory and antioxidant properties, which have captivated the interest of health enthusiasts and practitioners alike for centuries. From ancient medicinal practices to modern culinary delights, cinnamon continues to captivate palates and minds.</p>
    </div>
    <div class="carousel-item">
      <img src="cherry.jpg" class="d-block w-100" alt="..." style="max-width:570px;">
      <p class="fst-italic">Cherry pie is a pie baked with a cherry filling. Traditionally, cherry pie is made with sour cherries rather than sweet cherries. Morello cherries are one of the most common kinds of cherry used, but other varieties such as the black cherry may also be used. Cherry pie is associated with Europe and North America and is mentioned in the lyrics of American folk songs such as "Billy Boy".</p>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>  <!--end nest-->
        <a href="#here" class="btn btn-primary">Order</a>
      </div>
    </div>
  </div>
</div>
      
    
    <!--Order-->
   <!-- Order Section -->
<div id="here"class="bg">
    <div class="row">
        <!-- Menu Column -->
        <div class="col-md-6">
            <div class="menu">
                <main>
                    <h1>The Coffee Shop</h1>
                    <p class="established">Butuan Branch</p>
                    <hr>
                    <section>
                        <h2>Coffee</h2>
                        <img src="https://cdn.freecodecamp.org/curriculum/css-cafe/coffee.jpg" alt="coffee icon"/>
                        <article class="item">
                            <p class="flavor">French Vanilla</p><p class="price">250.00</p>
                        </article>
                        <article class="item">
                            <p class="flavor">Caramel</p><p class="price">240.00</p>
                        </article>
                        <article class="item">
                            <p class="flavor">Mocha</p><p class="price">230.00</p>
                        </article>
                    </section>
                    <section>
                        <h2>Desserts</h2>
                        <img src="https://cdn.freecodecamp.org/curriculum/css-cafe/pie.jpg" alt="pie icon"/>
                        <article class="item">
                            <p class="dessert">Donut</p><p class="price">60.00</p>
                        </article>
                        <article class="item">
                            <p class="dessert">Cherry Pie</p><p class="price">60.00</p>
                        </article>
                        <article class="item">
                            <p class="dessert">Cinnamon Roll</p><p class="price">60.00</p>
                        </article>
                    </section>
                </main>
                <hr class="bottom-line">
                <footer>
                    <p>
                        <a href="https://www.merriam-webster.com/dictionary/partner" target="_blank">Visit our partners</a>
                    </p>
                    <p class="address">The Coffee Shop</p>
                </footer>
            </div>
        </div>
        <div class="col-md-6">
     <!-- Order Section -->
  <div class="container">
    <form action="" method="POST">
      <!-- Coffee flavor selection -->
      <div class="form-group">
        <label for="coffee_flavor">Coffee Flavor</label>
        <select name="coffee_flavor" id="coffee_flavor" class="form-control" onchange="toggleQuantityInput()">
          <option value="" selected disabled>Choose Coffee Flavor</option>
          <option value="Mocha">Mocha</option>
          <option value="French Vanilla">French Vanilla</option>
          <option value="Caramel">Caramel</option>
        </select>
      </div>
      <!-- Coffee quantity input -->
      <div class="form-group">
        <label for="coffee_quantity">Coffee Quantity</label>
        <input type="number" id="coffee_quantity" class="form-control" name="coffee_quantity" placeholder="0" disabled required>
      </div>
      <hr style="border: 5px solid #8B4513"> <!-- Coffee brown color -->
      <!-- Dessert flavor selection -->
      <div class="form-group">
        <label for="dessert_flavor">Dessert</label>
        <select name="dessert_flavor" id="dessert_flavor" class="form-control" onchange="toggleQuantityInput()">
          <option value="" selected disabled>Choose Dessert</option>
          <option value="Donut">Donut</option>
          <option value="Cherry Pie">Cherry Pie</option>
          <option value="Cinnamon roll">Cinnamon roll</option>
        </select>
      </div>
      <!-- Dessert quantity input -->
      <div class="form-group">
        <label for="dessert_quantity">Dessert Quantity</label>
        <input type="number" id="dessert_quantity" class="form-control" name="dessert_quantity" placeholder="0" disabled required>
      </div>
      <!-- Button to submit the form -->
      <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </form>
  </div>


                <?php if (!empty($successMessage)) : ?>
                    <div class="alert alert-success mt-3" role="alert">
                        <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



  <!-- Bootstrap JS and dependencies -->
  <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>