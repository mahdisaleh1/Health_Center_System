<?php
include '../config.php';

$user_input = strtolower(trim($_POST['symptoms']));
$matched_specialty = '';
$highest_matches = 0;

$sql = "SELECT specialty_name, keywords FROM specialty";
$result = $con->query($sql);

while ($row = $result->fetch_assoc()) {
    $keywords = explode(',', strtolower($row['keywords']));
    $match_count = 0;

    foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        if (strpos($user_input, $keyword) !== false) {
            $match_count++;
        }
    }

    if ($match_count > $highest_matches) {
        $highest_matches = $match_count;
        $matched_specialty = $row['specialty_name'];
    }
}

if ($matched_specialty != '') {
    echo "You should see a doctor with <strong>$matched_specialty</strong> specialty.";
} else {
    echo "Sorry, we couldn't determine a specialty. Please describe your symptoms in more detail.";
}

$con->close();
?>