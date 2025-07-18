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

    <title>My Appointments - Health Medical Center</title>

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
    <div class="dashcontainer">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="./patientdashboard.php">Dashboard</a></li>
                    <li><a href="../appointments/appointment.php">Book Appointment</a></li>
                    <li class="lidashboard"><a href="./patapp.php">My Appointments</a></li>
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
                <p>Your appointments (uncompleted) can be modified.</p>
            </header>
            <form action="patapp.php" method="post">
                <div class="appointmentscontainer">
                    <select class="selectvalue" name="selectvalue" id="appointmentType">
                        <option value="1">Doctor Appointments</option>
                        <option value="2">Laboratory Appointments</option>
                    </select>
                    <div style="overflow-x:auto;">
                        <table class="myTable" id="myTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Appointment ID</th>
                                    <th>Doctor Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                $query = "
                            SELECT appointments.*,
                            doctors.doctor_name
                            FROM appointments
                            INNER JOIN doctors ON appointments.doctor_id = doctors.doctor_id
                            WHERE appointments.type='doctor' AND appointments.patient_id = '$patient_id'
                            ";
                                $result = mysqli_query($con, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                        <th><a class='userdetails' href='./app_details.php?id={$row['appointment_id']}'>><i class='fa fa-plus'></i></a></th>
                                        <th>{$row['appointment_id']}</th>
                                        <th>{$row['doctor_name']}</th>
                                        <td>{$row['appointment_date']}</td>
                                        <td>{$row['appointment_time']}</td>
                                        <td>{$row['status']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No appointments</td></tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
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
<script>
    document.getElementById("appointmentType").addEventListener("change", function() {
        let selectedValue = this.value;

        // Make an AJAX request to fetch data
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_appointments.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (this.status == 200) {
                document.getElementById("tableBody").innerHTML = this.responseText;
            }
        };

        xhr.send("selectvalue=" + selectedValue);
    });
</script>
<style>
    footer {
        margin-top: 0;
    }

    .lidashboard {
        text-decoration: underline;
    }
    

    @media (max-width: 768px) {
        #myTable {
            width: 100%;
            border-collapse: collapse;
        }

        #myTable thead {
            display: none;
        }

        #myTable tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #fff;
        }

        #myTable td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }

        #myTable td:last-child {
            border-bottom: none;
        }

        #myTable td::before {
            content: attr(data-label);
            font-weight: bold;
            flex: 1;
            padding-right: 10px;
            color: #555;
        }
    }
</style>

</html>