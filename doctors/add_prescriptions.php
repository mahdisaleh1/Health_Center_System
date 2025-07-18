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

if (isset($_POST['addprescription'])) {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $diagnosis = empty($_POST['diagnosis']) ? "NULL" : "'{$_POST['diagnosis']}'";
    $medications = $_POST['medications'];
    $instructions = $_POST['instructions'];
    $date_issued = date("Y-m-d");
    $sql = "INSERT INTO prescriptions (patient_id, doctor_id, date_issued, diagnosis, medications, instructions)
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("iissss", $patient_id, $doctor_id, $date_issued, $diagnosis, $medications, $instructions);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo '<script>alert("Prescription saved successfully.")</script>';
        header("Refresh:0.11; url=./prescriptions.php");
    } else {
        echo '<script>alert("Failed to save prescription.")</script>';
    }
    $stmt->close();
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Add Prescriptions - Doctor</title>

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
                    <li><a href="./patients.php" class="smoothScroll">Patients</a></li>
                    <li><a href="./appointments.php" class="smoothScroll">Appointments</a></li>
                    <li><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
                    <li><a href="./messages.php" class="smoothScroll">Messages</a></li>
                    <li><a href="./labtests.php" class="smoothScroll">Lab Test</a></li>
                    <li class="lidashboard"><a href="./prescriptions.php" class="smoothScroll">Prescriptions</a></li>
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
                    <li><a href="./doctordashboard.php">Dashboard</a></li>
                    <li><a href="./patients.php">Patients</a></li>
                    <li><a href="./appointments.php">Appointments</a></li>
                    <li><a href="./medicalrecords.php">Medical Records</a></li>
                    <li><a href="./messages.php">Messages</a></li>
                    <li><a href="./labtests.php">Lab Test</a></li>
                    <li class="lidashboard"><a href="./prescriptions.php">Prescriptions</a></li>
                    <li><a href="./doctorprofile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>Fill All Fields Marked With *</p>
            </header>
            <div class="second-content">
                <form action="./add_prescriptions.php" method="post">
                    <h3>Add New Prescription</h3><br>
                    <div class="addiv">
                        <?php
                        $query = "SELECT * FROM doctors WHERE user_id = ?";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $doctor = $result->fetch_assoc();
                        $doctor_id = $doctor['doctor_id'];
                        ?>
                        <label>Patient: *</label>
                        <select name="patient_id">
                            <?php
                            $result = $con->query("
                            SELECT DISTINCT patients.patient_id, patients.fullname
                            FROM patients
                            JOIN appointments ON appointments.patient_id = patients.patient_id
                            WHERE appointments.doctor_id = $doctor_id
                            ");
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['patient_id']}'>{$row['fullname']}</option>";
                            }
                            ?>
                        </select><br><br>

                        <label>Diagnosis: (Optional)</label><br>
                        <textarea name="diagnosis" placeholder="Enter Diagnosis Here"></textarea><br><br>

                        <label>Medications: *</label><br>
                        <textarea name="medications" placeholder=" (e.g., Name, Dosage, Duration)" required></textarea><br><br>

                        <label>Instructions: *</label><br>
                        <textarea name="instructions" placeholder="Enter Instructions Here"></textarea><br><br>

                        <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
                        <button type="submit" name="addprescription">Submit Prescription</button>
                    </div>
                </form>
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

    </script>
</body>

<style>
    .lidashboard {
        text-decoration: underline;
    }

    .content .first-content {
        display: flex;
        flex-direction: row;
        width: 100%;
        gap: 10px;
    }

    .content header {
        width: 100%;
        gap: 20px;
    }

    .second-content h3 {
        text-align: center;
    }

    .addiv {
        margin-top: 0;
        margin-left: 5%;
        width: 90%;
        background-color: #fff;
        padding: 20px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .addiv label {
        font-weight: 800;
        font-size: medium;
        color: #333;
    }

    select {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 4.5rem;
        margin-bottom: 2%;
    }

    textarea {
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