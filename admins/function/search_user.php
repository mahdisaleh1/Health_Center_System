<?php
include '../../config.php';
session_start();
if (isset($_POST['search'])) {
    $search = $con->real_escape_string($_POST['search']);
    $sql = "SELECT * FROM users
    WHERE (username LIKE '%$search%' OR phone LIKE '%$search%' OR email LIKE '%$search%')
     AND role IN ('doctor', 'patient', 'labstaff')";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
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
                <td><a class='deletebtn' data-id='" . $row['user_id'] . "'><i class='fa fa-trash'></i></a>
                    <a class='updateactivation' data-id='" . $row['user_id'] . "' data-status='" . $row['status'] . "'><i class='fa fa-edit'></i></a>
                </td>
            </tr>";
        }
    }
}
