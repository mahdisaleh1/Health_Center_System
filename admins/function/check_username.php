<?php
include '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $username = $con->real_escape_string($_POST['username']);

    $sql = "SELECT user_id FROM users WHERE username = '$username' LIMIT 1";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "not_exists";
    }
}
?>