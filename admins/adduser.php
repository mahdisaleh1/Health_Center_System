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
    if ($role !== 'admin') {
        header("Location: ../../users/login.php");
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['psw'];
    $phone = $_POST['phonenb'];
    $role = $_POST['role'];

    $user_query = "INSERT INTO users (email, username, phone, password, role, status) VALUES (?, ?, ?, ?, ?, 'active')";
    $stmt = $con->prepare($user_query);
    $stmt->bind_param("ssiss", $email, $username, $phone, $password, $role);
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        if ($role == 'patient') {
            $patients_query = "INSERT INTO patients (user_id, fullname) VALUES (?, ?)";
            $stmt = $con->prepare($patients_query);
            $stmt->bind_param("is", $user_id, $username);
            if ($stmt->execute()) {
                echo '<script>alert("Patient account has been created!" );</script>';
                header("Refresh:0.11; url=./allusers.php");
            } else {
                echo "Error creating patient account! Try again later.";
            }
        } else if ($role == 'doctor') {
            $specialty = $_POST['specialty'];
            $patients_query = "INSERT INTO doctors (user_id, specialty_id) VALUES (?, ?)";
            $stmt = $con->prepare($patients_query);
            $stmt->bind_param("ii", $user_id, $specialty);
            if ($stmt->execute()) {
                $doctor_id = $stmt->insert_id;
                $days = $_POST['availableDays'];
                $from_times = $_POST['available_from'];
                $to_times = $_POST['available_to'];
                $availability_query = "INSERT INTO doctor_availability (doctor_id, available_day, available_from, available_to) VALUES (?, ?, ?, ?)";
                $stmtt = $con->prepare($availability_query);
                foreach ($days as $day) {
                    $from = $from_times[$day];
                    $to = $to_times[$day];
                    $stmtt->bind_param("isss", $doctor_id, $day, $from, $to);
                    $stmtt->execute();
                }
                echo '<script>alert("Doctor account has been created!" );</script>';
                header("Refresh:0.11; url=./allusers.php");
            } else {
                echo "Error creating doctor account! Try again later.";
            }
        } else if ($role == 'labstaff') {
            $department = $_POST['departmentLabStaff'];
            $position = $_POST['positionLabStaff'];
            $from = $_POST['available_from_labstaff'];
            $to = $_POST['available_to_labstaff'];
            $patients_query = "INSERT INTO labstaff (user_id, department, position, available_from, available_to) VALUES (?, ?, ?, ?, ?)";
            $stmt = $con->prepare($patients_query);
            $stmt->bind_param("issss", $user_id, $department, $position, $from, $to);
            if ($stmt->execute()) {
                echo '<script>alert("Lab Staff account has been created!" );</script>';
                header("Refresh:0.11; url=./allusers.php");
            } else {
                echo "Error creating lab staff account! Try again later.";
            }
        } else {
            echo '<script>alert("Check you role selection!")</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Users - Admin </title>
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <li><a href="./admindashboard.php" class="smoothScroll">Home</a></li>
                    <li class="lidashboard"><a href="./allusers.php" class="smoothScroll">Users</a></li>
                    <li><a href="./doctorspecialty.php" class="smoothScroll">Doctor's specialty</a></li>
                    <li><a href="./testname.php" class="smoothScroll">Test name</a></li>
                    <li><a href="./notification.php" class="smoothScroll">Notifications</a></li>
                    <li><a href="./profile.php" class="smoothScroll">Profile</a></li>
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
                    <li><a href="./admindashboard.php">Dashboard</a></li>
                    <li class="lidashboard"><a href="./allusers.php">Users</a></li>
                    <li><a href="./doctorspecialty.php">Doctor's specialty</a></li>
                    <li><a href="./testname.php">Test name</a></li>
                    <li><a href="./notification.php" class="smoothScroll">Notifications</a></li>
                    <li><a href="./profile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>Here you can Create Accounts! <a href="./allusers.php" class="addUser">Click here to check users.</a></p>
            </header>
            <form action="adduser.php" method="post">
                <section class="users">
                    <div class="user-info">
                        <label for="username">Username:</label>
                        <input type="text" id="username" class="username" name="username" placeholder="Enter username" required>
                        <span id="username-message">Username already exists</span><br>
                        <label for="email">Email Address:</label>
                        <input type="email" class="emailadd" name="email" placeholder="Enter email address" required>
                        <label for="psw">Password</label>
                        <input type="password" name="psw" class="psw" placeholder="Enter password" required>
                        <label for="phonenb">Phone Number:</label>
                        <input type="number" class="phone" name="phonenb" placeholder="Enter phone number" required>
                        <label>Choose User Role:</label>
                        <select name="role" id="role" required>
                            <option value="0">Select role</option>
                            <option value="patient">Patient</option>
                            <option value="doctor">Doctor</option>
                            <option value="labstaff">Lab Staff</option>
                        </select>
                        <div id="specialty-container" style="display: none; margin-top: 1rem;">
                            <label for="specialty">Choose Doctor Specialty:</label>
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
                            <label for="daySelector"><strong>Select Available Day:</strong></label>
                            <select id="daySelector">
                                <option value="0">-- Select a day --</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>

                            <div id="availabilityContainer" style="margin-top: 1rem;"></div>
                        </div>
                        <div id="labstaff-container" style="display: none; margin-top: 1rem;">
                            <label for="department">Department:*</label>
                            <input type="text" name="departmentLabStaff" placeholder="Enter the Lab Staff Department">
                            <label for="position"><strong>Position:*</strong></label>
                            <input type="text" name="positionLabStaff" placeholder="Enter the Lab Staff Position" >

                            From: <input type="time" name="available_from_labstaff" >
                            To: <input type="time" name="available_to_labstaff" >
                        </div>
                        <div class="buttons">
                            <a href="./allusers.php"><button type="button">Cancel</button></a>
                            <button type="submit" name="submit">Create user</button>
                        </div>
                    </div>
                </section>
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
    <script>
        //add days and time for the doctors
        const daySelector = document.getElementById("daySelector");
        const container = document.getElementById("availabilityContainer");
        const addedDays = new Set();

        daySelector.addEventListener("change", () => {
            const day = daySelector.value;
            if (!day || addedDays.has(day)) return;

            addedDays.add(day);

            const row = document.createElement("div");
            row.className = "availability-row";
            row.setAttribute("data-day", day);

            row.innerHTML = `
            <input type="hidden" name="availableDays[]" value="${day}">
            <strong>${day}</strong>
            From: <input type="time" name="available_from[${day}]" required>
            To: <input type="time" name="available_to[${day}]" required>
            <button type="button" class="remove-btn" onclick="removeRow('${day}')">✖</button>
        `;

            container.appendChild(row);
            daySelector.value = ""; // reset dropdown
        });

        function removeRow(day) {
            const row = document.querySelector(`[data-day="${day}"]`);
            if (row) {
                row.remove();
                addedDays.delete(day);
            }
        } //END OF ADD FOR DAY AND TIME DOCTORS

        //Check username if available or not
        $(document).ready(function() {
            let checkTimer;

            $('#username').on('input', function() {
                const username = $(this).val();

                clearTimeout(checkTimer); // Cancel previous timer if still running

                // Only check if username has 3+ characters
                if (username.length > 2) {
                    checkTimer = setTimeout(function() {
                        $.ajax({
                            url: './function_user/check_username.php',
                            method: 'POST',
                            data: {
                                username: username
                            },
                            success: function(response) {
                                if (response.trim() === 'exists') {
                                    $('#username-message').show();
                                } else {
                                    $('#username-message').hide();
                                }
                            }
                        });
                    }, 400); // Delay request by 400ms after typing
                } else {
                    $('#username-message').hide();
                }
            });

            // Stop checking if user leaves the input
            $('#username').on('blur', function() {
                clearTimeout(checkTimer);
            });
        }); //END OF CHECKING USERNAME

        //Hide and Visible the doctor's info to add
        document.getElementById('role').addEventListener('change', function() {
            const selectedRole = this.value;
            const specialtyDiv = document.getElementById('specialty-container');

            if (selectedRole === 'doctor') {
                specialtyDiv.style.display = 'block';
            } else {
                specialtyDiv.style.display = 'none';
            }
        }); // END FOR HIDE OR VISIBLE DOCTOR's INFO

        //Hide and Visible the labstaff's info to add
        document.getElementById('role').addEventListener('change', function() {
            const selectedRole = this.value;
            const specialtyDiv = document.getElementById('labstaff-container');

            if (selectedRole === 'labstaff') {
                specialtyDiv.style.display = 'block';
            } else {
                specialtyDiv.style.display = 'none';
            }
        }); // END FOR HIDE OR VISIBLE LABSTAFF's INFO
    </script>
</body>
<style>
    .availability-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .availability-row input[type="time"] {
        padding: 3px 5px;
    }

    .remove-btn {
        cursor: pointer;
        color: red;
        font-weight: bold;
        border: none;
        background: none;
    }

    .lidashboard {
        text-decoration: underline;
    }

    .users {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        background-color: #f9f9f9;
        max-height: auto;
        height: auto;
        font-size: larger;
    }

    .user-info {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 700px;
        height: auto;
    }

    .user-info form {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }

    .user-info label {
        margin-top: 2%;
        font-weight: 600;
        color: #333;
    }

    .user-info .username {
        margin-bottom: 0%;
    }

    .user-info input,
    .user-info select {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 3.5rem;
        margin-bottom: 2%;
    }

    .user-info select {
        height: 4.5rem;
    }

    .user-info input:focus,
    .user-info select:focus {
        outline: none;
        border-color: #007bff;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .buttons button {
        padding: 1rem 1.75rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #username-message {
        display: none;
        color: red;
        font-size: 0.85rem;
        margin-bottom: 2%;
    }

    footer {
        margin-left: 100px;
        margin-top: -185px;
    }
</style>

</html>