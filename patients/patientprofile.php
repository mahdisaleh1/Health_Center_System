<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../users/login.php");
    exit();
} else {
    $user_id = $_SESSION['user_id'];
    //$query = "SELECT * FROM patients WHERE user_id = ?";
    $query = "
    SELECT patients.*, 
            users.*
            FROM patients
            INNER JOIN users ON users.user_id = patients.user_id
            WHERE patients.user_id = ?
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
    $patient_id = $patient['patient_id'];
    $user_id = $patient['user_id'];
    $email = $patient['email'];
    $fullname = $patient['fullname'];
    $phone = $patient['phone'];
    $psw = $patient['password'];
    $username = $patient['username'];
    $dob = $patient['dob'];
    $gender = $patient['gender'];
    $emg = $patient['emergency_contact'];
    $address = $patient['address'];
}
if (isset($_POST['update_profile'])) {
    $pat_id = $_POST['patient_id'];
    $user_idd = $_POST['user_id'];
    $emailad = $_POST['email'];
    $phonenb = $_POST['phonenb'];
    $fullnamee = $_POST['fullname'];
    $psww = $_POST['psw'];
    $genderr = $_POST['patgendar'];
    $addressa = $_POST['pataddress'];
    $patdob = $_POST['patdob'];
    $emgcontact = $_POST['emgcontact'];
    if ($email != $emailad || $fullname != $fullnamee || $phone != $phonenb || $psw != $psww || $dob != $patdob || $gender != $genderr || $emg != $emgcontact || $address != $addressa) {
        $update = "UPDATE users SET email = '$emailad', phone = '$phonenb', password='$psww' WHERE user_id = ?";
        $stmt = $con->prepare($update);
        $stmt->bind_param("i", $user_idd);
        if ($stmt->execute()) {
            $update = "UPDATE patients SET fullname = '$fullnamee', dob = '$patdob', gender = '$genderr', address = '$addressa', emergency_contact = '$emgcontact' WHERE user_id = ?";
            $stmt = $con->prepare($update);
            $stmt->bind_param("i", $user_idd);
            if ($stmt->execute()) {
                echo '<script>alert("Profile has been updated!")</script>';
                header("Refresh:0.11; url=./patientprofile.php");
                exit();
            } else {
                echo "Error updating profile: " . $stmt->error;
            }
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Profile - Health Medical Center</title>

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
                    <li><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
                    <li><a href="./prescriptions.php" class="smoothScroll">Prescriptions</a></li>
                    <li class="lidashboard"><a href="./patientprofile.php" class="smoothScroll">Profile</a></li>
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
                    <li><a href="../appointments/appointment.php">Book Appointment</a></li>
                    <li><a href="./patapp.php">My Appointments</a></li>
                    <li><a href="./labresults.php">Lab Results</a></li>
                    <li><a href="./medicalrecords.php">Medical Records</a></li>
                    <li><a href="./prescriptions.php">Prescriptions</a></li>
                    <li class="lidashboard"><a href="./patientprofile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>Your profile can be modified.</p>
            </header>
            <form action="patientprofile.php" method="post">
                <div class="profilecontainer">
                    <h3>Personal Information</h3>
                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <label for="patusername">Username:</label>
                    <input type="text" name="patusername" value="<?php echo htmlspecialchars($username); ?>" readonly>
                    <label for="email">Email address:</label>
                    <input type="email" name="email" placeholder="Your email address" value="<?php echo htmlspecialchars($email); ?>" required>
                    <label for="fullname">Full Name:</label>
                    <input type="text" name="fullname" placeholder="Your full name" value="<?php echo htmlspecialchars($fullname); ?>" required>
                    <label for="phonenb">Phone Number:</label>
                    <input type="number" name="phonenb" placeholder="Your phone number" value="<?php echo htmlspecialchars($phone); ?>" required>
                    <label for="psw">Password:</label>
                    <input type="password" name="psw" placeholder="Generate a password" value="<?php echo htmlspecialchars($psw); ?>" onfocus="this.type='text'"
                        onblur="this.type='password'" required>
                    <label for="patgendar">Gender:</label>
                    <select name="patgendar">
                        <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
                    </select>
                    <label for="pataddress">Address:</label>
                    <input type="text" name="pataddress" placeholder="Enter your address" value="<?php echo htmlspecialchars($address); ?>" required>
                    <label for="patdob">Birthday:</label>
                    <input type="date" name="patdob" value="<?php echo htmlspecialchars($dob); ?>" required>
                    <label for="emgcontact">Emergency Contact:</label>
                    <input type="number" name="emgcontact" placeholder="Enter emergency contact number" value="<?php echo htmlspecialchars($emg); ?>" required>
                </div>
                <div class="buttons">
                    <a href="./patientdashboard.php"><button type="button">Cancel</button></a>
                    <button type="submit" name="update_profile" class="updatebtn">Update Profile</button>
                </div>
            </form>
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
    footer {
        margin-top: -50px;
    }

    .lidashboard {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .buttons {
            margin-top: 50px;
            max-width: 100%;
            width: 100%;
            margin-left: 0;
            display: flex;
            flex-direction: row;
            gap: 25px;
        }

        .buttons button {
            width: 100%;
            margin-left: 0;
        }
    }
</style>

</html>