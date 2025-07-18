<?php
include '../config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $stmt = "SELECT user_id FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $stmt);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("Please use a different email address!" );</script>';
            header("Refresh:0.11; url=./signup.php");
        } else {
            $fullname = $_POST['fullname'];
            $username = $_POST['username'];
            $phonenb = $_POST['phonenb'];
            $gender = $_POST['genderselect'];
            $dateofB = $_POST['dob'];
            $address = $_POST['address'];
            $password = $_POST['password'];
            $customer_query = "INSERT INTO users (email, username, phone, password, role, status) VALUES (?, ?, ?, ?, 'patient', 'active')";
            $stmt = $con->prepare($customer_query);
            $stmt->bind_param("ssis", $email, $username, $phonenb, $password);
            //$stmt->execute();
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;

                // Insert into patients table
                $patients_query = "INSERT INTO patients (user_id, fullname, gender, dob, address) VALUES (?, ?, ?, ?, ?)";
                $stmt = $con->prepare($patients_query);
                $stmt->bind_param("issss", $user_id, $fullname, $gender, $dateofB, $address);
                if ($stmt->execute()) {
                    echo '<script>alert("Patient account has been created!" );</script>';
                    header("Refresh:0.11; url=./login.php");
                } else {
                    echo "Error creating your account! Try again later.";
                }
            }else {
                echo "Error creating your account! Try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Register - Health Medical Center</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Mahdi Saleh">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/owl.carousel.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon"> <!--ICON-->
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../css/stylee.css">
    <link rel="stylesheet" href="./style.css">

</head>

<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">


    <!-- HEADER -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-5">
                    <p>Welcome to a Professional Health Care</p>
                </div>
                <div class="col-md-8 col-sm-7 text-align-right">
                    <span class="phone-icon"><i class="fa fa-phone"></i>+961 3 123 456</span>
                    <span class="date-icon"><i class="fa fa-calendar-plus-o"></i> 6:00 AM - 10:00 PM (Mon-Fri)</span>
                    <span class="email-icon"><i class="fa fa-envelope-o"></i> <a href="#">info@company.com</a></span>
                </div>
            </div>
        </div>
    </header>


    <!-- MENU -->
    <section class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                </button>
                <!-- lOGO TEXT HERE -->
                <a href="../index.php" class="navbar-brand"><i class="fa fa-h-square"></i>ealth Center</a>
            </div>
            <!-- MENU LINKS -->
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../index.php" class="smoothScroll">Home</a></li>
                    <li><a href="#" class="smoothScroll">About Us</a></li>
                    <li><a href="#" class="smoothScroll">Doctors</a></li>
                    <li><a href="#" class="smoothScroll">News</a></li>
                    <li><a href="#" class="smoothScroll">Contact</a></li>
                    <li class="appointment-btn"><a href="../appointments/appointment.php">Make an appointment</a></li>
                </ul>
            </div>
        </div>
    </section>


    <!-- HOME -->
    <div class="logincontainer">
        <div class="login-form">
            <div class="login-info">
                <h2>Sign up</h2>
                <p>
                    Already have account?
                    <a href="./login.php">Login</a>
                </p>
            </div>
            <form action="signup.php" method="post">
                <label for="email">Email address:</label>
                <input type="email" name="email" id="email" placeholder="Enter email address" required>

                <label for="fullname">Full Name:</label>
                <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" required>

                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Generate an username" required>

                <label for="phonenb">Phone number:</label>
                <input type="number" name="phonenb" id="phonenb" placeholder="Enter your phone number" required>

                <label>Select Gender:</label>
                <select name="genderselect" id="genderselect">
                    <option>Male</option>
                    <option>Female</option>
                </select>

                <label>Date of Birth:</label>
                <input type="date" name="dob" id="dob" required>

                <label for="address">Address:</label>
                <input type="text" name="address" id="address" placeholder="Ex: Beirut, Hazmieh" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>

                <label for="password">Re-enter Password:</label>
                <input type="password" name="repeatpassword" id="repeatpassword" placeholder="Re-enter password" required>

                <p>
                    By creating an account you agree to our
                    <a href="#">Terms & Privacy</a>.
                </p>
                <div class="buttons">
                    <a href="../index.php"><button type="button" class="cancelbtn">Cancel</button></a>
                    <button type="submit" class="signupbtn" name="submit">Register</button>
                </div>
            </form>
        </div>
    </div>


    <!-- FOOTER -->
    <footer data-stellar-background-ratio="5">
        <div class="container">
            <div class="row">

                <div class="col-md-12 col-sm-12 border-top">
                    <div class="col-md-4 col-sm-6">
                        <div class="copyright-text">
                            <p>Copyright &copy; 2025 Health Center

                                | Developer: <a rel="nofollow" href="http://mahdisaleh.ct.ws" target="_parent">Mahdi Saleh</a></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="footer-link">
                            <a href="#">Laboratory Tests</a>
                            <a href="#">Departments</a>
                            <a href="#">Insurance Policy</a>
                            <a href="#">Careers</a>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 text-align-center">
                        <div class="angle-up-btn">
                            <a href="#top" class="smoothScroll wow fadeInUp" data-wow-delay="1.2s"><i class="fa fa-angle-up"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.sticky.js"></script>
    <script src="../js/jquery.stellar.min.js"></script>
    <script src="../js/wow.min.js"></script>
    <script src="../js/smoothscroll.js"></script>
    <script src="../js/owl.carousel.min.js"></script>
    <script src="../js/custom.js"></script>

</body>
<script>
    $(document).ready(function() {
        $('#repeatpassword').on('input', function() {
            const password = $('#password').val();
            const repeatPassword = $(this).val();

            if (repeatPassword && password !== repeatPassword) {
                $('#repeatpassword').css('border', '3px solid red');
            } else {
                $('#repeatpassword').css('border', '1px solid #ddd');
            }
        });
    });
</script>

</html>