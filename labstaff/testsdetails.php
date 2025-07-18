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
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Tests Details - Lab Staff</title>

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
                    <li><a href="./appointments.php" class="smoothScroll">Appointments</a></li>
                    <li class="lidashboard"><a href="./testsdetails.php" class="smoothScroll">Test name</a></li>
                    <li><a href="./notifications.php" class="smoothScroll">Notifications</a></li>
                    <li><a href="./staffprofile.php" class="smoothScroll">Profile</a></li>
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
                        <li><a href="./labstaffdashboard.php">Dashboard</a></li>
                        <li><a href="./appointments.php">Appointments</a></li>
                        <li class="lidashboard"><a href="./testsdetails.php">Test name</a></li>
                        <li><a href="./notifications.php">Notifications</a></li>
                        <li><a href="./staffprofile.php">Profile</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <div class="first-content">
                    <div class="searchinglot">
                        <input type="text" id="searchinp" class="searchinp" placeholder="Search tests by name" />
                        <button id="clear-btn" class="clear-btn" style="display: none;"><i class="bx bx-x"></i></button>
                    </div>
                    <header>
                        <p>You're the best Lab Staff :)</p>
                    </header>
                </div>

                <section class="cards">
                    <div class="specialtyTable">
                        <h3>Check Available Tests</h3><br>
                        <table border="0" id="myTable" class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>Test ID</th>
                                    <th>Test Name</th>
                                    <th>Test Amount</th>
                                    <th>Status</th>

                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                $query = "SELECT * FROM testname";
                                $result = mysqli_query($con, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                    <th>{$row['test_id']}</th>
                                    <td>{$row['test_name']}</td>
                                    <td>$" . "{$row['test_amount']}</td>
                                    <td class='status-cell' data-id='{$row['test_id']}' data-status='{$row['status']}'>{$row['status']}</td>
                                </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </section>
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
    <script>
        //Search input 
        document.getElementById("searchinp").addEventListener("keyup", function() {
            const query = this.value;
            fetch("./functionality/search_test.php", {
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
            fetch("./functionality/search_test.php", {
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
    </script>
</body>

<style>
    .content header {
        width: 50%;
    }

    .lidashboard {
        text-decoration: underline;
    }

    .searchinglot {
        flex: 1;
        position: relative;
        display: inline-block;
        width: 100%;
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