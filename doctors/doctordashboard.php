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
    if ($role !== 'doctor') {
        header("Location: ../users/login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>User Dashboard - Doctor</title>

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
                    <li class="lidashboard"><a href="./doctordashboard.php" class="smoothScroll">Home</a></li>
                    <li><a href="./patients.php" class="smoothScroll">Patients</a></li>
                    <li><a href="./appointments.php" class="smoothScroll">Appointments</a></li>
                    <li><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
                    <li><a href="./messages.php" class="smoothScroll">Messages</a></li>
                    <li><a href="./labtests.php" class="smoothScroll">Lab Test</a></li>
                    <li><a href="./prescriptions.php" class="smoothScroll">Prescriptions</a></li>
                    <li><a href="./doctorprofile.php" class="smoothScroll">Profile</a></li>
                    <li><a href="../users/logout.php" class="smoothScroll">Logout</a></li>
                </ul>
            </div>
        </div>
    </section>


    <!-- HOME -->
    <div class="dashcontainer">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li class="lidashboard"><a href="./doctordashboard.php">Dashboard</a></li>
                    <li><a href="./patients.php">Patients</a></li>
                    <li><a href="./appointments.php">Appointments</a></li>
                    <li><a href="./medicalrecords.php">Medical Records</a></li>
                    <li><a href="./messages.php">Messages</a></li>
                    <li><a href="./labtests.php">Lab Test</a></li>
                    <li><a href="./prescriptions.php">Prescriptions</a></li>
                    <li><a href="./doctorprofile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>You're the best Doctor :)</p>
            </header>

            <section class="cards">
                <div class="card">
                    <h3>Appointments</h3>
                    <p>Check and Modify</p>
                    <p>Make sure, new appointments can only created by you and patients.</p>
                    <a href="./appointments.php"><button type="button">View Details</button></a>
                </div>
                <div class="card">
                    <h3>Tests Available</h3>
                    <p>Check all tests details (names and prices)</p>
                    <p>Make sure, for any inquiries about lab tests, check with lab staff!</p>
                    <a href="./labtests.php"><button type="button">View Details</button></a>
                </div>
                <div class="card">
                    <h3>Messages</h3>
                    <p>Check messages sent by admins and lab staffs.</p>
                    <p>Make sure, you can send messages for admins and lab staffs.</p>
                    <a href="./messages.php"><button type="button">View Details</button></a>
                </div>
            </section>

        </main>
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

    .dashcontainer {
        height: 100vh;
        background: url(../images/news-image1.jpg) no-repeat center center/cover;
    }

    .imagelow {
        width: 100%;
        min-width: 100%;
        height: 700px;
        margin-top: 20px;
    }

    .imagelow img {
        width: 100%;
        height: 100%;
        border-radius: 15px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    footer {
        margin-top: -70px;
    }
</style>

</html>