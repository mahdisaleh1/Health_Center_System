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
    $email = $admin['email'];
    $password = $admin['password'];
    $username = $admin['username'];
    $phone = $admin['phone'];
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $usernames = $_POST['username'];
    $emails = $_POST['email'];
    $phones = $_POST['phonenb'];
    $psws = $_POST['psw'];
    if($username != $usernames || $email != $emails || $password != $psws || $phone != $phones){
        $id = $admin['user_id'];
        $sql = "UPDATE users SET email = '$emails', phone ='$phones', username = '$usernames', password = '$psws' WHERE user_id = '$id'";
        if ($con->query($sql) === TRUE) {
            echo '<script>alert("Changes saved successfuly! Please login again." );</script>';
            header("Refresh:0.11; url=../users/logout.php");
        }
    }
    else {
        echo '<script>alert("No changes has been occured!" );</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>My Profile - Lab Staff</title>

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
                    <li><a href="./labstaffdashboard.php" class="smoothScroll">Home</a></li>
                    <li><a href="./appointments.php" class="smoothScroll">Appointments</a></li>
                    <li><a href="./testsdetails.php" class="smoothScroll">Test name</a></li>
                    <li><a href="./notifications.php" class="smoothScroll">Notifications</a></li>
                    <li class="lidashboard"><a href="./staffprofile.php" class="smoothScroll">Profile</a></li>
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
                    <li><a href="./appointments.php">Appointments</a></li>
                    <li><a href="./testsdetails.php">Test name</a></li>
                    <li><a href="./notifications.php">Notifications</a></li>
                    <li class="lidashboard"><a href="./staffprofile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>If you want to update your work information, please check with your admin! (Time, department,..)</p>
            </header>
            <form action="./staffprofile.php" method="post">
                <div class="userinfo">
                    <h3>Personal Information</h3><br>
                    <label>Username:</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <span id="username-message">Username already exists</span><br>
                    <label for="email">Email Address:</label>
                    <input type="email" name="email" placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>" required>
                    <label for="phonenb">Phone Number:</label>
                    <input type="number" name="phonenb" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone); ?>" required>
                    <label for="psw">Password:</label>
                    <input type="password" name="psw" placeholder="Enter your password" value="<?php echo htmlspecialchars($password); ?>" onfocus="this.type='text'"
                        onblur="this.type='password'" required>
                    <button type="submit" name="submit">Update Profile</button>
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
    <script>
        $(document).ready(function() {
            let checkTimer;

            $('#username').on('input', function() {
                const username = $(this).val();

                clearTimeout(checkTimer); // Cancel previous timer if still running

                // Only check if username has 3+ characters
                if (username.length > 2) {
                    checkTimer = setTimeout(function() {
                        $.ajax({
                            url: './functionality/check_username.php',
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
        });
    </script>
</body>

<style>
    .lidashboard {
        text-decoration: underline;
    }
    #username-message {
        display: none;
        color: red;
        font-size: 0.85rem;
    }
    .userinfo h3 {
        margin-bottom: 2%;
    }

    .userinfo{
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
    .userinfo label {
        font-weight: 600;
        color: #333;
    }

    .userinfo input {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 3.5rem;
        margin-bottom: 2%;
    }
    .userinfo #username{
        margin-bottom: 0%;
    }
</style>

</html>