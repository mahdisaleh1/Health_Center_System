<?php
include '../../config.php'; // Include your database connection
session_start();

if (isset($_POST['selectvalue'])) {
    $type = $_POST['selectvalue'];

    if ($type == "1") {
        // Fetch Doctor Appointments
        $query = "SELECT * FROM users WHERE role = 'doctor' OR role = 'patient' OR role = 'labstaff'";
    } else if ($type == "2") {
        $query = "SELECT * FROM users WHERE role = 'doctor'";
    } else if ($type == "3") {
        $query = "SELECT * FROM users WHERE role = 'patient'";
    } else if ($type == "4") {
        $query = "SELECT * FROM users WHERE role = 'labstaff'";
    }
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
        <th><a <a class='userdetails' href='./function_user/user_details.php?id={$row['user_id']}'>><i class='fa fa-plus'></i></a></th>
            <th>{$row['user_id']}</th>
            <td>{$row['username']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['password']}</td>
            <td>{$row['role']}</td>
            <td class='status-cell' data-id='{$row['user_id']}' data-status='{$row['status']}'>{$row['status']}</td>
            <td><a class='deletebtn' data-id= '" . $row['user_id'] . "'><i class='fa fa-trash'></i></a>
                <a class='updateactivation' data-id='" . $row['user_id'] . "' data-status='" . $row['status'] . "'><i class='fa fa-edit'></i></a>
            </td>
        </tr>";
    }
}
?>
