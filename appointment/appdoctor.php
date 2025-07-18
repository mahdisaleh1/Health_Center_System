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
        $doctor_id = $_POST['doctorselect']; //doctor_id
        $day_app = $_POST['dateapp'];
        $time_app = $_POST['timeapp'];
        $notes = $_POST['comments'];

        $app_query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, type, status, notes) VALUES (?, ?, ?, ?, 'doctor', 'pending', ?)";
        $stmt = $con->prepare($app_query);
        $stmt->bind_param("iisss", $patient_id, $doctor_id, $day_app, $time_app, $notes);
        if ($stmt->execute()) {
            //echo '<script>alert("Appointment placed successfuly!" );</script>';
            //header("Refresh:0.11; url=../index.php");
            $redirectUrl = '../index.php';
        } else {
            echo '<script>alert("Error!" );</script>';
        }
    } else {
        //if the user isn't logged in, take info from the form and create user account and patient account then app.
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
                        $doctor_id = $_POST['doctorselect']; //doctor_id
                        $day_app = $_POST['dateapp'];
                        $time_app = $_POST['timeapp'];
                        $notes = $_POST['comments'];
                        $app_query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, type, status, notes) VALUES (?, ?, ?, ?, 'doctor', 'pending', ?)";
                        $stmt = $con->prepare($app_query);
                        $stmt->bind_param("iisss", $patient_id, $doctor_id, $day_app, $time_app, $notes);
                        if ($stmt->execute()) {
                            //echo '<script>alert("Appointment placed successfuly!" );</script>';
                            //header("Refresh:0.11; url=../index.php");
                            $redirectUrl = '../index.php';
                        } else {
                            echo '<script>alert("Error!" );</script>';
                        }
                    } else {
                        echo "Error creating your account! Try again later.";
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

    <title>Doctor Appointment - Health Medical Center</title>

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
    <form action="appdoctor.php" method="post">
        <div class="testcontainer">
            <div class="left-section">
                <div class="drimage">
                    <img src="../images/appointment_two.jpg" class="imageone" alt="">
                    <img src="../images/appointment-image.jpg" class="imagetwo" alt="">
                </div>
            </div>
            <div class="right-section">
                <h1>Appointment for Doctor!</h1>
                <div class="test-form">
                    <p>Already have an account? <a href="../users/login.php">Login here</a>!</p>

                    <h3>Personal Information</h3>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                    ?>
                        <label>Full Name: *</label>
                        <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" value="<?php echo htmlspecialchars($username); ?>" readonly>

                        <label>Email address: *</label>
                        <input type="email" name="email" placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>" readonly>

                        <label>Phone Number: *</label>
                        <input type="number" name="phonenb" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone); ?>" readonly>

                        <label>Password: *</label>
                        <input type="password" name="psw" placeholder="Generate your password" value="<?php echo htmlspecialchars($password); ?>" readonly>
                    <?php
                    } else {
                    ?>
                        <label>Full Name: *</label>
                        <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" value="<?php echo htmlspecialchars($username); ?>" required>

                        <label>Email address: *</label>
                        <input type="email" name="email" placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>" required>

                        <label>Phone Number: *</label>
                        <input type="number" name="phonenb" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone); ?>" required>

                        <label>Password: *</label>
                        <input type="password" name="psw" placeholder="Generate your password" value="<?php echo htmlspecialchars($password); ?>" required>

                    <?php
                    }
                    ?>
                    <h3>Appointment Information</h3>

                    <label>Choose Specialty: *</label>
                    <select name="specialty" id="specialty">
                        <option value="0">Select specialty</option>
                        <!-- Add more specialties as needed -->
                        <?php
                        $sql = "SELECT * FROM specialty";
                        $result = $con->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value = '" . $row['specialty_id'] . "'>" . htmlspecialchars($row['specialty_name']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No specialties found!</option>";
                        }
                        ?>
                    </select>

                    <label>Choose a doctor: *</label>
                    <select name="doctorselect" id="doctorselect">
                        <option value="0">Select doctor</option>
                    </select>

                    <label>Choose Date: *</label>
                    <input type="date" name="dateapp" id="datePicker" required disabled>

                    <label>Choose Time: *</label>
                    <input type="time" name="timeapp" id="timePicker" required disabled>
                    <span id="availabilityInfo"></span>

                    <label>Leave a comment (optional):</label>
                    <textarea name="comments" placeholder="Write your comment here"></textarea>
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

    //GET DOCTORS AS SPECIALTY SELECTED
    document.getElementById('specialty').addEventListener('change', function() {
        const specialtyId = this.value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "get_doctors_by_specialty.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById("doctorselect").innerHTML = this.responseText;
            }
        };

        xhr.send("specialty_id=" + specialtyId);
    }); //END OF GET DOCTORS AS SPECIALTY

    //Get days and time of doctor selected
    document.getElementById('doctorselect').addEventListener('change', function() {
        const doctorId = this.value;

        if (doctorId === "0") {
            // Reset and disable date and time inputs if no doctor is selected
            document.getElementById('datePicker').value = '';
            document.getElementById('datePicker').disabled = true;
            document.getElementById('timePicker').value = '';
            document.getElementById('timePicker').disabled = true;
            document.getElementById('availabilityInfo').textContent = '';
            return;
        }

        // Fetch doctor's availability
        fetch('get_doctor_availability.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'doctor_id=' + encodeURIComponent(doctorId),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const availability = data.availability;
                    // Enable date input
                    document.getElementById('datePicker').disabled = false;
                    // Store availability data for later use
                    document.getElementById('datePicker').dataset.availability = JSON.stringify(availability);
                    document.getElementById('availabilityInfo').textContent = 'Please select a date to see available times.';
                } else {
                    document.getElementById('availabilityInfo').textContent = 'No availability found for the selected doctor.';
                    document.getElementById('datePicker').disabled = true;
                    document.getElementById('timePicker').disabled = true;
                }
            })
            .catch(error => console.error('Error:', error));
    });

    document.getElementById('datePicker').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const dayOfWeek = selectedDate.toLocaleString('en-us', {
            weekday: 'long'
        });

        const availability = JSON.parse(this.dataset.availability || '[]');
        const dayAvailability = availability.filter(slot => slot.available_day === dayOfWeek);

        if (dayAvailability.length > 0) {
            // Enable time input and set min and max attributes based on availability
            document.getElementById('timePicker').disabled = false;
            document.getElementById('timePicker').min = dayAvailability[0].available_from;
            document.getElementById('timePicker').max = dayAvailability[0].available_to;
            document.getElementById('availabilityInfo').textContent = `Available times on ${dayOfWeek}: ${dayAvailability[0].available_from} to ${dayAvailability[0].available_to}`;
        } else {
            document.getElementById('timePicker').disabled = true;
            document.getElementById('availabilityInfo').textContent = `No availability on ${dayOfWeek}. Please select another date.`;
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
        height: 100%;
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