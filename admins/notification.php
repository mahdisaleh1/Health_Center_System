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
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Nofitications - Admin</title>

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
                    <li><a href="./admindashboard.php" class="smoothScroll">Home</a></li>
                    <li><a href="./allusers.php" class="smoothScroll">Users</a></li>
                    <li><a href="./doctorspecialty.php" class="smoothScroll">Doctor's specialty</a></li>
                    <li><a href="./testname.php" class="smoothScroll">Test name</a></li>
                    <li class="lidashboard"><a href="./notification.php" class="smoothScroll">Notifications</a></li>
                    <li><a href="./profile.php" class="smoothScroll">Profile</a></li>
                    <li><a href="../users/logout.php" class="smoothScroll">Logout</a></li>
                </ul>
            </div>
        </div>
    </section>


    <!-- HOME -->
    <form action="" method="">
        <div class="dashcontainer">
            <aside class="sidebar">
                <nav>
                    <ul>
                        <li><a href="./admindashboard.php">Dashboard</a></li>
                        <li><a href="./allusers.php">Users</a></li>
                        <li><a href="./doctorspecialty.php">Doctor's specialty</a></li>
                        <li><a href="./testname.php">Test name</a></li>
                        <li class="lidashboard"><a href="./notification.php">Notifications</a></li>
                        <li><a href="./profile.php">Profile</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <header>
                    <p><a href="./sendmessage.php">Send message?</a></p>
                </header>
                <div class="unreadedmsg">
                    <h3>Status: Unread Messages</h3>
                    <section class="cards">
                        <?php
                        $query = "
                        SELECT notifications.*, users.username 
                        FROM notifications 
                        JOIN users ON notifications.user_id = users.user_id 
                        WHERE notifications.read_status = 'unread' AND notifications.receiver_id = ?
                    ";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        //$result = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='card'>";
                            echo "<h3><strong>Sent by:</strong> " . $row['username'] . "</h3>";
                            echo "<p><strong>Sent at:</strong> " . $row['created_at'] . "</p>";
                            echo '<p style="
                                display: -webkit-box;
                                -webkit-line-clamp: 3;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                "><strong>Message:</strong> ' . $row['message'] . '</p>';
                            echo "<a href='info_not.php?notification_id=" . urlencode($row['notification_id']) . "'><button type='button'>Check details</button></a>";
                            echo "</div>";
                        }
                        ?>
                    </section>
                </div>
                <div class="readed_msg">
                    <h3>Status: Read Messages</h3>
                    <section class="cards">
                        <?php
                        $query = "
                            SELECT notifications.*, users.username 
                            FROM notifications 
                            JOIN users ON notifications.user_id = users.user_id 
                            WHERE notifications.read_status = 'read' AND notifications.receiver_id = ?
                        ";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='card'>";
                            echo "<h3><strong>Sent by:</strong> " . $row['username'] . "</h3>";
                            echo "<p><strong>Sent at:</strong> " . $row['created_at'] . "</p>";
                            echo '<p style="
                                display: -webkit-box;
                                -webkit-line-clamp: 3;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                "><strong>Message:</strong> ' . $row['message'] . '</p>';
                            echo "<a href='info_not.php?notification_id=" . urlencode($row['notification_id']) . "'><button type='button'>Check details</button></a>";
                            echo "</div>";
                        }
                        ?>
                    </section>
                </div>
            </main>
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
    .lidashboard {
        text-decoration: underline;
    }

    .unreadedmsg h3 {
        margin-bottom: 3%;
    }

    .readed_msg h3 {
        margin-bottom: 3%;
        margin-top: 4%;
    }
</style>

</html>