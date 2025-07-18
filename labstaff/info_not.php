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
    if ($role !== 'labstaff') {
        header("Location: ../users/login.php");
    }
}

if (isset($_GET['notification_id'])) {
    $notification_id = intval($_GET['notification_id']); // Safe cast to int
} else {
    echo "No notification selected.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver = $_POST['userid']; //receiver
    $message = $_POST['sendmessage'];
    $user_id = $_SESSION['user_id']; //sender
    $patients_query = "INSERT INTO notifications (user_id, receiver_id, message, read_status) VALUES (?, ?, ?, 'unread')";
    $stmt = $con->prepare($patients_query);
    $stmt->bind_param("iis", $user_id, $receiver, $message);
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

    <title>Message Details - Lab Staff</title>

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
                    <li><a href="./notifications.php" class="smoothScroll">Notifications</a></li>
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
                    <li><a href="./notifications.php">Notifications</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>The message has been marked as read!</p>
            </header>
            <div class="notfdetails">
                <h2>Notification Details</h2>
                <?php
                $query = "
                    SELECT notifications.*, users.username 
                    FROM notifications 
                    JOIN users ON notifications.user_id = users.user_id 
                    WHERE notifications.notification_id = $notification_id
                ";
                $result = mysqli_query($con, $query);

                if ($row = mysqli_fetch_assoc($result)) {
                    echo "<input type='hidden' value='" . $notification_id . "'>";
                    echo "<p><strong>Notification ID: </strong>" . htmlspecialchars($notification_id) . "</p>";
                    echo "<p><strong>Sent by:</strong> " . htmlspecialchars($row['username']) . "</p>";
                    echo "<p><strong>Sent at:</strong> " . htmlspecialchars($row['created_at']) . "</p>";
                    echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars($row['message'])) . "</p>";

                    // Optional: Mark the notification as read
                    $update = "UPDATE notifications SET read_status = 'read' WHERE notification_id = $notification_id";
                    mysqli_query($con, $update);
                } else {
                    echo "Notification not found.";
                }
                ?>
            </div>
            <h2>Reply on this message?</h2>
            <div class="replycontainer">
                <form action="info_not.php" method="post">
                    <div class="receiver_info">
                        <!-- Receiver ID-->
                        <input type="hidden" name="userid" value="<?php echo htmlspecialchars($row['user_id']); ?>">
                        <!-- Sender in this div is the admin-->
                        <label for="username">Receiver username:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" readonly>
                        <br>
                        <label>Message:</label>
                        <textarea name="sendmessage" placeholder="Enter your message here..." required></textarea>
                    </div>
                    <div class="buttons">
                        <button type="submit" name="submit">Send message</button>
                    </div>
                </form>
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

</body>

<style>
    .lidashboard {
        text-decoration: underline;
    }

    .content header {
        width: 100%;
    }

    .replycontainer {
        background-color: #ffffff;
        margin-left: 5%;
        width: 100%;
        max-width: 90%;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        height: auto;
    }

    .receiver_info {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
        max-width: 100%;
    }

    label {
        font-size: large;
        text-align: left;
        color: #333;
        font-weight: bold;
    }

    input,
    textarea {
        font-size: large;
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border 0.3s ease-in-out;
    }

    button {
        margin-top: 1%;
        width: 50%;
        font-size: large;
    }
</style>

</html>