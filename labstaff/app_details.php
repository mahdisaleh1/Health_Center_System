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
    if ($role !== 'labstaff') {
        header("Location: ../users/login.php");
    }
}


if (isset($_POST['save_app_changes'])) {
    $appointment_id = $_POST['appointment_id'];
    $labtest_id = $_POST['labtest_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $assigned_labstaff_id = $_POST['assigned_labstaff_id'];
    $status = $_POST['status'];
    $update = "UPDATE appointments 
            SET appointment_date = ?, appointment_time = ?, status = ? 
            WHERE appointment_id = ?";
    $stmt = $con->prepare($update);
    $stmt->bind_param("sssi", $appointment_date, $appointment_time, $status, $appointment_id);
    if ($stmt->execute()) {
        $appointment_id = $_POST['appointment_id'];
        $labtest_id = $_POST['labtest_id'];
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];
        $assigned_labstaff_id = $_POST['assigned_labstaff_id'];
        $status = $_POST['status'];
        $updatee = "UPDATE labtests SET assigned_labstaff_id = ?, status = ? WHERE appointment_id = ?";
        $stmtt = $con->prepare($updatee);
        $stmtt->bind_param("isi", $assigned_labstaff_id, $status, $appointment_id);
        if ($stmtt->execute()) {
            echo '<script>alert("Lab Appointment has been updated!")</script>';
            header("Refresh:0.11; url=./appointments.php");
            exit();
        } else {
            echo "Error updating lab test appointment: " . $stmt->error;
        }
    } else {
        echo "Error updating appointment: " . $stmt->error;
    }
}

if (isset($_POST['save_payment_changes'])) {
    $payment_id = $_POST['payment_id'];
    $payment_status = $_POST['updateStatusPayment'];
    $update = "UPDATE payments SET status = ? WHERE payment_id = ?";
    $stmt = $con->prepare($update);
    $stmt->bind_param("si", $payment_status, $payment_id);
    if ($stmt->execute()) {
        echo '<script>alert("Payment with id = ' . $payment_id . ' has been updated!")</script>';
        header("Refresh:0.11; url=./appointments.php");
        exit();
    } else {
        echo "Error updating labstaff: " . $stmt->error;
    }
}


