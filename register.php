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
    background-image: url('login.jpeg'); /* Set the background image */
    background-size: cover; /* Cover the entire background */
    background-repeat: no-repeat; /* Do not repeat the background image */
    background-attachment: fixed; /* Fix the background image so it doesn't scroll */
    position: relative; /* Set position to relative to contain the overlay */
}

.overlay {
    position: absolute; /* Position the overlay */
    top: 0;
    left: 0;
    width: 100%; /* Cover the entire viewport */
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3); /* Semi-transparent black overlay */
    z-index: -1;
}

        .container {
            max-width: 800px;
            margin: auto;
            padding: 50px;
            box-shadow: rgba(100,100,111,0.4)  0px 5px 27px 0px;
         background-color:rgba(153, 127, 99, 0.5); /* Add a semi-transparent white background */
        }
        .form-group {
            margin-bottom: 30px;
        }
        .login{
            margin:auto;
            color:white;
           
          
        }
</style>
<body>
<?php
include('connect.php');

$successMessage = ""; // Initialize a variable to hold the success message
$passwordsMatchError = ""; // Initialize a variable to hold the password match error message

if (isset($_POST["submit"])) {
    $firstnme = $_POST["firstnme"];
    $lastnme = $_POST["lasttnme"]; 
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeat_password = $_POST["repeat_password"];
    $region = $_POST["region_alt"];
    $province = $_POST["province_alt"];
    $city = $_POST["city_alt"];
    $barangay = $_POST["barangay"];
    $errors = array();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $repeat_password) {
        $passwordsMatchError = "Passwords don't match";
    }
   
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If there are no errors, insert data into the database
    if (empty($errors) && empty($passwordsMatchError)) {
        $sql = "INSERT INTO users (firstnme, lasttnme, email, password,region,province,city,barangay) VALUES (:firstnme, :lasttnme, :email, :password, :region,:province,:city,:barangay)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':firstnme', $firstnme);
        $stmt->bindParam(':lasttnme', $lastnme); 
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':region', $region);
        $stmt->bindParam(':province', $province);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':barangay', $barangay);
        $stmt->execute();

        $_SESSION['successMessage'] = "User registered successfully!"; // Store success message in session variable
        header("Location: " . $_SERVER['REQUEST_URI']); // Redirect to prevent duplicate registration
        exit();
    }
    
}
?>
 <div style="position: relative;">
    <img src="logoss.png" alt="Coffee Shop Logo" height="300px" style="position: fixed; top: 0; left: 2;z-index:-1; ">
    <div style="text-align: center; margin-bottom:10px;">
        <button type="button" class="btn btn-secondary" style="font-size: 30px; border-radius: 20px; background-color: rgba(153, 127, 99, 0.4); margin-left: 20px;font-family:bernard;">Register Here!</button>
    </div>
</div>



<div class="overlay"></div>
<div class="container ">
    <form method="POST">
        <div class="form-group">
            <input type="text" class="form-control" name="firstnme" placeholder="First Name" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="lasttnme" placeholder="Last Name" required>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <div id="passmessage" style="color: red;"></div> <!-- Password length/message -->
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Re-enter Password" required>
            <?php if ($passwordsMatchError) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $passwordsMatchError; ?>
                </div>
            <?php endif; ?>
            <div id="passwordMismatch" style="color: red; display: none;">Passwords don't match</div> <!-- New div for password mismatch message -->
        </div>
        
        <!-- Region Selection -->
        <div class="form-group">
            <label for="region"style="color:white;">Region:</label>
            <select name="region" id="region" class="form-control" onchange="fetch_provinces()">
                <option>Select Region</option>    
            <!-- Options will be populated dynamically -->
            </select>

            <select hidden="true" name="region_alt" id="region_alt" class="form-control">
            <option></option> <!-- Options will be populated dynamically -->
            </select>
        </div>

        <!-- Province Selection -->
        <div class="form-group">
            <label for="province"style="color:white;">Province:</label>
            <select name="province" id="province" class="form-control" onchange="fetch_cities()">
            <option>Select Province</option><!-- Options will be populated dynamically -->
            </select>

            <select hidden="true" name="province_alt" id="province_alt" class="form-control">
            <option></option> <!-- Options will be populated dynamically -->
            </select>
        </div>

        <!-- City Selection -->
        <div class="form-group">
            <label for="city"style="color:white;">City:</label>
            <select name="city" id="city" class="form-control" onchange="fetch_barangays()">
            <option>Select City</option> <!-- Options will be populated dynamically -->
            </select>

            <select hidden="true" name="city_alt" id="city_alt" class="form-control">
            <option></option> <!-- Options will be populated dynamically -->
            </select>
        </div>

        <!-- Barangay Selection -->
        <div class="form-group">
            <label for="barangay"style="color:white;">Barangay:</label>
            <select name="barangay" id="barangay" class="form-control">
            <option>Select Barangay</option>  <!-- Options will be populated dynamically -->
            </select>

        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="checkcon" required>
            <label class="form-check-label" for="checkcon"style="color:white; font-size:18px;">I agree to the   <a  data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
  Terms and Conditions
