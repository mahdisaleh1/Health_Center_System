<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../users/login.php");
    exit();
} else {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $role = $admin['role'];
    if($role !== 'labstaff'){
        header("Location: ../users/login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>User Dashboard - Lab Staff</title>

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
                    <li class="lidashboard"><a href="./labstaffdashboard.php" class="smoothScroll">Home</a></li>
                    <li><a href="./appointments.php" class="smoothScroll">Appointments</a></li>
                    <li><a href="./testsdetails.php" class="smoothScroll">Test name</a></li>
                    <li><a href="./notifications.php" class="smoothScroll">Notifications</a></li>
                    <li><a href="./staffprofile.php" class="smoothScroll">Profile</a></li>
                    <li><a href="../users/logout.php" class="smoothScroll">Logout</a></li>
                </ul>
            </div>
        </div>
    </section>


    <!-- HOME -->
    <form action="" method="">
        <div class="dashcontainer">
            <aside class="sidebar">
                <nav>
                    <ul>
                        <li class="lidashboard"><a href="./labstaffdashboard.php">Dashboard</a></li>
                        <li><a href="./appointments.php">Appointments</a></li>
                        <li><a href="./testsdetails.php">Test name</a></li>
                        <li><a href="./notifications.php">Notifications</a></li>
                        <li><a href="./staffprofile.php">Profile</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <header>
                    <p>You're the best Lab Staff :)</p>
                </header>

                <section class="cards">
                    <div class="card">
                        <h3>Appointments</h3>
                        <p>Check and Modify</p>
                        <p>Make sure, new appointments can only created from doctors or patients.</p>
                        <a href="./appointments.php"><button type="button">View Details</button></a>
                    </div>
                    <div class="card">
                        <h3>Tests Available</h3>
                        <p>Check all tests details (names, prices and more)</p>
                        <p>Make sure, only admins can add new and modify tests!</p>
                        <a href="./testsdetails.php"><button type="button">View Details</button></a>
                    </div>
                    <div class="card">
                        <h3>Notifications</h3>
                        <p>Check notifications sent by admins and doctors.</p>
                        <p>Make sure, you can send nofitications for admins and doctors.</p>
                        <a href="./notifications.php"><button type="button">View Details</button></a>
                    </div>
                </section>
            </main>
        </div>
    </form>



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

<style>
    .lidashboard {
        text-decoration: underline;
    }
</style>

</html>