<?php
include '../config.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_idd = $_SESSION['user_id'];
    $stmt = "SELECT * FROM users WHERE user_id = '$user_idd' AND status = 'active'";
    $result = mysqli_query($con, $stmt);
    if ($result) {
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_username'] = $user['username'];
            if ($user['role'] === 'admin') {
                //echo '<script>alert("Welcome ' . $user['username'] . '! You entered using an Admin Account!" );</script>';
                header("Refresh:0.11; url=../admin/admindashboard.php");
            } else if ($user['role'] === 'patient') {
                //echo '<script>alert("Welcome ' . $user['username'] . '")</script>';
                header("Refresh:0.11; url=../patient/patientdashboard.php");
            } else if ($user['role'] === 'labstaff') {
                //echo '<script>alert("Welcome ' . $user['username'] . '")</script>';
                header("Refresh:0.11; url=../laboratoryset/labstaffdashboard.php");
            } else if ($user['role'] === 'doctor') {
                //echo '<script>alert("Welcome ' . $user['username'] . '")</script>';
                header("Refresh:0.11; url=../doctors/doctordashboard.php");
            }
        }
    }
    // User is not logged in, redirect to login page
    //header("Location: ../patient/patientdashboard.php");
    exit();
}
$popupMessage = '';
$popupType = ''; // success or error
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $un = $_POST['email'];
    $password = $_POST['psw'];
    $hashed_password = md5($password);
    $stmt = "SELECT * FROM users WHERE (email = '$un' OR username = '$un')";
    $result = mysqli_query($con, $stmt);
    if ($result) {
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_password'] = $user['password'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_role'] = $user['role'];
            if ($user['status'] !== 'active') {
                $popupMessage = "Your account has been deactivated!";
                $popupType = 'error';
                session_unset();
                session_destroy();
            } else {
                if ($password !== $user['password']) {
                    //AND password = '$password'
                    $popupMessage = "Incorrect password!";
                    $popupType = 'error';
                    session_unset();
                    session_destroy();
                } else if ($user) {
                    $popupMessage = "Welcome, " . $user['username'] . "! You will be redirected shortly.";
                    $popupType = 'success';
                    $usernamee = $user['username'];
                    $role = $user['role'];
                    $redirectUrl = '';
                    switch ($role) {
                        case 'admin':
                            $redirectUrl = '../admin/admindashboard.php';
                            break;
                        case 'patient':
                            $redirectUrl = '../patient/patientdashboard.php';
                            break;
                        case 'labstaff':
                            $redirectUrl = '../laboratoryset/labstaffdashboard.php';
                            break;
                        case 'doctor':
                            $redirectUrl = '../doctors/doctordashboard.php';
                            break;
                    }
                }
            }
        } else {
            $popupMessage = "No account with this email!";
            $popupType = 'error';
            session_unset();
            session_destroy();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Login - Health Medical Center</title>

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

    <?php if (!empty($popupMessage)): ?>
        <div id="popup-overlay">
            <div id="popup-box" class="<?php echo $popupType === 'error' ? 'error' : 'success'; ?>">
                <h2><?php echo $popupType === 'error' ? 'Login Failed' : 'Login Successful'; ?></h2>
                <p><?php echo htmlspecialchars($popupMessage); ?></p>
            </div>
        </div>
    <?php endif; ?>
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
                    <li><a href="../index.php#about" class="smoothScroll">About Us</a></li>
                    <li><a href="../index.php#team" class="smoothScroll">Doctors</a></li>
                    <li><a href="../index.php#news" class="smoothScroll">News</a></li>
                    <li><a href="../index.php#google-map" class="smoothScroll">Contact</a></li>
                    <li class="appointment-btn"><a href="../appointments/appointment.php">Make an appointment</a></li>
                </ul>
            </div>
        </div>
    </section>


    <!-- HOME -->
    <div class="logincontainer">
        <div class="login-form">
            <div class="login-info">
                <h2>Login</h2>
                <p>
                    Want to create a new account?
                    <a href="signup.php">Sign Up</a>
                </p>
            </div>
            <form action="login.php" method="post">
                <label for="email">Email address or Username:</label>
                <input type="text" name="email" class="email" id="email" placeholder="Enter email or username" required>
                <label for="password">Password:</label>
                <input type="password" name="psw" class="password" id="password" placeholder="Enter your password" required>
                <p><a href="forget.php">Forget Password?</a></p> <br>

                <div class="buttons">
                    <a href="../index.php"><button type="button" class="cancelbtn">Cancel</button></a>
                    <button type="submit" class="signupbtn" name="submit">Login</button>
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
    <script>
        // Auto-close popup after 3 seconds
        setTimeout(function() {
            const popup = document.getElementById("popup-overlay");
            if (popup) {
                popup.style.display = "none";
            }

            // Redirect on successful login
            <?php if ($popupType === 'success' && !empty($redirectUrl)): ?>
                window.location.href = "<?= $redirectUrl ?>";
            <?php endif; ?>
        }, 3000);
    </script>
</body>
<style>
    #popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    /* Popup box */
    #popup-box {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        font-family: Arial, sans-serif;
    }

    #popup-box h2 {
        margin-top: 0;
        color: #28a745;
    }

    #popup-box p {
        margin: 10px 0;
    }
</style>

</html>