</a></label>
        </div>
        <div class="form-btn">
            <button type="submit" id="submitform" class="btn btn-dark" name="submit">Register</button>
             <!--diara-->
           
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Terms and Conditions</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div>
     <p>Data Collection: By creating an account on our website, you agree to provide accurate and current information, including your name, email address, and any other required details for registration.

Data Usage: We collect your personal information to create and manage your account, personalize your experience, process transactions, and provide customer support. Your data may also be used for marketing purposes, including sending promotional emails and offers, but you can opt out of these communications at any time.

Data Security: We take the security of your personal information seriously and employ industry-standard measures to protect it against unauthorized access, alteration, disclosure, or destruction.

Third-Party Access: We may share your data with trusted third-party service providers who assist us in operating our website, conducting business, or servicing you, as long as those parties agree to keep this information confidential.

Cookies and Tracking: We use cookies and similar tracking technologies to enhance your browsing experience, analyze website traffic, and personalize content. You can choose to disable cookies through your browser settings, but this may affect some features of the website.

Age Restrictions: Our website is intended for users who are at least 18 years old. By creating an account, you confirm that you are of legal age to enter into binding contracts.

Changes to Terms: We reserve the right to update or modify these terms and conditions at any time without prior notice. Your continued use of the website after any changes indicates your acceptance of the updated terms.

Data Deletion: You have the right to request the deletion of your account and associated personal data at any time. Please note that certain information may be retained for legal or regulatory purposes.

Governing Law: These terms and conditions are governed by and construed in accordance with the laws of [your jurisdiction], and any disputes relating to these terms shall be subject to the exclusive jurisdiction of the courts in [your jurisdiction].

Contact Information: If you have any questions or concerns about our data collection practices or these terms and conditions, please contact us at [your contact information].</p>
    </div>
   
  </div>
</div>
                <!--diara-->

                
            <button type="button" id="cancelButton" class="btn btn-secondary">Cancel</button> <!-- Added cancel button -->
            <p class="mt-3">Already have an account? <a href="index.php">Log in here</a></p> <!-- Added anchor tag for index link -->
        </div>
    </form>
</div>

<!-- modal -->
<?php if (isset($_SESSION['successMessage'])) : ?>
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <!--diara-->
                
                <!--diara-->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo $_SESSION['successMessage']; ?> <!-- Display success message here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['successMessage']); ?> <!-- Unset the success message to prevent duplication -->
