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
    $specialty_name = $_POST['specialtyname'];
    $testamount = $_POST['testamount'];
    $sql = "INSERT INTO testname (test_name, test_amount, status) VALUES (?, ?, 'active')";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $specialty_name, $testamount);
    if ($stmt->execute()) {
        echo '<script>alert("Test has been added!" );</script>';
        header("Refresh:0.11; url=./testname.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Tests Names - Admin </title>

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
                    <li><a href="./allusers.php" class="smoothScroll">Users</a></li>
                    <li><a href="./doctorspecialty.php" class="smoothScroll">Doctor's specialty</a></li>
                    <li class="lidashboard"><a href="./testname.php" class="smoothScroll">Test name</a></li>
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
                    <li><a href="./allusers.php">Users</a></li>
                    <li><a href="./doctorspecialty.php">Doctor's specialty</a></li>
                    <li class="lidashboard"><a href="./testname.php">Test name</a></li>
                    <li><a href="./notification.php">Notifications</a></li>
                    <li><a href="./profile.php">Profile</a></li>
                    <li><a href="../users/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <p>You can create and modify tests names! Tests names cannot be deleted! (active -> visible)</p>
            </header>
            <form action="./testname.php" method="post">
                <div class="doctorspecialty">
                    <h3>Create New Test Name</h3><br>
                    <label>Test Name:</label>
                    <input type="text" name="specialtyname" id="specialty" placeholder="Enter test name" required>
                    <label>Test Amount: ($)</label>
                    <input type="number" name="testamount" id="specialty" placeholder="Enter test amount" required>
                    <button type="submit" name="submit">Add Test Record</button>
                </div><br><br>
                <div class="specialtyTable">
                    <h3>Check Available Tests</h3><br>
                    <table border="0" id="myTable" class="table table-stripped">
                        <thead>
                            <tr>
                                <th>Test ID</th>
                                <th>Test Name</th>
                                <th>Test Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM testname";
                            $result = mysqli_query($con, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <th>{$row['test_id']}</th>
                                    <td>{$row['test_name']}</td>
                                    <td>$"."{$row['test_amount']}</td>
                                    <td class='status-cell' data-id='{$row['test_id']}' data-status='{$row['status']}'>{$row['status']}</td>
                                    <td><a class='updateactivation' data-id='" . $row['test_id'] . "' data-status='" . $row['status'] . "'><i class='fa fa-edit'></i></a></td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
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
    //for updating activation
    $(document).on('click', '.updateactivation', function() {
        var $this = $(this);
        var currentStatus = $this.data('status');
        var userId = $this.data('id');
        var $cell = $(`.status-cell[data-id='${userId}']`);

        // Create a dropdown
        var dropdown = `
      <select class="statusDropdown">
        <option value="">Choose status</option>
        <option value="Active" ${currentStatus === 'Active' ? 'selected' : ''}>Active</option>
        <option value="Inactive" ${currentStatus === 'Inactive' ? 'selected' : ''}>Inactive</option>
      </select>
    `;

        $cell.html(dropdown);

        // Handle change
        $('.statusDropdown').change(function() {
            var newStatus = $(this).val();

            $.ajax({
                url: './function_specialty/update_test_name_status.php',
                type: 'POST',
                data: {
                    id: userId,
                    status: newStatus
                },
                success: function(response) {
                    // Replace dropdown with updated text
                    $('.statusDropdown').replaceWith(`
                    <span class="updateactivation" data-id="${userId}" data-status="${newStatus}">${newStatus}</span>
                `);
                }
            });
        });
    });
</script>
</body>
<style>
    .lidashboard {
        text-decoration: underline;
    }

    #myTable {
        width: 100%;
        max-width: 100%;
        height: auto;
        margin-top: 25px;
    }

    #myTable td,
    th {
        font-size: large;
        text-align: center;
        justify-content: center;
    }

    #mytable tbody {
        margin-top: 50px;
    }

    .doctorspecialty {
        background-color: #ffffff;
        padding: 2rem;
        margin: 2rem auto;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 100%;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: larger;
    }

    .specialtyTable {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .doctorspecialty label {
        color: #555;
        margin-top: 1%;
    }

    .doctorspecialty input[type="text"], input[type="number"] {
        width: 100%;
        padding: 1.5rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s;
    }

    .doctorspecialty button[type="submit"] {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 1.5rem;
    }

    .doctorspecialty input[type="text"]:focus {
        outline: none;
        border-color: #007bff;
    }

    footer {
        margin-left: 100px;
        margin-top: auto;
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