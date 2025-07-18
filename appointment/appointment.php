<!DOCTYPE html>
<html lang="en">

<head>

    <title>Appointment - Health Medical Center</title>

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
                    <li><a href="./appdoctor.php" class="smoothScroll">Appointment for doctor</a></li>
                    <li><a href="./apptest.php" class="smoothScroll">Appointment for test</a></li>
                </ul>
            </div>
        </div>
    </section>


    <!-- HOME -->
<h1>Welcome dear to health center system!</h1>
    <div class="dashcontainer">
        <div class="choosecontainer">
            
            <div class="cards">
                <div class="card">
                    <h3>Appointment with doctor</h3>
                    <p><a href="./appdoctor.php">take appointment with best doctors.</a></p>
                    <a href="./appdoctor.php"><button type="button">Take doctor appointment</button></a>
                </div>
                <div class="card">
                    <h3>Appointment for test</h3>
                    <p><a href="./apptest.php">Take appointment for tests in our medical center.</a></p>
                    <a href="./apptest.php"><button type="button">Take test appointment</button></a>
                </div>
            </div>
        </div>
        <div class="askai">
            <h2>Ask about your symptoms</h2>
            <form id="symptomForm">
                <label>Describe your symptoms:</label>
                <textarea name="symptoms" id="symptoms" rows="4" cols="50" placeholder="e.g., chest pain, shortness of breath"></textarea><br>
                <button type="submit">Check Specialty</button>
            </form>

            <div id="response" style="margin-top:20px; font-weight:bold;"></div>
        </div>
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
        $('#symptomForm').on('submit', function(e) {
            e.preventDefault();
            let symptoms = $('#symptoms').val();

            $.ajax({
                url: 'process_symptoms.php',
                method: 'POST',
                data: {
                    symptoms: symptoms
                },
                success: function(response) {
                    $('#response').html(response);
                }
            });
        });
    </script>
</body>
<style>
    .dashcontainer {
        min-height: 100vh;
        height: 100%;
        background: url(../images/news-image2.jpg) no-repeat center center/cover;
    }

    .askai {
        margin-top: 0;
        margin-left: 5%;
        width: 90%;
        background-color: #fff;
        padding: 20px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .askai label {
        font-weight: 800;
        font-size: medium;
        color: #333;
    }

    textarea {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        font-size: 1.5rem;
        transition: border-color 0.3s ease;
        width: 100%;
        height: 10rem;
        margin-bottom: 2%;
    }

    .askai button {
        background-color: #333;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        display: block;
        width: 100%;
        font-size: large;
    }
</style>

</html>