<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in to make a reservation.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $cake_id = $_POST['cake_id'] ?? 0;
    $delivery_address = $_POST['delivery_address'] ?? '';
    $reservation_date = $_POST['reservation_date'] ?? '';
    $reservation_time = $_POST['reservation_time'] ?? '';

    if (empty($delivery_address) || empty($cake_id) || empty($reservation_date) || empty($reservation_time)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reservations (customer_id, cake_id, delivery_address, reservation_date, reservation_time, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    
    if ($stmt->execute([$customer_id, $cake_id, $delivery_address, $reservation_date, $reservation_time])) {
        echo json_encode(['status' => 'success', 'message' => 'Reservation submitted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit reservation.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