if (isset($_POST['add_new_payment'])) {
    $app_pay_id = $_POST['appointment_id_payment'];
    $amount = $_POST['amountpaid'];
    $add_payment_status = $_POST['addPaymentStatus'];
    $date = $_POST['paymentnewdate'];

    $insert = "INSERT INTO payments (appointment_id, amount, payment_date, status)
                VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($insert);
    $stmt->bind_param("isss", $app_pay_id, $amount, $date, $add_payment_status);
    if ($stmt->execute()) {
        echo '<script>alert("Payment added successufly!")</script>';
        header("Refresh:0.11; url=./appointments.php");
        exit();
    } else {
        echo "Error adding payment: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Appointment - Lab Staff</title>

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
                    <li><a href="./labstaffdashboard.php" class="smoothScroll">Home</a></li>
                    <li class="lidashboard"><a href="./appointments.php" class="smoothScroll">Appointments</a></li>
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
                    <li><a href="./labstaffdashboard.php">Dashboard</a></li>
                    <li class="lidashboard"><a href="./appointments.php">Appointments</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>Want to upload test result? <a href="#resultdiv" id="uploadA">Click here</a>.</p>
            </header>

            <div class="second-content">
                <?php
                if (isset($_GET['id'])) {
                    $appointment_id = intval($_GET['id']);
                    // Fetch main user info
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
                        echo "<a href='#' id='editbtn'><i class='fa fa-edit'> Edit </i></a>";
                        echo "<p><strong>Lab Test ID:</strong> {$appointment['labtest_id']}</p>";
                        echo "<p><strong>Appointment ID:</strong> {$appointment['appointment_id']}</p>";
                        //echo "<p><strong>Patient ID:</strong> {$appointment['patient_id']}</p>";
                        echo "<p><strong>Patient Name:</strong> {$appointment['fullname']}</p>";
                        echo "<p><strong>Doctor Name:</strong> {$appointment['doctor_name']}</p>";
                        echo "<p><strong>Test Name:</strong> {$appointment['test_name']}</p>";


                        echo "<div class='appointmentInfo' id='appointmentInfo'>";
                        echo "<p><strong>Appointment Date:</strong> {$appointment['appointment_date']}</p>";
                        echo "<p><strong>Appointment Time:</strong> {$appointment['appointment_time']}</p>";
                        echo "<p><strong>Assigned Lab Staff Username:</strong> " . ($appointment['labstaff_username'] ?? 'N/A') . "</p>";
                        echo "<p><strong>Status:</strong> {$appointment['status']}</p>";
                        echo "</div>";
                        $labstaff_query = mysqli_query($con, "
                        SELECT labstaff.labstaff_id, users.username
                        FROM labstaff
                        JOIN users ON labstaff.user_id = users.user_id
                    ");
                        echo "<form action='app_details.php' method='POST'>";
                        echo "<div class='editableDiv' id='editableDiv'>";
                        echo "<input type='hidden' name='appointment_id' value='{$appointment['appointment_id']}'>";
                        echo "<input type='hidden' name='labtest_id' value='{$appointment['labtest_id']}'>";
                        echo "<p><strong>Appointment Date:</strong> <input type='date' name='appointment_date' value='{$appointment['appointment_date']}' required></p>";
                        echo "<p><strong>Appointment Time:</strong> <input type='time' name='appointment_time' value='{$appointment['appointment_time']}' required></p>";
                        echo "<p><strong>Assigned Lab Staff:</strong> <select name='assigned_labstaff_id' required>";
                        while ($labstaff = mysqli_fetch_assoc($labstaff_query)) {
                            $selected = ($labstaff['labstaff_id'] == $appointment['assigned_labstaff_id']) ? 'selected' : '';
                            echo "<option value='{$labstaff['labstaff_id']}' $selected>{$labstaff['username']}</option>";
                        }
                        echo "</select></p>";
                        echo "<p><strong>Status:</strong>
                                <select name='status' required>
                                    <option value='pending' " . ($appointment['status'] == 'pending' ? 'selected' : '') . ">Pending</option>
                                    <option value='confirmed' " . ($appointment['status'] == 'confirmed' ? 'selected' : '') . ">Cancelled</option>
                                    <option value='completed' " . ($appointment['status'] == 'completed' ? 'selected' : '') . ">Completed</option>
                                    <option value='canceled' " . ($appointment['status'] == 'canceled' ? 'selected' : '') . ">Cancelled</option>
                                </select>
                            </p>";
                        echo "<button type='submit' name='save_app_changes'>Save Changes</button>";
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";

                        $app_id = $appointment["appointment_id"];
                        echo "<div class='paymentdiv'>";
                        echo "<h2>Payment Details</h2>";
                        $payment_query = mysqli_query($con, "SELECT * FROM payments WHERE appointment_id = '$app_id'");
                        if (mysqli_num_rows($payment_query) > 0) {
                            $payment = mysqli_fetch_assoc($payment_query);
                            echo "<a href='#' id='editPayment'><i class='fa fa-edit'> Edit </i></a>";

                            echo "<p><strong>Payment ID:</strong> {$payment['payment_id']}</p>";
                            echo "<p><strong>Appointment ID:</strong> {$payment['appointment_id']}</p>";
                            echo "<p><strong>Amount Paid: </strong>$ {$payment['amount']}</p>";
                            echo "<p><strong>Payment Date:</strong> {$payment['payment_date']}</p>";
                            echo "<p id='statuscell'><strong>Payment Status:</strong> {$payment['status']}</p>";

                            echo "<form action='app_details.php' method='post'>";
                            echo "<div id='editablePayment'>";
                            echo "<input type='hidden' name='payment_id' value='{$payment['payment_id']}'>";
                            echo "<p><strong>Status:</strong>
                                <select name='updateStatusPayment' required>
                                    <option value='paid' " . ($payment['status'] == 'paid' ? 'selected' : '') . ">Paid</option>
                                    <option value='unpaid' " . ($payment['status'] == 'unpaid' ? 'selected' : '') . ">Unpaid</option>
                                    <option value='refunded' " . ($payment['status'] == 'refunded' ? 'selected' : '') . ">Refunded</option>
                                </select>
                            </p>";
                            echo "<button type='submit' name='save_payment_changes'>Save Changes</button>";
                            echo "</div>";
                            echo "</form>";
                        } else {
                            echo "<p id='nopay'>No payment added</p>";
                            echo "<a href='#' id='addnewpayment'><i class='fa fa-edit'> Add New Payment </i></a>";

                            echo "<form action='app_details.php' method='post'>";
                            echo "<div class='addPaymentDiv' id='addPaymentDiv'>";
                            echo "<input type='hidden' name='appointment_id_payment' value='{$appointment['appointment_id']}'>";
                            echo "<p><strong>Amount Paid: </strong></p>";
                            echo "<p><input type='number' name='amountpaid' placeholder='Enter amount paid..' required></p>";
                            echo "<p><strong>Status:</strong>
                                <select id='statusPayment' name='addPaymentStatus' required>
                                    <option value='paid' " . ($appointment['status'] == 'Pending' ? 'selected' : '') . ">Paid</option>
                                    <option value='unpaid' " . ($appointment['status'] == 'Completed' ? 'selected' : '') . ">Unpaid</option>
                                    <option value='refunded' " . ($appointment['status'] == 'Cancelled' ? 'selected' : '') . ">Refunded</option>
                                </select>
                            </p>";
                            echo "<p><strong>Date of payment: </strong></p>";
                            echo "<p><input type='date' name='paymentnewdate'><p>";
                            echo "<button type='submit' name='add_new_payment'>Add payment</button>";
                            echo "</div>";
                            echo "</form>";
                        }
                        echo "</div>";
                    }
                }
                ?>
            </div>
            <div class="resultdiv" id="resultdiv">
                <h2>Result Details</h2>
                <?php
                $lab_test_id = $appointment['labtest_id'];
                $results_query = mysqli_query($con, "SELECT * FROM labresults WHERE labtest_id = '$lab_test_id'");
                if (mysqli_num_rows($results_query) > 0) {
                    $result = mysqli_fetch_assoc($results_query);
                    echo "<p>Uploaded by:" . $result['uploaded_by'] . "</p>";
                    echo "<p>Comments:" . $result['comments'] . "</p>";
                    echo "<p>Uploaded at:" . $result['uploaded_at'] . "</p>";
                    $filepath = '../uploads/labresults/' . $result['result_file'];
                    if (pathinfo($filepath, PATHINFO_EXTENSION)) {
                        echo "<a href='$filepath' target='_blank'>View Result</a>";
                    } else {
                        echo "<img src='$filepath' alt='Lab Result' width='300'>";
                    }
                } else {
                ?>
                    <p>No results added. Fill his form to add the result!</p>
                    <form action="./functionality/upload_lab_result.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="labtest_id" value="<?= htmlspecialchars($lab_test_id) ?>">
                        <input type="hidden" name="assignedlabstaff_id" value="<?= htmlspecialchars($labstaff_id) ?>">
                        <label for="result_file">Upload Result (PDF/Image):</label><br>
                        <input type="file" name="result_file" accept=".pdf,image/*" required><br><br>

                        <button type="submit" name="upload_result">Upload Result</button>
                    </form>
                <?php
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
        //ADD PAYMENT 
        document.getElementById("addnewpayment").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("addPaymentDiv").style.display = "block";
            document.getElementById("addnewpayment").style.display = "none";
            document.getElementById("nopay").style.display = "none";
        });
    </script>
    <script>
        //EDIT APPOINTMENT INFO
        document.getElementById("editbtn").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("editableDiv").style.display = "block";
            document.getElementById("appointmentInfo").style.display = "none";
            document.getElementById("editbtn").style.display = "none";
        });

        //EDIT PAYMENT ADDED STATUS
        document.getElementById("editPayment").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("editablePayment").style.display = "block";
            document.getElementById("statuscell").style.display = "none";
            document.getElementById("editPayment").style.display = "none";
        });



        //Search input 
        document.getElementById("searchinp").addEventListener("keyup", function() {
            const query = this.value;
            fetch("./functionality/search_appointment.php", {
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
            fetch("./functionality/search_appointment.php", {
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



        //IF USER SELECT A DATE FROM datePicker
        document.getElementById("datePicker").addEventListener("change", function() {
            const selectedDate = this.value;

            fetch("./functionality/filter_appointment_by_date.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: "date=" + encodeURIComponent(selectedDate),
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("tableBody").innerHTML = data;
                })
                .catch(error => console.error("Error:", error));
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

    .searchinglot {
        flex: 1;
        position: relative;
        display: inline-block;
        width: 100%;
    }

    #datePicker {
        border-radius: 7px;
        border: 0.25px solid #000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        padding: 12px;
        height: 45px;
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
        right: 10px;
        top: 40%;
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