<?php endif; ?>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<script>
    // Your existing JavaScript code

    // PSCG API: Regions
     fetch(`https://psgc.gitlab.io/api/regions/`)
    .then(response => response.json())
    .then(data => {
        
        const regionsSelect = document.getElementById('region');
        data.sort((a, b) => a.name.localeCompare(b.name));
        data.forEach(region => {
            const option = document.createElement('option');
            option.value = region.code;
            option.textContent = region.name;
            regionsSelect.appendChild(option);
        });
    });

    // PSCG API: Regions
    fetch(`https://psgc.gitlab.io/api/regions/`)
    .then(response => response.json())
    .then(data => {
        
        const regionsSelect = document.getElementById('region_alt');
        data.sort((a, b) => a.name.localeCompare(b.name));
        data.forEach(region => {
            const option = document.createElement('option');
            option.value = region.name;
            option.textContent = region.name;
            regionsSelect.appendChild(option);
        });
    });


    // PSGC API: Provinces
    async function fetch_provinces () {

        var r1 = document.getElementById('region');
        var r2 = document.getElementById('region_alt');
        r2.value = r1.options[r1.selectedIndex].text;

        const region = document.getElementById('region').value;
        const response = await fetch(`https://psgc.gitlab.io/api/regions/${region}/provinces/`);
        const data = await response.json();

        const provincesSelect = document.getElementById('province');
        provincesSelect.innerHTML = ''; // Clear previous options

        const buffer = document.createElement('option');
        provincesSelect.appendChild(buffer); // Add empty option as placeholder

        data.sort((a, b) => a.name.localeCompare(b.name));
        data.forEach(province => {
            const option = document.createElement('option');
            option.value = province.code;
            option.textContent = province.name;
            provincesSelect.appendChild(option);
        });
        
        fetch(`https://psgc.gitlab.io/api/regions/${region}/provinces/`)
       .then(response => response.json())
        .then(data => {
        
        const provinceSelect = document.getElementById('province_alt');
        data.sort((a, b) => a.name.localeCompare(b.name));
        data.forEach(province => {
            const option = document.createElement('option');
            option.value = province.name;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
        });
    });
    }

    
    // PSGC API: Cities
    async function fetch_cities () {

        var p1 = document.getElementById('province');
        var p2 = document.getElementById('province_alt');
        p2.value = p1.options[p1.selectedIndex].text;

        const province = document.getElementById('province').value;
        const response = await fetch(`https://psgc.gitlab.io/api/provinces/${province}/cities-municipalities/`);
        const data = await response.json();

        const citiesSelect = document.getElementById('city');
        citiesSelect.innerHTML = ''; // Clear previous options

        const buffer = document.createElement('option');
        citiesSelect.appendChild(buffer); // Add empty option as placeholder

        data.sort((a, b) => a.name.localeCompare(b.name));
        data.forEach(city => {
            const option = document.createElement('option');
            option.value = city.code;
            option.textContent = city.name;
            citiesSelect.appendChild(option);
        });

        fetch(`https://psgc.gitlab.io/api/provinces/${province}/cities-municipalities/`)
        .then(response => response.json())
        .then(data => {
        
        const citySelect = document.getElementById('city_alt');
        data.sort((a, b) => a.name.localeCompare(b.name));
        data.forEach(city => {
            const option = document.createElement('option');
            option.value = city.name;
            option.textContent = city.name;
            citySelect.appendChild(option);
        });
    });
    }

    // PSGC API: Barangays
    async function fetch_barangays () {
        var bar1 = document.getElementById('city');
        var bar2 = document.getElementById('city_alt');
        bar2.value = bar1.options[bar1.selectedIndex].text;

        const city = document.getElementById('city').value;
        const response = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${city}/barangays/`);
        const data = await response.json();

        const barangaysSelect = document.getElementById('barangay');
        barangaysSelect.innerHTML = ''; // Clear previous options

        const buffer = document.createElement('option');
        barangaysSelect.appendChild(buffer); // Add empty option as placeholder

        data.sort((a, b) => a.name.localeCompare(b.name));
        data.forEach(barangay => {
            const option = document.createElement('option');
            option.value = barangay.name;
            option.textContent = barangay.name;
            barangaysSelect.appendChild(option);
        }); 
    }

    // Additional JavaScript functions for province, city, and barangay selection

    // Check if passwords match
    document.getElementById('repeat_password').addEventListener('input', function() {
        var origPass = document.getElementById('password').value;
        var checkPass = this.value;
        var messageElement = document.getElementById('passmessage');
        var mismatchMessageElement = document.getElementById('passwordMismatch');

        if (origPass !== checkPass) {
            this.style.border = '1px solid red';
            messageElement.innerHTML = ''; // Clear the length message
            mismatchMessageElement.style.display = 'block'; // Show the mismatch message
        } else {
            this.style.border = '1px solid green';
            messageElement.innerHTML = ''; // Clear the length message
            mismatchMessageElement.style.display = 'none'; // Hide the mismatch message
        }
    });

    // Cancel button action
    document.getElementById('cancelButton').addEventListener('click', function() {
        window.history.back(); // Go back to the previous page
    });

    // Password length check
    document.getElementById('password').addEventListener('input', function() {
        var origPass = this.value;
        var checkPass = document.getElementById('repeat_password');
        var messageElement = document.getElementById('passmessage');

        if (origPass.length < 8 && origPass.length > 0) {
            this.style.border = '1px solid red';
            messageElement.innerHTML = 'Password must be 8 characters or more';
            checkPass.value = '';
            checkPass.disabled = true;
        } else {
            this.style.border = '1px solid green';
            messageElement.innerHTML = '';
            checkPass.disabled = false;
        }
    });

    // Password match check
    document.getElementById('checkpass').addEventListener('input', function() {
        var origPass = document.getElementById('password').value;
        var checkPass = this.value;
        var messageElement = document.getElementById('passmessage');

        if (origPass !== checkPass) {
            this.style.border = '1px solid red';
            messageElement.innerHTML = 'Password does not match';
            messageElement.style.color = 'red';
        } else {
            this.style.border = '1px solid green';
            messageElement.innerHTML = 'Password matches';
            messageElement.style.color = 'green';
        }
    });
</script>

</body>
</html>
