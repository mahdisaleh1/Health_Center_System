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

    <title>All Users - Admin </title>

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
                    <li class="lidashboard"><a href="./allusers.php" class="smoothScroll">Users</a></li>
                    <li><a href="./doctorspecialty.php" class="smoothScroll">Doctor's specialty</a></li>
                    <li><a href="./testname.php" class="smoothScroll">Test name</a></li>
                    <li><a href="./notification.php" class="smoothScroll">Notifications</a></li>
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
                        <li class="lidashboard"><a href="./allusers.php">Users</a></li>
                        <li><a href="./doctorspecialty.php">Doctor's specialty</a></li>
                        <li><a href="./testname.php">Test name</a></li>
                        <li><a href="./notification.php">Notifications</a></li>
                        <li><a href="./profile.php">Profile</a></li>
                        <li><a href="../users/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="content">
                <header>
                    <p>You can Activate, Modify and Create Accounts! <a href="./adduser.php" class="addUser">Click here</a> to add new user</p>
                </header>

                <section class="users">
                    <div class="first-cont">
                        <select id="chooseuser">
                            <option value="1">Show All Users</option>
                            <option value="2">Doctor's accounts</option>
                            <option value="3">Patient's accounts</option>
                            <option value="4">Lab Staff's accounts</option>
                        </select>
                        <div class="searchinglot">
                            <input type="text" id="searchinp" class="searchinp" placeholder="Search orders..." />
                            <button id="clear-btn" class="clear-btn" style="display: none;"><i class="bx bx-x"></i></button>
                        </div>
                    </div>
                    <div class="second-cont">
                        <form action="allusers.php" method="post" enctype="multipart/form-data">
                            <div style="overflow-x:auto;">


                                <table class="table table-stripped" id="myTable">
                                    <thead>
                                        <th>Details</th>
                                        <th>User ID</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Active</th>

                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        $query = "SELECT * FROM users WHERE role = 'doctor' OR role = 'patient' OR role = 'labstaff'";
                                        $result = mysqli_query($con, $query);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                                <th><a <a class='userdetails' href='./function_user/user_details.php?id={$row['user_id']}'>><i class='fa fa-plus'></i></a></th>
                                                <th>{$row['user_id']}</th>
                                                <td>{$row['email']}</td>
                                                <td>{$row['phone']}</td>
                                                <td>{$row['role']}</td>
                                                <td class='status-cell' data-id='{$row['user_id']}' data-status='{$row['status']}'>{$row['status']}</td>
                                                <td><a class='updateactivation' data-id='" . $row['user_id'] . "' data-status='" . $row['status'] . "'><i class='fa fa-edit'></i></a>
                                                </td>
        </tr>";
                                        }
                                        //<a class='deletebtn' data-id='" . $row['user_id'] . "'><i class='fa fa-trash'></i></a>
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
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

</body>
<script>
    //delete btn
    $(document).on('click', '.deletebtn', function() {
        const productId = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this user?')) {
            fetch('./function_user/delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: productId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('row-' + productId).remove();
                        alert('User deleted successfully!');
                    } else {
                        alert('Error deleting user.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });

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
                url: './function_user/update_status.php',
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



    //for updating table of choosen option from select
    document.getElementById("chooseuser").addEventListener("change", function() {
        let selectedValue = this.value;

        // Make an AJAX request to fetch data
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./function_user/fetchallusers.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (this.status == 200) {
                document.getElementById("tableBody").innerHTML = this.responseText;
            }
        };

        xhr.send("selectvalue=" + selectedValue);
    });

    //Search input 
    document.getElementById("searchinp").addEventListener("keyup", function() {
        const query = this.value;
        fetch("./function_user/search_user.php", {
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
        fetch("./function_user/search_user.php", {
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
<style>
    .addUser {
        padding: 0.25rem 0.5rem;
        font-size: 2rem;
        color: #fff;
        background: #333;
        text-decoration: none;
        border-radius: 25px;
        transition: background 0.3s ease;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    }

    .lidashboard {
        text-decoration: underline;
    }

    .first-cont {
        display: flex;
        align-items: center;
        /* Aligns items vertically */
        gap: 10px;
        /* Adds space between the select and search bar */
        width: 100%;
    }

    #chooseuser {
        flex: 0 0 auto;
        padding: 8px;
    }

    .searchinglot {
        flex: 1;
        position: relative;
        display: inline-block;
        width: 100%;
    }

    #searchinp {
        border: 1px solid #000;
        background-color: #fff;
        width: 100%;
        max-width: 90%;
        margin-left: 2%;
        padding: 10px;
        /* Space for the "X" button */
    }

    .searchinglot .clear-btn {
        position: absolute;
        right: 85px;
        top: 60%;
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

    @media (max-width: 768px) {
        .addUser {
            padding: 0.25rem;
        font-size: 1rem;
        }
    }
</style>

</html>