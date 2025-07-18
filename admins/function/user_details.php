<?php
include '../../config.php';
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
if (isset($_POST['save_doctor'])) {
    $doctor_id = $_POST['doctor_id'] ?? null;

    // 1. Get all existing availability IDs from DB
    $existing_ids = [];
    $res = mysqli_query($con, "SELECT id FROM doctor_availability WHERE doctor_id = $doctor_id");
    while ($row = mysqli_fetch_assoc($res)) {
        $existing_ids[] = $row['id'];
    }

    // 2. Track submitted IDs
    $submitted_ids = [];

    foreach ($_POST['availability_id'] as $index => $id) {
        $day = $_POST['available_day'][$index];
        $from = $_POST['available_from'][$index];
        $to = $_POST['available_to'][$index];

        if ($id === 'new') {
            // Insert new row
            $insert = "INSERT INTO doctor_availability (doctor_id, available_day, available_from, available_to)
                       VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($insert);
            $stmt->bind_param("isss", $doctor_id, $day, $from, $to);
            $stmt->execute();
        } else {
            // Update existing row
            $update = "UPDATE doctor_availability 
                       SET available_day = ?, available_from = ?, available_to = ?
                       WHERE id = ?";
            $stmt = $con->prepare($update);
            $stmt->bind_param("sssi", $day, $from, $to, $id);
            $stmt->execute();
            $submitted_ids[] = (int)$id; // Keep track of updated ones
        }
    }

    // 3. Delete rows that were not submitted
    $ids_to_delete = array_diff($existing_ids, $submitted_ids);
    if (!empty($ids_to_delete)) {
        $id_placeholders = implode(',', array_fill(0, count($ids_to_delete), '?'));
        $delete_sql = "DELETE FROM doctor_availability WHERE id IN ($id_placeholders)";
        $stmt = $con->prepare($delete_sql);
        $stmt->bind_param(str_repeat('i', count($ids_to_delete)), ...$ids_to_delete);
        $stmt->execute();
    }

    header("Location: ../allusers.php");
    exit();
}

