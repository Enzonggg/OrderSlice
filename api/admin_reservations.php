<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $conn->query("
        SELECT r.*, c.name as cake_name, cu.name as customer_name, cu.phone as customer_phone
        FROM reservations r 
        LEFT JOIN cakes c ON r.cake_id = c.id 
        LEFT JOIN customers cu ON r.customer_id = cu.id
        ORDER BY r.created_at DESC
    ");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $reservations]);
} elseif ($method === 'POST') {
    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? '';
    
    $valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (in_array($status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $id])) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
    }
}
?>
