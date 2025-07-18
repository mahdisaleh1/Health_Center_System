<?php
include '../config.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE user_id = ? AND status = 'active'";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $username = $user['username'];
    $phone = $user['phone'];
    $password = $user['password'];
} else {
    $email = '';
    $username = '';
    $phone = '';
    $password = '';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        //if the user is logged in, take info from session
        $user_id = $_SESSION['user_id']; //patient_id
        $query = "SELECT * FROM patients WHERE user_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        $patient_id = $patient['patient_id'];
        $test_id = $_POST['testname'];
        $doctor_id = $_POST['selectdoctor'];
        $time = $_POST['appTime'];
        $date = $_POST['appDate'];
        $comment = $_POST['comments'];
        $app_query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, type, status, notes) VALUES (?, ?, ?, ?, 'test', 'pending', ?)";
        $stmt = $con->prepare($app_query);
        $stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $time, $comment);
        if ($stmt->execute()) {
            $appointment_id = $stmt->insert_id;
            $app_query = "INSERT INTO labtests (appointment_id, doctor_id, patient_id, test_id, status) VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $con->prepare($app_query);
            $stmt->bind_param("iiii", $appointment_id, $doctor_id, $patient_id, $test_id);
            if ($stmt->execute()) {
                //echo '<script>alert("Test appointment placed successfuly!" );</script>';
                //header("Refresh:0.11; url=../index.php");
                $redirectUrl = '../index.php';
            } else {
                echo '<script>alert("Error inseting test!" );</script>';
            }
        } else {
            echo '<script>alert("Error inserting appointment!" );</script>';
        }
    } else {
        $email = $_POST['email'];
        $stmt = "SELECT user_id FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $stmt);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<script>alert("Please use a different email address!" );</script>';
                header("Refresh:0.11; url=./signup.php");
            } else {
                $fullname = $_POST['fullname'];
                $phonenb = $_POST['phonenb'];
                $password = $_POST['psw'];
                $customer_query = "INSERT INTO users (email, username, phone, password, role, status) VALUES (?, ?, ?, ?, 'patient', 'active')";
                $stmt = $con->prepare($customer_query);
                $stmt->bind_param("ssis", $email, $fullname, $phonenb, $password);
                if ($stmt->execute()) {
                    $user_id = $stmt->insert_id;
                    $patients_query = "INSERT INTO patients (user_id, fullname) VALUES (?, ?)";
                    $stmt = $con->prepare($patients_query);
                    $stmt->bind_param("is", $user_id, $fullname);
                    if ($stmt->execute()) {
                        $patient_id = $stmt->insert_id;
                        $test_id = $_POST['testname'];
                        $doctor_id = $_POST['selectdoctor'];
                        $time = $_POST['appTime'];
                        $date = $_POST['appDate'];
                        $comment = $_POST['comments'];
                        $app_query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, type, status, notes) VALUES (?, ?, ?, ?, 'test', 'pending', ?)";
                        $stmt = $con->prepare($app_query);
                        $stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $time, $comment);
                        if ($stmt->execute()) {
                            $appointment_id = $stmt->insert_id;
                            $app_query = "INSERT INTO labtests (appointment_id, doctor_id, patient_id, test_id, status) VALUES (?, ?, ?, ?, 'pending')";
                            $stmt = $con->prepare($app_query);
                            $stmt->bind_param("iiii", $appointment_id, $doctor_id, $patient_id, $test_id);
                            if ($stmt->execute()) {
                                //echo '<script>alert("Test appointment placed successfuly!" );</script>';
                                //header("Refresh:0.11; url=../index.php");
                                $redirectUrl = '../index.php';
                            } else {
                                echo '<script>alert("Error inserting test!" );</script>';
                            }
                        } else {
                            echo '<script>alert("Error inserting appointment!" );</script>';
                        }
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Test Appointment - Health Medical Center</title>

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

    <?php if (isset($redirectUrl)): ?>
        <div id="popup-overlay">
            <div id="popup-box">
                <h2>Appointment Set Successful</h2>
                <p>You will be redirected to home page shortly.</p>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "<?= $redirectUrl ?>";
            }, 3000);
        </script>
    <?php endif; ?>
    
    <!-- HEADER -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-5">
                    <p>Welcome to a Professional Health Care</p>
                </div>
                <div class="col-md-8 col-sm-7 text-align-right">
                    <span class="phone-icon"><i class="fa fa-phone"></i>+961 3 123 456</span>
                    <span class="date-icon"><i class="fa fa-calendar-plus-o"></i> 6:00 AM - 10:00 PM (Mon-Fri)</span>
                    <span class="email-icon"><i class="fa fa-envelope-o"></i> <a href="#">info@company.com</a></span>
                </div>
            </div>
        </div>
    </header>


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
                    <li><a href="../index.php" class="smoothScroll">Home</a></li>
                    <li><a href="./appdoctor.php" class="smoothScroll">Appointment for doctor</a></li>
                    <li><a href="./apptest.php" class="smoothScroll">Appointment for test</a></li>
                </ul>
            </div>
        </div>
    </section>


    <!-- HOME -->
    <form action="./apptest.php" method="post">
        <div class="testcontainer">
            <div class="left-section">
                <div class="drimage">
                    <img src="../images/team-image3.jpg" class="imagetwo" alt="">
                </div>
            </div>
            <div class="right-section">
                <h1>Appointment for Tests!</h1>
                <div class="test-form">

                    <p>Already have an account? <a href="../users/login.php">Login here</a>!</p>

                    <?php
                    if (isset($_SESSION['user_id'])) {
                    ?>
                        <label>Full Name:</label>
                        <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" value="<?php echo htmlspecialchars($username); ?>" readonly>

                        <label>Email Address:</label>
                        <input type="email" name="email" placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>" readonly>

                        <label>Phone Number:</label>
                        <input type="number" name="phonenb" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone); ?>" readonly>

                        <label>Password:</label>
                        <input type="password" name="psw" placeholder="Generate password for your account" value="<?php echo htmlspecialchars($password); ?>" readonly>
                    <?php
                    } else {
                    ?>
                        <label>Full Name:</label>
                        <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" value="<?php echo htmlspecialchars($username); ?>" required>

                        <label>Email Address:</label>
                        <input type="email" name="email" placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>" required>

                        <label>Phone Number:</label>
                        <input type="number" name="phonenb" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone); ?>" required>

                        <label>Password:</label>
                        <input type="password" name="psw" placeholder="Generate password for your account" value="<?php echo htmlspecialchars($password); ?>" required>
                    <?php
                    }
                    ?>

                    <label>Choose test name:</label>
                    <select name="testname" id="testname">
                        <option value="0">Select test</option>
                        <!-- Add more specialties as needed -->
                        <?php
                        $sql = "SELECT * FROM testname";
                        $result = $con->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value = '" . $row['test_id'] . "'>" . htmlspecialchars($row['test_name']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No specialties found!</option>";
                        }
                        ?>
                    </select>

                    <label>Choose a doctor: (optional)</label>
                    <select name="selectdoctor">
                        <option value="0">Select doctor</option>
                        <!-- Add more specialties as needed -->
                        <?php
                        $sql = "SELECT * FROM doctors";
                        $result = $con->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value = '" . $row['doctor_id'] . "'>" . htmlspecialchars($row['doctor_name']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No specialties found!</option>";
                        }
                        ?>
                    </select>

                    <label>Choose Date:</label>
                    <input type="date" id="datePicker" name="appDate" required>

                    <label>Choose Time:</label>
                    <input type="time" id="timePicker" name="appTime" required>

                    <label>Leave a comment (optional):</label>
                    <textarea placeholder="Write your comment here" name="comments"></textarea>
                </div>


                <div class="buttons">
                    <a href="./appointment.php"><button type="button" class="cancelbtn">Cancel</button></a>
                    <button type="submit" class="submitbtn" name="submit">Place appointment</button>
                </div>
            </div>
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
                    <div class="col-md-2 col-sm-2 text-align-center">
                        <div class="angle-up-btn">
                            <a href="#top" class="smoothScroll wow fadeInUp" data-wow-delay="1.2s"><i class="fa fa-angle-up"></i></a>
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
<script>
    document.getElementById("datePicker").addEventListener("input", function() {
        let selectedDate = new Date(this.value);
        let day = selectedDate.getDay(); // 0 = Sunday, 6 = Saturday

        if (day === 0 || day === 6) {
            alert("Weekends are not allowed! Please select a weekday.");
            this.value = ""; // Clear the input
        }
    });
    document.getElementById("timePicker").addEventListener("input", function() {
        let selectedTime = this.value;
        let [hours, minutes] = selectedTime.split(":").map(Number);
        let totalMinutes = hours * 60 + minutes; // Convert time to total minutes

        let minTime = 6 * 60; // 6:00 AM in minutes
        let maxTime = 22 * 60; // 10:00 PM in minutes

        if (totalMinutes < minTime || totalMinutes > maxTime) {
            alert("Please select a time between 6:00 AM and 10:00 PM.");
            this.value = ""; // Clear the input
        }
    });
