<?php
/**
 * Request Thesis Access Code
 */

require_once __DIR__ . '/../db_includes/db_connect.php';
require_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$thesis_id = isset($_POST['thesis_id']) ? intval($_POST['thesis_id']) : 0;

if ($thesis_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid thesis ID']);
    exit();
}

// Check if thesis exists
$thesis_check = $conn->prepare("SELECT title FROM thesis WHERE id = ?");
$thesis_check->bind_param("i", $thesis_id);
$thesis_check->execute();
$thesis_result = $thesis_check->get_result();

if ($thesis_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Thesis not found']);
    $thesis_check->close();
    exit();
}

$thesis_title = $thesis_result->fetch_assoc()['title'];
$thesis_check->close();

// Check if request already exists
$check_stmt = $conn->prepare("SELECT id FROM thesis_access WHERE user_id = ? AND thesis_id = ?");
$check_stmt->bind_param("ii", $_SESSION['user_id'], $thesis_id);
$check_stmt->execute();

if ($check_stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already requested access to this thesis']);
    $check_stmt->close();
    exit();
}
$check_stmt->close();

// Insert access request
$stmt = $conn->prepare("INSERT INTO thesis_access (user_id, thesis_id, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("ii", $_SESSION['user_id'], $thesis_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Access request submitted successfully! Please wait for admin approval.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit request']);
}

$stmt->close();
$conn->close();
?>
