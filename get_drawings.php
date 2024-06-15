<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../tools/config.php';

$query = 'SELECT drawing_data FROM drawings';
$result = $conn->query($query);

if ($result) {
    $drawings = [];
    while ($row = $result->fetch_assoc()) {
        $drawings[] = json_decode($row['drawing_data'], true);
    }

    header('Content-Type: application/json');
    echo json_encode($drawings);
} else {
    echo json_encode(['error' => 'Failed to retrieve drawings: ' . $conn->error]);
}
?>
