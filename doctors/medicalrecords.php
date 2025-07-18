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
if (isset($_POST['add_medical_record'])) {
    $patient_ids = $_POST['patient_id'];
    $doctor_ids = $_POST['doctor_id'];
    $record = $_POST['medicaldetails'];
    $date = $_POST['creationdate'];
    $insert = "INSERT INTO medicalrecords (patient_id, doctor_id, record_details, date, status)
                VALUES (?, ?, ?, ?, 'visible')";
    $stmt = $con->prepare($insert);
    $stmt->bind_param("iiss", $patient_ids, $doctor_ids, $record, $date);
    if ($stmt->execute()) {
        echo '<script>alert("Medical record added successufly!")</script>';
        header("Refresh:0.11; url=./medicalrecords.php");
        exit();
    } else {
        echo "Error adding payment: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Medical Records - Doctor</title>

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
                    <li class="lidashboard"><a href="./medicalrecords.php" class="smoothScroll">Medical Records</a></li>
                    <li><a href="./messages.php" class="smoothScroll">Messages</a></li>
                    <li><a href="./labtests.php" class="smoothScroll">Lab Test</a></li>
                    <li><a href="./prescriptions.php" class="smoothScroll">Prescriptions</a></li>
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
                    <li class="lidashboard"><a href="./medicalrecords.php">Medical Records</a></li>
                    <li><a href="./messages.php">Messages</a></li>
                    <li><a href="./labtests.php">Lab Test</a></li>
                    <li><a href="./prescriptions.php">Prescriptions</a></li>
                    <li><a href="./doctorprofile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>Medical records are added for each patient!</p>
            </header>

            <form action="./medicalrecords.php" method="post">
                <?php
                if (isset($_GET['id'])) {
                    $patient_id = intval($_GET['id']);  //PATIENT ID
                    $doctor_user_id = $_SESSION['user_id'];
                    $query = "SELECT * FROM doctors WHERE user_id = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("i", $doctor_user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $doctor = $result->fetch_assoc();
                    $doctor_new_id = $doctor['doctor_id']; //DOCTOR ID

                    $sql = "SELECT fullname FROM patients WHERE patient_id = ?";
                    $stmtt = $con->prepare($sql);
                    $stmtt->bind_param("i", $patient_id);
                    $stmtt->execute();
                    $resultt = $stmtt->get_result();
                    $patient = $resultt->fetch_assoc();
                    $patient_fullname = $patient['fullname'];
                ?>
                    <div class="medicaldiv">
                        <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                        <input type="hidden" name="doctor_id" value="<?php echo $doctor_new_id; ?>">

                        <label>Patient Full Name:</label>
                        <input type="text" name="patient_name" value="<?php echo $patient_fullname; ?>" readonly>

                        <label>Doctor Full Name:</label>
                        <input type="text" name="doctor_name" value="<?php echo $doctor['doctor_name']; ?>" readonly>

                        <label>Patient Medical Record:</label>
                        <textarea name="medicaldetails" placeholder="Write records here" required></textarea>

                        <label>Date of Creation:</label>
                        <input type="date" name="creationdate" id="creationdate" required>
                        <button type="submit" name="add_medical_record">Add Record</button>
                    </div>

                <?php
                } else {
                ?>
                    <div class="searchinglot">
                        <input type="text" id="searchinp" class="searchinp" placeholder="Search tests by name" />
                        <button id="clear-btn" class="clear-btn" style="display: none;"><i class="bx bx-x"></i></button>
                    </div>
                    <div class="allrecords">
                        <table border="0" id="myTable" class="table table-stripped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Record ID</th>
                                    <th>Patient Name</th>
                                    <th>Details</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                $user_id = $_SESSION['user_id'];
                                $query = "SELECT * FROM doctors WHERE user_id = ?";
                                $stmt = $con->prepare($query);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $doctor = $result->fetch_assoc();
                                $doctor_id = $doctor['doctor_id'];
                                $query = "
                                    SELECT 
                                        medicalrecords.*, 
                                        patients.fullname
                                    FROM medicalrecords
                                    JOIN patients ON medicalrecords.patient_id = patients.patient_id
                                    WHERE medicalrecords.doctor_id = $doctor_id
                                ";
                                $result = mysqli_query($con, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                    <th><a <a class='userdetails' href='./record_details.php?id={$row['record_id']}'>><i class='fa fa-plus'></i></a></th>
                                    <td>{$row['record_id']}</td>
                                    <td>{$row['fullname']}</td>
                                    <td>{$row['record_details']}</td>
                                    <td>{$row['date']}</td>
                                    <td>{$row['status']}</td>
                                </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>
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
        //Search input 
        document.getElementById("searchinp").addEventListener("keyup", function() {
            const query = this.value;
            fetch("./functionality/search_medical.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `search=${encodeURIComponent(query)}`,
                })
                .then((response) => response.text())
                .then((data) => {
                    // Update the product container with the search results
                    document.getElementById("tableBody").innerHTML = data;
                })
                .catch((error) => console.error("Error:", error));
        });
        const searchInput = document.getElementById("searchinp");
        const clearButton = document.getElementById("clear-btn");

        // Show or hide the "X" button based on input value
        searchInput.addEventListener("input", function() {
            clearButton.style.display = this.value ? "block" : "none";
        });

        // Clear the input and trigger the product reload
        clearButton.addEventListener("click", function() {
            searchInput.value = "";
            clearButton.style.display = "none";

            // Reload all products by triggering the search with an empty value
            fetch("./functionality/search_medical.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: "search=",
                })
                .then((response) => response.text())
                .then((data) => {
                    document.getElementById("tableBody").innerHTML = data;
                })
                .catch((error) => console.error("Error:", error));
        });



        //MAKE SURE THE uSER WILL CHOOSE ONLY NEXT DAYS NOT PREVIOUS
        const today = new Date().toISOString().split('T')[0];
        // Set the min attribute of the date input
        document.getElementById("creationdate").setAttribute("min", today);
    </script>
</body>

<style>
    .lidashboard {
        text-decoration: underline;
    }


    .medicaldiv h3 {
        margin-bottom: 2%;
    }

    .medicaldiv {
        display: flex;
        flex-direction: column;
        padding: 2rem;
        background-color: #fff;
        max-height: 100vh;
        height: auto;
        font-size: larger;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .medicaldiv label {
        font-weight: 600;
        color: #333;
    }

    .medicaldiv input {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 4rem;
        margin-bottom: 2%;
    }

    .medicaldiv textarea {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 5.5rem;
        margin-bottom: 2%;
    }

    #searchinp {
        border-radius: 7px;
        border: 0.25px solid #000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        width: 100%;
        max-width: 100%;
        padding: 12px;
        /* Space for the "X" button */
    }

    .searchinglot .clear-btn {
        position: absolute;
        right: 30px;
        top: 23%;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 30px;
        color: #000;
        cursor: pointer;
        display: none;
        /* Initially hidden */
    }

    .searchinglot .clear-btn:hover {
        color: #000;
    }

    #searchinp:focus {
        border: 2px solid #000;
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