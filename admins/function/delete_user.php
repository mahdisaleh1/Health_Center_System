<?php
include '../../config.php';
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = $data['id'];

    // First, get the user's role
    $roleQuery = "SELECT role FROM users WHERE user_id = ?";
    if ($stmt = $con->prepare($roleQuery)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($role);
        if ($stmt->fetch()) {
            $stmt->close();

            // Delete from the respective table first
            $deleteSubTable = false;
            if ($role === 'patient') {
                $deleteSubTable = $con->prepare("DELETE FROM patients WHERE user_id = ?");
            } else if ($role === 'doctor') {
                // First delete doctor availability records
                $deleteAvailability = $con->prepare("DELETE FROM doctor_availability WHERE doctor_id = ?");
                if ($deleteAvailability) {
                    $deleteAvailability->bind_param("i", $id);
                    $deleteAvailability->execute();
                    $deleteAvailability->close();
                }
                $deleteSubTable = $con->prepare("DELETE FROM doctors WHERE user_id = ?");
            } else if ($role === 'labstaff') {
                $deleteSubTable = $con->prepare("DELETE FROM labstaff WHERE user_id = ?");
            }

            if ($deleteSubTable) {
                $deleteSubTable->bind_param("i", $id);
                if ($deleteSubTable->execute()) {
                    $deleteSubTable->close();

                    // Now delete from users table
                    $deleteUser = $con->prepare("DELETE FROM users WHERE user_id = ?");
                    if ($deleteUser) {
                        $deleteUser->bind_param("i", $id);
                        if ($deleteUser->execute()) {
                            echo json_encode(['success' => true]);
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Failed to delete from users']);
                        }
                        $deleteUser->close();
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Failed to prepare user delete']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to delete from role table']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Unknown role or failed to prepare role delete']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'User not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare role fetch']);
    }

    $con->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
}
?>