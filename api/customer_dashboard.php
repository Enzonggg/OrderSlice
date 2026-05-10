<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$stmt = $conn->prepare("
    SELECT r.*, c.name as cake_name, c.image_url, c.price
    FROM reservations r
    LEFT JOIN cakes c ON r.cake_id = c.id
    WHERE r.customer_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$_SESSION['customer_id']]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'success', 'data' => $reservations]);
?>
