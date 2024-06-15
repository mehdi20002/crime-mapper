<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../tools/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drawing_data = $_POST['drawing_data'] ?? '';

    if ($drawing_data) {
        $stmt = $conn->prepare('INSERT INTO drawings (drawing_data) VALUES (?)');
        if ($stmt) {
            $stmt->bind_param('s', $drawing_data);
            if ($stmt->execute()) {
                echo 'Drawing saved successfully.';
            } else {
                echo 'Error saving drawing: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            echo 'Error preparing statement: ' . $conn->error;
        }
    } else {
        echo 'No drawing data received.';
    }
} else {
    echo 'Invalid request method.';
}
?>
