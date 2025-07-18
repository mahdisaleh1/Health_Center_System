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
    $labstaff_id = $admin['user_id'];
    if ($role !== 'doctor') {
        header("Location: ../users/login.php");
    }
}


if (isset($_POST['save_record_changes'])) {
    $record_id = $_POST['record_id'];
    $record_details = $_POST['record_new_details'];
    $status = $_POST['status'];
    $update = "UPDATE medicalrecords 
            SET record_details = ?, status = ?
            WHERE record_id = ?";
    $stmt = $con->prepare($update);
    $stmt->bind_param("ssi", $record_details, $status, $record_id);
    if ($stmt->execute()) {
        echo '<script>alert("Record has been updated!")</script>';
        header("Refresh:0.11; url=./medicalrecords.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Record Details - Doctor</title>

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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <li><a href="./doctordashboard.php" class="smoothScroll">Home</a></li>
                    <li><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
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
                    <li><a href="./doctordashboard.php">Dashboard</a></li>
                    <li><a href="./medicalrecords.php">Medical Records</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <?php
            if (isset($_GET['id'])) {
                $record_id = intval($_GET['id']);
                $patient_query = mysqli_query($con, "
                            SELECT 
                                medicalrecords.*, 
                                patients.fullname
                            FROM medicalrecords
                            JOIN patients ON medicalrecords.patient_id = patients.patient_id
                            WHERE medicalrecords.record_id = '$record_id'
                        ");
                if (mysqli_num_rows($patient_query) == 1) {
                    $patient = mysqli_fetch_assoc($patient_query);
                    $patient_fullname = $patient['fullname'];
                }
            }
            ?>
            <header>
                <p>Medical record details for <?php echo $patient_fullname; ?></p>
            </header>

            <div class="second-content">
                <?php
                if (isset($_GET['id'])) {
                    $appointment_id = intval($_GET['id']);
                    // Fetch main user info
                    $appointment_query = mysqli_query($con, "
                            SELECT 
                                medicalrecords.*,
                                patients.fullname,
                                doctors.doctor_name
                            FROM medicalrecords
                            JOIN patients ON medicalrecords.patient_id = patients.patient_id
                            JOIN doctors ON medicalrecords.doctor_id = doctors.doctor_id
                            WHERE medicalrecords.record_id = '$appointment_id'
                        ");
                    if (mysqli_num_rows($appointment_query) == 1) {
                        $appointment = mysqli_fetch_assoc($appointment_query);
                        echo "<div class='allinfos'>";
                        echo "<h2>Record Details</h2>";
                        echo "<a href='#' id='editbtn'><i class='fa fa-edit'> Edit </i></a>";
                        echo "<p><strong>Record ID:</strong> {$appointment['record_id']}</p>";
                        echo "<p><strong>Patient Name:</strong> {$appointment['fullname']}</p>";
                        echo "<p><strong>Doctor Name:</strong> {$appointment['doctor_name']}</p>";
                        echo "<p><strong>Creation Date:</strong> {$appointment['date']}</p>";

                        echo "<div class='appointmentInfo' id='appointmentInfo'>";
                        echo "<p><strong>Record Details:</strong> {$appointment['record_details']}</p>";
                        echo "<p><strong>Status:</strong> {$appointment['status']}</p>";
                        echo "</div>";

                        echo "<form action='' method='POST'>";
                        echo "<div class='editableDiv' id='editableDiv'>";
                        echo "<input type='hidden' name='record_id' value='{$appointment['record_id']}'>";
                        echo "<p><strong>Record details:</strong></p>";
                        echo "<textarea name='record_new_details'>{$appointment['record_details']}</textarea>";
                        echo "<p><strong>Status:</strong>
                                <select name='status' required>
                                    <option value='visible' " . ($appointment['status'] == 'visible' ? 'selected' : '') . ">Visible</option>
                                    <option value='invisible' " . ($appointment['status'] == 'invisible' ? 'selected' : '') . ">Invisible</option>
                                </select>
                            </p>";
                            
                        echo "<button type='submit' name='save_record_changes'>Save Changes</button>";
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
                ?>
            </div>

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
    <script>
        //EDIT APPOINTMENT INFO
        document.getElementById("editbtn").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("editableDiv").style.display = "flex";
            document.getElementById("appointmentInfo").style.display = "none";
            document.getElementById("editbtn").style.display = "none";
        });

    </script>
</body>

<style>
    #addPaymentDiv {
        display: none;
    }

    .addPaymentDiv input {
        width: 100%;
        padding: 0.25rem;
    }

    .addPaymentDiv #statusPayment {
        width: 100%;
        margin-top: 15px;
    }

    #editablePayment {
        display: none;
    }

    .editableDiv {
        display: none;
        flex-direction: column;
    }

    .lidashboard {
        text-decoration: underline;
    }

    .content header {
        width: 100%;
    }

    .second-content {
        width: 100%;
        display: flex;
        flex-direction: row;
        gap: 15px;
    }

    .allinfos {
        width: 100%;
        background-color: #fff;
        padding: 20px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .allinfos p {
        font-size: large;
        margin-top: 15px;
        padding-left: 15px;
    }

    .allinfos a {
        padding-left: 15px;
    }

    .paymentdiv {
        width: 50%;
        background-color: #fff;
        padding: 20px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .paymentdiv p {
        font-size: large;
        margin-top: 15px;
        padding-left: 15px;
    }

    .paymentdiv a {
        padding-left: 15px;
    }

    .resultdiv {
        margin-top: 3rem;
        width: 100%;
        background-color: #fff;
        padding: 20px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .resultdiv p {
        font-size: large;
        margin-top: 15px;
        margin-bottom: 15px;
    }

    footer {
        margin-top: -100px;
    }

    @media screen and (max-width: 768px) {
        .second-content {
            display: flex;
            gap: 15px;
            flex-direction: column;
        }

        .allinfos {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .paymentdiv {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
    }
</style>

</html>