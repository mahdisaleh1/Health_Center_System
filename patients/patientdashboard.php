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
    $admin = $result->fetch_assoc();
    $patient_id = $admin['patient_id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Dashboard - Health Medical Center</title>

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
                    <li class="lidashboard"><a href="./patientdashboard.php" class="smoothScroll">Dashboard</a></li>
                    <li><a href="./patapp.php" class="smoothScroll">My Appointments</a></li>
                    <li><a href="./labresults.php" class="smoothScroll">Lab Results</a></li>
                    <li><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
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
                        <li class="lidashboard"><a href="./patientdashboard.php">Dashboard</a></li>
                        <li><a href="../appointments/appointment.php">Book Appointment</a></li>
                        <li><a href="./patapp.php">My Appointments</a></li>
                        <li><a href="./labresults.php">Lab Results</a></li>
                        <li><a href="./medicalrecords.php">Medical Records</a></li>
                        <li><a href="./prescriptions.php">Prescriptions</a></li>
                        <li><a href="./patientprofile.php">Profile</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <header>
                    <p>Your health records at a glance</p>
                </header>

                <section class="cards">
                    <div class="card">
                        <h3>Upcoming Appointment</h3>
                        <?php
                        $today = date("Y-m-d");

                        // Query to fetch the nearest upcoming (not completed) appointment
                        $sql = "SELECT appointments.*, doctors.doctor_name 
                                FROM appointments 
                                INNER JOIN doctors ON appointments.doctor_id = doctors.doctor_id
                                WHERE appointment_date >= ? AND status != 'completed' AND patient_id = ?
                                ORDER BY appointment_date ASC, appointment_time ASC 
                                LIMIT 1";

                        $stmt = $con->prepare($sql);
                        $stmt->bind_param("si", $today, $patient_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        ?>
                        <?php if ($result->num_rows > 0):
                            $row = $result->fetch_assoc();
                        ?>
                            <p><?= htmlspecialchars($row['doctor_name']) ?></p>
                            <p>Date: <?= date("F j, Y", strtotime($row['appointment_date'])) ?></p>
                            <a href="app_details.php?id=<?= $row['appointment_id'] ?>">
                                <button type="button">View Details</button>
                            </a>
                        <?php else: ?>
                            <p>No upcoming appointments</p>
                            <a href="../appointments/appointment.php">
                                <button type="button">Book Appointment</button>
                            </a>
                        <?php endif; ?>

                        <?php $stmt->close();
                        $con->close(); ?>
                    </div>
                    <div class="card">
                        <h3>Check Lab Results</h3>
                        <p>All test completed and uploaded results are visible.</p>
                        <a href="./labresults.php"><button type="button">Check List</button></a>
                    </div>
                    <div class="card">
                        <h3>Doctors Available</h3>
                        <p>Search and check all doctors available by specialty.</p>
                        <a href="./doctors.php"><button type="button">Check Doctors</button></a>
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
    .dashcontainer {
        height: 100vh;
        background: url(../images/slider2.jpg) no-repeat center center/cover;
    }
</style>

</html>