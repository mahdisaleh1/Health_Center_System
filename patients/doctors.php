<?php
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Health Center System</title>

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
    <!-- PRE LOADER -->
    <section class="preloader">
        <div class="spinner">

            <span class="spinner-rotate"></span>

        </div>
    </section>

    <!-- HEADER -->
    <header>
        <div class="container">
            <div class="row">

                <div class="col-md-4 col-sm-5">
                    <p>Welcome to a Professional Health Care</p>
                </div>

                <div class="col-md-8 col-sm-7 text-align-right">
                    <span class="phone-icon"><i class="fa fa-phone"></i>+961 3 123 456</span>
                    <span class="date-icon"><i class="fa fa-calendar-plus-o"></i> 6:00 AM - 10:00 PM (Mon-Fri)</span>
                    <span class="email-icon"><i class="fa fa-envelope-o"></i> <a href="#">info@company.com</a></span>
                </div>

            </div>
        </div>
    </header>


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
                    <li><a href="../index.php" class="smoothScroll">Home</a></li>
                    <li><a href="../index.php#about" class="smoothScroll">About Us</a></li>
                    <li><a href="../index.php#team" class="smoothScroll">Doctors</a></li>
                    <li><a href="../index.php#news" class="smoothScroll">News</a></li>
                    <li><a href="../index.php#google-map" class="smoothScroll">Contact</a></li>
                    <li><a href="../users/login.php">Log In</a></li>
                    <li class="appointment-btn"><a href="../appointments/appointment.php">Make an appointment</a></li>
                </ul>
            </div>

        </div>
    </section>

    <section class="alldoctors">
        <main class="content">
            <header>
                <p>Please select a specialty to see doctors</p>
            </header>
        </main>
        <form action="./doctors.php" method="post">


            <div class="doctors">
                <label for="specialty">Choose Specialty:</label>
                <select name="specialty" id="specialty">
                    <option value="0">Select specialty</option>
                    <?php
                    $sql = "SELECT * FROM specialty";
                    $result = $con->query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value = '" . $row['specialty_id'] . "'>" . htmlspecialchars($row['specialty_name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No specialties found!</option>";
                    }
                    ?>
                </select>

                <label>Choose a Doctor:</label>
                <select name="doctorselect" id="doctorselect">
                    <option value="0">Select doctor</option>
                </select>

                <table border="0" id="myTable" class="table table-stripped">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Available From</th>
                            <th>Available To</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan='3'>Select a doctor</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </section>


    <!-- GOOGLE MAP -->
    <section id="google-map">
        <!-- How to change your own map point
            1. Go to Google Maps
            2. Click on your location point
            3. Click "Share" and choose "Embed map" tab
            4. Copy only URL and paste it within the src="" field below
	-->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26653.371930766334!2d35.46732295317009!3d33.379641498696145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151e94a5d7a1ac15%3A0x23629441e0514c4c!2sNabatieh!5e0!3m2!1sen!2slb!4v1741612492083!5m2!1sen!2slb" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>

    </section>

    <footer data-stellar-background-ratio="5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="footer-thumb">
                        <h4 class="wow fadeInUp" data-wow-delay="0.4s">Contact Info</h4>
                        <p>Fusce at libero iaculis, venenatis augue quis, pharetra lorem. Curabitur ut dolor eu elit consequat ultricies.</p>
                        <div class="contact-info">
                            <p><i class="fa fa-phone"></i> +961 3 123 456</p>
                            <p><i class="fa fa-envelope-o"></i> <a href="#">info@company.com</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="footer-thumb">
                        <div class="opening-hours">
                            <h4 class="wow fadeInUp" data-wow-delay="0.4s">Opening Hours</h4>
                            <p>Monday - Friday <span>06:00 AM - 10:00 PM</span></p>
                            <p>Saturday <span>09:00 AM - 08:00 PM</span></p>
                            <p>Sunday <span>Closed</span></p>
                        </div>

                        <ul class="social-icon">
                            <li><a href="#" class="fa fa-facebook-square" attr="facebook icon"></a></li>
                            <li><a href="#" class="fa fa-twitter"></a></li>
                            <li><a href="#" class="fa fa-instagram"></a></li>
                        </ul>
                    </div>
                </div>
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
                    <div class="col-md-2 col-sm-2 text-align-center">
                        <div class="angle-up-btn">
                            <a href="#top" class="smoothScroll wow fadeInUp" data-wow-delay="1.2s"><i class="fa fa-angle-up"></i></a>
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
        //GET DOCTORS AS SPECIALTY SELECTED
        document.getElementById('specialty').addEventListener('change', function() {
            const specialtyId = this.value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "./functionality/get_doctors_by_specialty.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("doctorselect").innerHTML = this.responseText;
                }
            };

            xhr.send("specialty_id=" + specialtyId);
        }); //END OF GET DOCTORS AS SPECIALTY

        // New: Get doctor availability
        document.getElementById('doctorselect').addEventListener('change', function() {
            const doctorId = this.value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "./functionality/get_availability_by_doctor.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (this.status === 200) {
                    document.querySelector("#myTable tbody").innerHTML = this.responseText;
                }
            };

            xhr.send("doctor_id=" + doctorId);
        });
    </script>
</body>
<style>
    .content {
        margin-left: 0;
        padding: 20px;
        flex: 1;
    }

    .content header {
        justify-content: center;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        width: 100%;
    }

    .content header p {
        font-size: large;
    }

    #google-map {
        margin-top: 200px;
    }

    #myTable td {
        margin-top: 15px;
        font-size: large;
        text-align: center;
        justify-content: center;
        margin-bottom: 50px;
    }

    .doctors {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .doctors select {
        max-width: 90%;
        margin-left: 5%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .doctors label{
        margin-left: 4.25%;
        font-size: large;
        font-weight: bold;
        margin-top: 10px;
    }
</style>

</html>