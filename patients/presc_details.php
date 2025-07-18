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

    <title>Prescription Details - Health Medical Center</title>

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
                    <li class="lidashboard"><a href="./prescriptions.php" class="smoothScroll">Prescriptions</a></li>
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
                        <li class="lidashboard"><a href="./prescriptions.php">Prescriptions</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <header>
                    <p>Your Prescriptions Details. Make sure this prescription can be used once.</p>
                </header>
                <?php
                if (isset($_GET['id'])) {
                    $presc_id = intval($_GET['id']);
                    $patient_query = mysqli_query($con, "
                            SELECT 
                                prescriptions.*, 
                                patients.fullname,
                                doctors.doctor_name
                            FROM prescriptions
                            JOIN patients ON prescriptions.patient_id = patients.patient_id
                            JOIN doctors ON prescriptions.doctor_id = doctors.doctor_id
                            WHERE prescriptions.pres_id = '$presc_id'
                        ");
                    if (mysqli_num_rows($patient_query) == 1) {
                        $patient = mysqli_fetch_assoc($patient_query);
                        $patient_fullname = $patient['fullname'];
                    }
                }
                ?>
                <div class="second-content">
                    <h3>Prescription Details</h3><br>
                    <div class="alldetails">
                        <input type="hidden" name="prescription_id" value="<?php echo $presc_id; ?>">
                        <label>Patient Name:</label><br>
                        <input type="text" name="patient_name" value="<?php echo $patient['fullname'] ?>" readonly>
                        <label>Doctor Name:</label>
                        <input type="text" name="docotr_name" value="<?php echo $patient['doctor_name'] ?>" readonly>
                        <label>Created At:</label>
                        <input type="text" value="<?php echo $patient['created_at'] ?>">
                        <div class="editablediv" id="editablediv">
                            <label>Diagnosis:</label><br>
                            <textarea readonly><?php echo $patient['diagnosis'] ?></textarea><br>
                            <label>Medications: </label><br>
                            <textarea readonly><?php echo $patient['medications'] ?></textarea><br>
                            <label>Instructions:</label><br>
                            <textarea readonly><?php echo $patient['instructions'] ?></textarea><br>
                        </div><br>
                        <a class="downloadbtn" id="downloadbtn" href="./functionality/download_prescription.php?id=<?php echo $patient['pres_id']; ?>" target="_blank">Download as PDF File</a>
                    </div>
                </div>
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
    .downloadbtn {
        background: #333;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }
    .alldetails {
        margin-top: 0;
        margin-left: 5%;
        width: 90%;
        background-color: #fff;
        padding: 20px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .alldetails label {
        font-weight: 800;
        font-size: medium;
        color: #333;
    }

    input {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 4.5rem;
        margin-bottom: 2%;
        background-color: #ddd;
    }

    .editablediv textarea {
        background-color: #ddd;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 7rem;
        margin-bottom: 2%;
    }
</style>

</html>