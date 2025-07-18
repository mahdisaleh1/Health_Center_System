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

if (isset($_POST['send_admin_message'])) {
    $sender_id = $_SESSION['user_id'];
    $message = $_POST['drmessagedetails'];
    $patients_query = "INSERT INTO notifications (user_id, receiver_id, message, read_status) VALUES (?, '1', ?, 'unread')";
    $stmt = $con->prepare($patients_query);
    $stmt->bind_param("is", $user_id, $message);
    if ($stmt->execute()) {
        echo '<script>alert("Message has been sent!" );</script>';
        header("Refresh:0.11; url=./messages.php");
    } else {
        echo "Error sending your message! Try again later.";
    }
}


if(isset($_POST['send_labstaff_message'])){
    $labstaff_id = $_POST['selectlabstaff'];
    $query = "SELECT * FROM labstaff WHERE labstaff_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $labstaff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $labstaff = $result->fetch_assoc();
    $labstaff_user_id = $labstaff['user_id'];
    $sender_id = $_SESSION['user_id'];
    $message = $_POST['labstaffmessagedetails'];
    $patients_query = "INSERT INTO notifications (user_id, receiver_id, message, read_status) VALUES (?, ?, ?, 'unread')";
    $stmt = $con->prepare($patients_query);
    $stmt->bind_param("iis", $user_id, $labstaff_user_id, $message);
    if ($stmt->execute()) {
        echo '<script>alert("Notification has been sent!" );</script>';
        header("Refresh:0.11; url=./notification.php");
    } else {
        echo "Error sending your message! Try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Send Message - Doctor</title>

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
                    <li class="lidashboard"><a href="./messages.php" class="smoothScroll">Messages</a></li>
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
                    <li><a href="./medicalrecords.php">Medical Records</a></li>
                    <li class="lidashboard"><a href="./messages.php">Messages</a></li>
                    <li><a href="./labtests.php">Lab Test</a></li>
                    <li><a href="./prescriptions.php">Prescriptions</a></li>
                    <li><a href="./doctorprofile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <a href="./sendmessage.php">
                    <p>Fill all the informations!</p>
                </a>
            </header>
            <div class="select-content">
                <label>Select Receiver Type:</label>
                <select id="selectreceiver">
                    <option value="">Select receiver type</option>
                    <option value="admin">Admin</option>
                    <option value="labstaff">Lab Staff</option>
                </select>
            </div>
            <div class="sendmessage">
                <div id="choosereceivertype">
                    <h4>Select receiver type!</h4>
                </div>
                <div class="sendfordoctor" id="sendfordoctor" style="display: none;">
                    <form action="./sendmessage.php" method="post">
                        <h4>Send Message for Admin</h4>
                        <label>Write your message here:</label>
                        <textarea name="drmessagedetails" placeholder="Write your message here" required></textarea>
                        <button type="submit" name="send_admin_message">Send message</button>
                    </form>
                </div>
                <div class="sendforlabstaff" id="sendforlabstaff" style="display: none;">
                    <form action="./sendmessage.php" method="post">
                        <h4>Send Message for a Lab Staff</h4>
                        <label for="selectlabstaff">Select Lab Staff Receiver:</label>
                        <select id="selectlabstaff" name="selectlabstaff">
                            <?php
                            $stmt = $con->prepare("SELECT labstaff.labstaff_id, users.username FROM labstaff JOIN users ON labstaff.user_id = users.user_id");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            echo "<option value='0'>Choose lab staff</option>";
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['labstaff_id'] . "'>" . htmlspecialchars($row['username']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No labstaff accounts found</option>";
                            }
                            ?>
                        </select>
                        <label>Write your message here:</label>
                        <textarea name="labstaffmessagedetails" placeholder="Write your message here" required></textarea>
                        <button type="submit" name="send_labstaff_message">Send message</button>
                    </form>
                </div>
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
        document.getElementById('selectreceiver').addEventListener('change', function() {
            const selected = this.value;
            const doctorDiv = document.getElementById('sendfordoctor');
            const adminDiv = document.getElementById('sendforlabstaff');
            const chooseTypeDiv = document.getElementById('choosereceivertype');

            // Hide all message sections initially
            doctorDiv.style.display = 'none';
            adminDiv.style.display = 'none';
            chooseTypeDiv.style.display = 'none';

            // Show the corresponding section
            if (selected === 'admin') {
                doctorDiv.style.display = 'flex';
            } else if (selected === 'labstaff') {
                adminDiv.style.display = 'flex';
            } else {
                chooseTypeDiv.style.display = 'flex';
                alert("Please choose receiver type!");
            }
        });
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

    .select-content {
        width: 100%;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding-left: 8px;
    }

    .select-content select {
        width: 85%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    .content .sendmessage {
        background-color: #ffffff;
        margin-top: 3%;
        width: 100%;
        max-width: 90%;
        margin-left: 5%;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        justify-items: center;
        height: auto;
    }

    .sendmessage h4 {
        text-align: center;
    }

    .sendfordoctor {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
    }

    .sendforlabstaff {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
    }

    .sendmessage label {
        margin-top: 25px;
        text-align: left;
        color: #333;
        font-weight: bold;
    }

    .sendfordoctor select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .sendforlabstaff select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .sendfordoctor textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        height: 100px;
    }

    .sendforlabstaff textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        height: 100px;
    }

    .sendfordoctor button {
        margin-top: 1%;
    }

    .sendforlabstaff button {
        margin-top: 1%;
    }

    @media (max-width: 768px) {
        .content .sendmessage {
            max-width: 100%;
            margin-left: 0%;
            margin-top: 20px;
        }

        .select-content select {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-left: 5px;
            margin-bottom: 5px;
        }

    }
</style>

</html>