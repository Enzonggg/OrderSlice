<?php
require_once 'config.php';
header('Content-Type: application/json');

$stmt = $conn->query("SELECT * FROM cakes ORDER BY created_at DESC");
$cakes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'success', 'data' => $cakes]);
?>
