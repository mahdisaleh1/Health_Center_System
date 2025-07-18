<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../users/login.php");
    exit();
} else {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM patients WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
    $patient_id = $patient['patient_id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Medical Records - Health Medical Center</title>

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
                    <li><a href="./patientdashboard.php" class="smoothScroll">Dashboard</a></li>
                    <li><a href="./patapp.php" class="smoothScroll">My Appointments</a></li>
                    <li><a href="./labresults.php" class="smoothScroll">Lab Results</a></li>
                    <li class="lidashboard"><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
                    <li><a href="./prescriptions.php" class="smoothScroll">Prescriptions</a></li>
                    <li><a href="./patientprofile.php" class="smoothScroll">Profile</a></li>
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
                        <li><a href="./patientdashboard.php">Dashboard</a></li>
                        <li><a href="../appointments/appointment.php">Book Appointment</a></li>
                        <li><a href="./patapp.php">My Appointments</a></li>
                        <li><a href="./labresults.php">Lab Results</a></li>
                        <li class="lidashboard"><a href="./medicalrecords.php">Medical Records</a></li>
                        <li><a href="./prescriptions.php">Prescriptions</a></li>
                        <li><a href="./patientprofile.php">Profile</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <header>
                    <p>Find Your Medical Records</p>
                </header>

                <section class="cards">
                    

                    <?php 
                    $query = "
                    SELECT medicalrecords.*,
                    doctors.doctor_name
                    FROM medicalrecords
                    INNER JOIN doctors ON medicalrecords.doctor_id = doctors.doctor_id
                    WHERE medicalrecords.status='visible' AND medicalrecords.patient_id = '$patient_id'
                    ";
                    $result = mysqli_query($con, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='card'>";
                            echo "<h3>Record ID: #{$row['record_id']}</h3>";
                            echo "<p><strong>Doctor Name: </strong> {$row['doctor_name']}</p>";
                            echo "<p><strong>Creation Date: </strong> {$row['date']}</p>";
                            echo "<p><strong>Record Details: </strong> {$row['record_details']}</p>";
                            echo "<a href='record_details.php?id={$row['record_id']}'><button type='button'>Check Details</button></a>";
                            echo "</div>";
                        }
                    }
                    else {
                        echo "<h2>No Medical Records Added!</h2>";
                    }
                    ?>
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
    .cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .comment-truncate {
        display: -webkit-inline-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    @media (max-width: 768px) {
    .sidebar {
        visibility: hidden;
    }

    .content {
        width: 100%;
        max-width: 100%;
        margin-left: 0;
    }

    .cards {
        grid-template-columns: 1fr;
    }

    footer {
        margin-left: 0;
        margin-top: -70px;
    }
}
</style>

</html>