if (isset($_POST['save_labstaff'])) {
    $labstaff_id = $_POST['labstaff_id'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $from = $_POST['available_from'];
    $to = $_POST['available_to'];

    $update = "UPDATE labstaff 
               SET department = ?, position = ?, available_from = ?, available_to = ?
               WHERE user_id = ?";

    $stmt = $con->prepare($update);
    $stmt->bind_param("ssssi", $department, $position, $from, $to, $labstaff_id);

    if ($stmt->execute()) {
        header("Refresh:0.11; url=../allusers.php");
        exit();
    } else {
        echo "Error updating labstaff: " . $stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <title>User details - Admin </title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Mahdi Saleh">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/animate.css">
    <link rel="stylesheet" href="../../css/owl.carousel.css">
    <link rel="stylesheet" href="../../css/owl.theme.default.min.css">
    <link rel="shortcut icon" href="../../images/icon.png" type="image/x-icon"> <!--ICON-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../../css/stylee.css">
    <link rel="stylesheet" href="../style.css">

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
                    <li><a href="../admindashboard.php" class="smoothScroll">Home</a></li>
                    <li><a href="../allusers.php" class="smoothScroll">Users</a></li>
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
                    <li><a href="../admindashboard.php">Dashboard</a></li>
                    <li><a href="../allusers.php">Users</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <section class="userDetails">
                <?php
                if (isset($_GET['id'])) {
                    $user_id = intval($_GET['id']);

                    // Fetch main user info
                    $user_query = mysqli_query($con, "SELECT * FROM users WHERE user_id = $user_id");
                    if (mysqli_num_rows($user_query) == 1) {
                        $user = mysqli_fetch_assoc($user_query);
                        echo "<h2>User Details</h2>";
                        echo "<p><strong>Username:</strong> {$user['username']}</p>";
                        echo "<p><strong>Email:</strong> {$user['email']}</p>";
                        echo "<p><strong>Phone:</strong> {$user['phone']}</p>";
                        echo "<p><strong>Role:</strong> {$user['role']}</p>";
                        echo "<p><strong>Status:</strong> {$user['status']}</p>";
                        // Role-specific info
                        $role = $user['role'];
                        if ($role == 'doctor') {
                            $details = mysqli_query($con, "
                                    SELECT d.*, s.specialty_name 
                                    FROM doctors d 
                                    JOIN specialty s ON d.specialty_id = s.specialty_id 
                                    WHERE d.user_id = $user_id
                                ");
                            if ($row = mysqli_fetch_assoc($details)) {
                                echo "<h3>Doctor Info</h3><br>";
                                echo "<p><strong>Specialty: </strong> {$row['specialty_name']}</p>";
                                //echo "<p><strong>Available Time: </strong> {$row['available_from']} - {$row['available_to']}</p>";
                                $doctor_id = $row['doctor_id'];
                                $sql = "SELECT * FROM doctor_availability WHERE doctor_id = $doctor_id";
                                $result = mysqli_query($con, $sql);
                                $availability = [];
                                while ($slot = mysqli_fetch_assoc($result)) {
                                    $availability[] = $slot;
                                }
                                echo "<input type='hidden' name='doctor_id' value='" . $doctor_id . "'>";
                                echo "<div id='staticView'>";
                                echo "<p><strong>Days Available:</strong> <a href='#' id='editBtn'><i class='fa fa-edit'> Edit </i></a><br>";
                                foreach ($availability as $slot) {
                                    echo "* " . $slot['available_day'] . ": " . $slot['available_from'] . " - " . $slot['available_to'] . "<br>";
                                }
                                echo "</p></div>";
                                // Editable Form
                                echo "<div class='editableform' id='editableform'>";
                                echo "<form action='user_details.php' method='post' id='editableForm'>";
                                echo "<div id='availabilityRows'>";
                                foreach ($availability as $slot) {
                                    echo "<div class='availabilityRow'>";
                                    echo "<input type='hidden' name='doctor_id' value='".$doctor_id."'>";
                                    echo "<input type='hidden' name='availability_id[]' value='{$slot['id']}'>";
                                    echo "<select name='available_day[]' required>
                                        <option value='Monday'" . ($slot['available_day'] == 'Monday' ? ' selected' : '') . ">Monday</option>
                                        <option value='Tuesday'" . ($slot['available_day'] == 'Tuesday' ? ' selected' : '') . ">Tuesday</option>
                                        <option value='Wednesday'" . ($slot['available_day'] == 'Wednesday' ? ' selected' : '') . ">Wednesday</option>
                                        <option value='Thursday'" . ($slot['available_day'] == 'Thursday' ? ' selected' : '') . ">Thursday</option>
                                        <option value='Friday'" . ($slot['available_day'] == 'Friday' ? ' selected' : '') . ">Friday</option>
                                        <option value='Saturday'" . ($slot['available_day'] == 'Saturday' ? ' selected' : '') . ">Saturday</option>
                                        <option value='Sunday'" . ($slot['available_day'] == 'Sunday' ? ' selected' : '') . ">Sunday</option>
                                    </select>";
                                    echo "<input type='time' name='available_from[]' value='{$slot['available_from']}' required>";
                                    echo "<input type='time' name='available_to[]' value='{$slot['available_to']}' required>";
                                    echo " <a href='#' class='removeRow'>  <i class='fa fa-trash'></i> Remove </a>";
                                    echo "</div><br>";
                                }
                                echo "</div>";
                                echo "<button type='button' id='addRow'>Add Availability</button><br><br>";
                                echo "<button type='submit' name='save_doctor'>Save</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                        } else if ($role == 'patient') {
                            $details = mysqli_query($con, "SELECT * FROM patients WHERE user_id = $user_id");
                            if ($row = mysqli_fetch_assoc($details)) {
                                echo "<h3>Patient Info</h3><br>";
                                echo "<p><strong>Date of Birth:</strong> {$row['dob']}</p>";
                                echo "<p><strong>Gender:</strong> {$row['gender']}</p>";
                                echo "<p><strong>Address:</strong> {$row['address']}</p>";
                                echo "<p><strong>Emergency Contact:</strong> {$row['emergency_contact']}</p>";
                            }
                        } else if ($role == 'labstaff') {
                            $details = mysqli_query($con, "SELECT * FROM labstaff WHERE user_id = $user_id");
                            if ($row = mysqli_fetch_assoc($details)) {
                                echo "<h3>Lab Staff Info</h3><br>";
                                echo "<div id='staticView'>";
                                echo "<a href='#' id='editbtn'><i class='fa fa-edit'> Edit </i></a><br>";
                                echo "<p><strong>Department:</strong> {$row['department']}</p>";
                                echo "<p><strong>Position:</strong> {$row['position']}</p>";
                                echo "<p><strong>Available from:</strong> {$row['available_from']}</p>";
                                echo "<p><strong>Available to:</strong> {$row['available_to']}</p>";
                                echo "</div>";
                ?>
                                <form action='user_details.php' method='post'>
                                    <div class='editableform' id='editableform'>
                                        <input type='hidden' name='labstaff_id' value='<?php echo htmlspecialchars($row['user_id']); ?>'>
                                        <label>Department: </label>
                                        <input type='text' name='department' value='<?php echo htmlspecialchars($row['department']); ?>' required> <br>
                                        <label>Position: </label>
                                        <input type='text' name='position' value='<?php echo htmlspecialchars($row['position']); ?>' required> <br>
                                        <label>Available From: </label>
                                        <input type='time' name='available_from' value='<?php echo htmlspecialchars($row['available_from']); ?>' required> <br>
                                        <label>Available To: </label>
                                        <input type='time' name='available_to' value='<?php echo htmlspecialchars($row['available_to']); ?>' required> <br>
                                        <button type='submit' name='save_labstaff'>Save</button>
                                    </div>
                                </form>
                <?php
                            }
                        } else {
                            echo "<p>No additional info for this role.</p>";
                        }
                    } else {
                        echo "<p>User not found.</p>";
                    }
                } else {
                    echo "<p>No user ID provided.</p>";
                    //header("Refresh:2.50; url=../allusers.php");
                }
                ?>
            </section>
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
    <script src="../../js/jquery.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.sticky.js"></script>
    <script src="../../js/jquery.stellar.min.js"></script>
    <script src="../../js/wow.min.js"></script>
    <script src="../../js/smoothscroll.js"></script>
    <script src="../../js/owl.carousel.min.js"></script>
    <script src="../../js/custom.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editBtn = document.getElementById("editbtn");
            if (editBtn) {
                editBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    const form = document.getElementById("editableform");
                    const staticView = document.getElementById("staticView");
                    form.style.display = "block";
                    staticView.style.display = "none";
                });
            }
        });


        document.addEventListener("DOMContentLoaded", function() {
            const editBtn = document.getElementById("editBtn");
            const form = document.getElementById("editableform");
            const staticView = document.getElementById("staticView");

            if (editBtn) {
                editBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    form.style.display = "block";
                    staticView.style.display = "none";
                });
            }

            const addRowBtn = document.getElementById("addRow");
            const container = document.getElementById("availabilityRows");

            addRowBtn.addEventListener("click", function() {
                const div = document.createElement("div");
                div.className = "availabilityRow";
                div.innerHTML = `
            <input type='hidden' name='availability_id[]' value='new'>
            <select name='available_day[]' required>
                <option value='Monday'>Monday</option>
                <option value='Tuesday'>Tuesday</option>
                <option value='Wednesday'>Wednesday</option>
                <option value='Thursday'>Thursday</option>
                <option value='Friday'>Friday</option>
                <option value='Saturday'>Saturday</option>
                <option value='Sunday'>Sunday</option>
            </select>
            <input type='time' name='available_from[]' required>
            <input type='time' name='available_to[]' required>
            <button type='button' class='removeRow'>Remove</button>
        `;
                container.appendChild(div);
            });

            container.addEventListener("click", function(e) {
                if (e.target.classList.contains("removeRow")) {
                    e.target.parentElement.remove();
                }
            });
        });
        /*document.querySelectorAll(".editbtn").forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const form = this.closest(".userDetails").querySelector(".editableform");
                const staticView = this.closest(".userDetails").querySelector("#staticView");
                form.style.display = "block";
                staticView.style.display = "none";
            });
        });*/
    </script>
</body>
<style>
    .editableform {
        display: none;
    }

    footer {
        margin-left: 100px;
        margin-top: -55px;
    }
</style>

</html>