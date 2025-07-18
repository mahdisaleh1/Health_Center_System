<?php
include '../config.php'; // Your database connection file

if (isset($_POST['doctor_id'])) {
    $doctor_id = intval($_POST['doctor_id']);

    $stmt = $con->prepare("SELECT available_day, TIME_FORMAT(available_from, '%H:%i') AS available_from, TIME_FORMAT(available_to, '%H:%i') AS available_to FROM doctor_availability WHERE doctor_id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $availability = [];
    while ($row = $result->fetch_assoc()) {
        $availability[] = $row;
    }

    echo json_encode(['success' => true, 'availability' => $availability]);

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>