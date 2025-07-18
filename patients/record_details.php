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
if (isset($_GET['id'])) {
    $record_id = intval($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Record Details - Health Medical Center</title>

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
                    <li><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
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
                        <li><a href="./medicalrecords.php">Medical Records</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <header>
                    <p>Medical Records Details.</p>
                </header>
                <?php
                $query = "
                SELECT medicalrecords.*,
                doctors.doctor_name,
                patients.fullname
                FROM medicalrecords
                INNER JOIN doctors ON medicalrecords.doctor_id = doctors.doctor_id
                INNER JOIN patients ON medicalrecords.patient_id = patients.patient_id
                WHERE medicalrecords.status='visible' AND medicalrecords.patient_id = '$patient_id'
                ";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="record">
                            <h3>Record Details</h3>
                            <div class="recordetails">
                                <p><strong>Record ID: </strong># <?php echo $record_id; ?></p>
                                <p><strong>Patient Name: </strong> <?php echo $row['fullname']; ?></p>
                                <p><strong>Doctor Name: </strong> <?php echo $row['doctor_name']; ?></p>
                                <p><strong>Creation Date: </strong> <?php echo $row['date']; ?></p>
                                <p><strong>Record Comments: </strong> <?php echo $row['record_details']; ?></p>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>

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

    .record h3 {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .recordetails {
        width: 100%;
        background-color: #fff;
        padding: 20px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .recordetails p {
        font-size: x-large;
        margin-top: 30px;
        padding-left: 15px;
    }

    footer {
        margin-top: 0;
    }
</style>

</html>