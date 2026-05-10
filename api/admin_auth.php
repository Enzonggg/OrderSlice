<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }
} elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success']);
} elseif ($action === 'check') {
    if (isset($_SESSION['admin_id'])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    }
}
?>