</script>
<script>
    //MAKE SURE THE uSER WILL CHOOSE ONLY NEXT DAYS NOT PREVIOUS
    const today = new Date().toISOString().split('T')[0];
    // Set the min attribute of the date input
    document.getElementById("datePicker").setAttribute("min", today);
</script>
<style>
    .test-form p {
        text-align: center;
        font-size: large;
    }

    .testcontainer {
        display: flex;
        align-items: flex-start;
        gap: 30px;
        padding: 20px;
        max-width: 100%;
        margin: auto;
        flex-wrap: wrap;
        /* Enables stacking on small screens */
    }

    .left-section {
        flex: 1;
        min-width: 300px;

    }

    .right-section {
        flex: 2;
        min-width: 300px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }

    .drimage img {
        width: 100%;
        border-radius: 12px;
        object-fit: cover;
    }

    /* Form and layout enhancements */
    .test-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .test-form label {
        font-weight: bold;
        margin-top: 10px;
    }

    .test-form input,
    .test-form select,
    .test-form textarea {
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .buttons {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }

    .cancelbtn,
    .submitbtn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .cancelbtn {
        background-color: #f44336;
        color: white;
    }

    .submitbtn {
        background-color: #4CAF50;
        color: white;
    }

    @media screen and (max-width: 768px) {
        .drimage .imageone {
            visibility: hidden;
            height: 0;
        }
    }

    /* Popup background */
    #popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    /* Popup box */
    #popup-box {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        font-family: Arial, sans-serif;
    }

    #popup-box h2 {
        margin-top: 0;
        color: #28a745;
    }

    #popup-box p {
        margin: 10px 0;
    }
</style>

</html>