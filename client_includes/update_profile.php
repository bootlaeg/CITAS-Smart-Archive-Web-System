<?php
/**
 * Update User Profile Handler
 */

require_once __DIR__ . '/../db_includes/db_connect.php';
require_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = sanitize_input($_POST['full_name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$address = sanitize_input($_POST['address'] ?? '');
$contact_number = sanitize_input($_POST['contact_number'] ?? '');
$course = sanitize_input($_POST['course'] ?? '');
$year_level = sanitize_input($_POST['year_level'] ?? '');

// Validation
$errors = [];
if (empty($full_name)) $errors[] = 'Full name is required';
if (empty($email)) $errors[] = 'Email is required';
if (empty($address)) $errors[] = 'Address is required';
if (empty($contact_number)) $errors[] = 'Contact number is required';
if (empty($course)) $errors[] = 'Course is required';
if (empty($year_level)) $errors[] = 'Year level is required';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit();
}

// Check if email is already used by another user
$check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$check_stmt->bind_param("si", $email, $user_id);
$check_stmt->execute();

if ($check_stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email is already in use by another account']);
    $check_stmt->close();
    exit();
}
$check_stmt->close();

// Update profile
$stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, address = ?, contact_number = ?, course = ?, year_level = ? WHERE id = ?");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ssssssi", $full_name, $email, $address, $contact_number, $course, $year_level, $user_id);

if ($stmt->execute()) {
    // Update session variables
    $_SESSION['full_name'] = $full_name;
    
    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully!'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
