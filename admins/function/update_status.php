<?php
include '../../config.php';
session_start();

if(isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $con->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        echo "Status updated.";
    } else {
        echo "No change or error.";
    }
    $stmt->close();
}
?>