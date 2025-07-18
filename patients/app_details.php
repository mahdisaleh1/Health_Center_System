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

if (isset($_POST['cancelapp'])) {
    $appointment_idd = $_POST['appointment_id'];
    $update = "UPDATE appointments SET status = 'canceled' WHERE appointment_id = ?";
    $stmt = $con->prepare($update);
    $stmt->bind_param("i", $appointment_idd);
    if ($stmt->execute()) {
        $update = "UPDATE labtests SET status = 'canceled' WHERE appointment_id = ?";
        $stmt = $con->prepare($update);
        $stmt->bind_param("i", $appointment_idd);
        if ($stmt->execute()) {
            echo '<script>alert("Appointment has been cancelled!")</script>';
            header("Refresh:0.11; url=./patapp.php");
            exit();
        } else {
            echo "Error updating lab test: " . $stmt->error;
        }
    } else {
        echo "Error updating appointments: " . $stmt->error;
    }
}

if (isset($_POST['save_app_changes'])) {
    $appointment_id = $_POST['appointment_id'];
    $labtest_id = $_POST['labtest_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $update = "UPDATE appointments 
            SET appointment_date = ?, appointment_time = ?
            WHERE appointment_id = ?";
    $stmt = $con->prepare($update);
    $stmt->bind_param("ssi", $appointment_date, $appointment_time, $appointment_id);
    if ($stmt->execute()) {
        echo '<script>alert("Appointment has been updated!")</script>';
        header("Refresh:0.11; url=./app_details.php?id=$appointment_id");
        exit();
    } else {
        echo "Error updating appointment: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Appointment Details - Health Medical Center</title>

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
                    <li class="lidashboard"><a href="./patapp.php" class="smoothScroll">My Appointments</a></li>
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
                    <li><a href="./patientdashboard.php">Dashboard</a></li>
                    <li class="lidashboard"><a href="./patapp.php">My Appointments</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>Here you can find appointment details. You can modify and cancel appointment.</p>
            </header>
            <div class="second-content">
                <?php
                if (isset($_GET['id'])) {
                    $appointment_id = intval($_GET['id']);
                    $query = mysqli_query($con, "SELECT * FROM appointments WHERE appointment_id = '$appointment_id'");
                    if (mysqli_num_rows($query) > 0) {
                        $result = mysqli_fetch_assoc($query);
                        if ($result['type'] == 'doctor') {
                            $appointment_query = mysqli_query($con, "
                            SELECT 
                                appointments.*, 
                                patients.fullname,
                                doctors.doctor_name
                            FROM appointments
                            JOIN patients ON appointments.patient_id = patients.patient_id
                            JOIN doctors ON appointments.doctor_id = doctors.doctor_id
                            WHERE appointments.appointment_id = '$appointment_id'
                            ");
                            if (mysqli_num_rows($appointment_query) == 1) {
                                $appointment = mysqli_fetch_assoc($appointment_query);
                                echo "<div class='allinfos'>";
                                echo "<h2>Appointment Details</h2>";
                                if ($appointment['status'] != 'completed') {
                                    echo "<a href='#' id='editbtn'><i class='fa fa-edit'> Edit </i></a>";
                                }
                                echo "<p><strong>Appointment ID:</strong> {$appointment['appointment_id']}</p>";
                                echo "<p><strong>Patient Name:</strong> {$appointment['fullname']}</p>";
                                echo "<p><strong>Doctor Name:</strong> {$appointment['doctor_name']}</p>";

                                echo "<div class='appointmentInfo' id='appointmentInfo'>";
                                echo "<p><strong>Appointment Date:</strong> {$appointment['appointment_date']}</p>";
                                echo "<p><strong>Appointment Time:</strong> {$appointment['appointment_time']}</p>";
                                echo "</div>";

                                echo "<form action='app_details.php' method='POST'>";
                                echo "<div class='editableDiv' id='editableDiv'>";
                                echo "<input type='hidden' name='appointment_id' value='{$appointment['appointment_id']}'>";
                                echo "<p><strong>Appointment Date:</strong> <input type='date' name='appointment_date' value='{$appointment['appointment_date']}' required></p>";
                                echo "<p><strong>Appointment Time:</strong> <input type='time' name='appointment_time' value='{$appointment['appointment_time']}' required></p>";
                                echo "<button type='submit' name='save_app_changes'>Save Changes</button>";
                                echo "</div>";
                                echo "</form>";
                                echo "<p><strong>Status:</strong> {$appointment['status']}</p>";
                                if ($appointment['status'] != 'completed' && $appointment['status'] != 'canceled') {
                                    echo "<form action='' method='post' onsubmit='return confirmCancel()'>";
                                    echo "<input type='hidden' name='appointment_id' value='{$appointment['appointment_id']}'>";
                                    echo "<button type='submit' name='cancelapp'>Cancel Appointment</button>";
                                    echo "</form>";
                                    echo "<script>
                                        function confirmCancel() {
                                        return confirm('Are you sure you want to cancel this appointment?');
                                        }
                                    </script>";
                                }
                                echo "</div>";

                                echo "<div class='paymentdiv'>";
                                echo "<h2>Payment Details</h2>";
                                $payment_query = mysqli_query($con, "SELECT * FROM payments WHERE appointment_id = '$appointment_id'");
                                if (mysqli_num_rows($payment_query) > 0) {
                                    $payment = mysqli_fetch_assoc($payment_query);
                                    echo "<p><strong>Payment ID:</strong> {$payment['payment_id']}</p>";
                                    echo "<p><strong>Appointment ID:</strong> {$payment['appointment_id']}</p>";
                                    echo "<p><strong>Amount Paid: </strong>$ {$payment['amount']}</p>";
                                    echo "<p><strong>Payment Date:</strong> {$payment['payment_date']}</p>";
                                    echo "<p id='statuscell'><strong>Payment Status:</strong> {$payment['status']}</p>";
                                } else {
                                    echo "<p id='nopay'>No payment added</p>";
                                }
                                echo "</div>";
                            }
                        } else if ($result['type'] == 'test') {
                            $appointment_query = mysqli_query($con, "
                            SELECT 
                                appointments.*, 
                                labtests.*,
                                patients.fullname,
                                doctors.doctor_name,
                                testname.test_name,
                                users.username AS labstaff_username
                            FROM appointments
                            JOIN labtests ON appointments.appointment_id = labtests.appointment_id
                            JOIN patients ON appointments.patient_id = patients.patient_id
                            JOIN doctors ON appointments.doctor_id = doctors.doctor_id
                            JOIN testname ON labtests.test_id = testname.test_id
                            LEFT JOIN labstaff ON labtests.assigned_labstaff_id = labstaff.labstaff_id
                            LEFT JOIN users ON labstaff.user_id = users.user_id
                            WHERE appointments.appointment_id = '$appointment_id'
                            ");
                            if (mysqli_num_rows($appointment_query) == 1) {
                                $appointment = mysqli_fetch_assoc($appointment_query);
                                echo "<div class='allinfos'>";
                                echo "<h2>Appointment Details</h2>";
                                if ($appointment['status'] != 'completed' && $appointment['status'] != 'canceled') {
                                    echo "<a href='#' id='editbtn'><i class='fa fa-edit'> Edit </i></a>";
                                }
                                echo "<p><strong>Lab Test ID:</strong> {$appointment['labtest_id']}</p>";
                                echo "<p><strong>Appointment ID:</strong> {$appointment['appointment_id']}</p>";
                                echo "<p><strong>Patient Name:</strong> {$appointment['fullname']}</p>";
                                echo "<p><strong>Doctor Name:</strong> {$appointment['doctor_name']}</p>";
                                echo "<p><strong>Test Name:</strong> {$appointment['test_name']}</p>";
                                echo "<div class='appointmentInfo' id='appointmentInfo'>";
                                echo "<p><strong>Appointment Date:</strong> {$appointment['appointment_date']}</p>";
                                echo "<p><strong>Appointment Time:</strong> {$appointment['appointment_time']}</p>";
                                echo "</div>";

                                echo "<form action='app_details.php' method='POST'>";
                                echo "<div class='editableDiv' id='editableDiv'>";
                                echo "<input type='hidden' name='appointment_id' value='{$appointment['appointment_id']}'>";
                                echo "<input type='hidden' name='labtest_id' value='{$appointment['labtest_id']}'>";
                                echo "<p><strong>Appointment Date:</strong> <input type='date' name='appointment_date' value='{$appointment['appointment_date']}' required></p>";
                                echo "<p><strong>Appointment Time:</strong> <input type='time' name='appointment_time' value='{$appointment['appointment_time']}' required></p>";
                                echo "<button type='submit' name='save_app_changes'>Save Changes</button>";
                                echo "</div>";
                                echo "</form>";

                                echo "<p><strong>Assigned Lab Staff Username:</strong> " . ($appointment['labstaff_username'] ?? 'N/A') . "</p>";
                                echo "<p><strong>Status:</strong> {$appointment['status']}</p>";

                                if ($appointment['status'] != 'completed' && $appointment['status'] != 'canceled') {
                                    echo "<form action='' method='post' onsubmit='return confirmCancel()'>";
                                    echo "<input type='hidden' name='appointment_id' value='{$appointment['appointment_id']}'>";
                                    echo "<button type='submit' name='cancelapp'>Cancel Appointment</button>";
                                    echo "</form>";
                                    echo "<script>
                                        function confirmCancel() {
                                        return confirm('Are you sure you want to cancel this appointment?');
                                        }
                                    </script>";
                                }
                                echo "</div>";

                                $app_id = $appointment["appointment_id"];
                                echo "<div class='paymentdiv'>";
                                echo "<h2>Payment Details</h2>";
                                $payment_query = mysqli_query($con, "SELECT * FROM payments WHERE appointment_id = '$app_id'");
                                if (mysqli_num_rows($payment_query) > 0) {
                                    $payment = mysqli_fetch_assoc($payment_query);

                                    echo "<p><strong>Payment ID:</strong> {$payment['payment_id']}</p>";
                                    echo "<p><strong>Appointment ID:</strong> {$payment['appointment_id']}</p>";
                                    echo "<p><strong>Amount Paid: </strong>$ {$payment['amount']}</p>";
                                    echo "<p><strong>Payment Date:</strong> {$payment['payment_date']}</p>";
                                    echo "<p id='statuscell'><strong>Payment Status:</strong> {$payment['status']}</p>";
                                } else {
                                    echo "<p id='nopay'>No payment added</p>";
                                }
                                echo "</div>";
                            }
                        }
                    }
                }
                ?>
            </div>
            <?php
            if ($result['type'] == 'test') {


            ?>
                <div class="resultdiv" id="resultdiv">
                    <h2>Result Details</h2>
                    <?php
                    $lab_test_id = $appointment['labtest_id'];
                    $results_query = mysqli_query($con, "SELECT * FROM labresults WHERE labtest_id = '$lab_test_id'");
                    if (mysqli_num_rows($results_query) > 0) {
                        $result = mysqli_fetch_assoc($results_query);
                        echo "<p>Uploaded by: " . $result['uploaded_by'] . "</p>";
                        echo "<p>Comments: " . $result['comments'] . "</p>";
                        echo "<p>Uploaded at: " . $result['uploaded_at'] . "</p>";
                        $filepath = '../uploads/labresults/' . $result['result_file'];
                        if (pathinfo($filepath, PATHINFO_EXTENSION)) {
                            echo "<a href='$filepath' target='_blank'><button type='button'>View Result</button></a>";
                            echo "<a href='$filepath' download class='download-btn'><button type='button'>Download Result</button></a>";
                        } else {
                            echo "<img src='$filepath' alt='Lab Result' width='300'>";
                        }
                    } else {
                    ?>
                        <p>No results added. Fill his form to add the result!</p>

                    <?php
                    }
                    ?>

                </div>
            <?php
            }
            ?>
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
            document.getElementById("editableDiv").style.display = "block";
            document.getElementById("appointmentInfo").style.display = "none";
            document.getElementById("editbtn").style.display = "none";
        });
    </script>
</body>
<style>
    .content {
        max-width: 80%;
        margin-left: 15%;
    }

    footer {
        margin-top: 0;
    }

    .lidashboard {
        text-decoration: underline;
    }

    .second-content {
        width: 100%;
        display: flex;
        flex-direction: row;
        gap: 15px;
    }

    .allinfos {
        width: 50%;
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

    .editableDiv {
        display: none;
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

    .download-btn {
        margin-left: 30px;
    }

    @media screen and (max-width: 768px) {
        .second-content {
            display: flex;
            gap: 15px;
            flex-direction: column;
            width: 100%;
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

        .content {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
        }
    }
</style>

</html>