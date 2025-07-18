<?php
include '../../config.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $labstaff_id = $_POST['labstaff_id'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $from = $_POST['available_from'];
    $to = $_POST['available_to'];
    $update = "UPDATE labstaff 
               SET department = '$department', position = '$position', 
                   available_from = '$from', available_to = '$to' 
               WHERE user_id = $labstaff_id";
    $stmt = $con->prepare($update);
    if ($stmt->execute()) {
        //echo "<script>alert('Doctor availability updated.'); location.reload();</script>";
        header("Refresh:0.11; url=./allusers.php");
    }
}
?>