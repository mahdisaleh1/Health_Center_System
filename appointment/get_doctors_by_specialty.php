<?php
include '../config.php'; // change to your actual DB connection file

if (isset($_POST['specialty_id'])) {
    $specialty_id = intval($_POST['specialty_id']);

    if ($specialty_id === 0) {
        echo "<option value='0'>Select doctor</option>";
        exit;
    }

    $stmt = $con->prepare("SELECT * FROM doctors WHERE specialty_id = ?");
    $stmt->bind_param("i", $specialty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value='0'>Select doctor</option>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['doctor_id'] . "'>" . htmlspecialchars($row['doctor_name']) . "</option>";
        }
    } else {
        echo "<option value=''>No doctors found</option>";
    }

    $stmt->close();
    $con->close();
}
?>