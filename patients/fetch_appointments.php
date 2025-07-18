<?php

include '../config.php'; // Include your database connection
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM patients WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $patient_id = $admin['patient_id'];
    

if(isset($_POST['selectvalue'])) {
    $type = $_POST['selectvalue'];
    
    if($type == "1") {
        // Fetch Doctor Appointments
        //$query = "SELECT * FROM appointments WHERE type = 'doctor' AND patient_id = '$patient_id'";
        $query = "
        SELECT appointments.*,
        doctors.doctor_name
        FROM appointments
        INNER JOIN doctors ON appointments.doctor_id = doctors.doctor_id
        WHERE appointments.type='doctor' AND appointments.patient_id = '$patient_id'
        ";
    } else if($type == 2) {
        // Fetch Laboratory Appointments
        $query = "
        SELECT appointments.*,
        labtests.*,
        doctors.*
        FROM labtests 
        INNER JOIN doctors ON labtests.doctor_id = doctors.doctor_id
        INNER JOIN appointments ON labtests.appointment_id = appointments.appointment_id
        WHERE labtests.patient_id = '$patient_id'";
    }

    $result = mysqli_query($con, $query);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <th><a class='userdetails' href='./app_details.php?id={$row['appointment_id']}'>><i class='fa fa-plus'></i></a></th>
                                        <th>{$row['appointment_id']}</th>
                                        <th>{$row['doctor_name']}</th>
                                        <td>{$row['appointment_date']}</td>
                                        <td>{$row['appointment_time']}</td>
                                        <td>{$row['status']}</td>
                                        </tr>";
                                }
                            }
                            else {
                                echo "<tr><td colspan='6'>No appointments</td></tr>";
                            }
}
}
?>