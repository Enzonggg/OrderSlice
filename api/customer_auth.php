<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'register') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO customers (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $hash, $phone, $address])) {
            $_SESSION['customer_id'] = $conn->lastInsertId();
            $_SESSION['customer_name'] = $name;
            echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Integrity constraint violation
            echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
        }
    }
} elseif ($action === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer && password_verify($password, $customer['password'])) {
        $_SESSION['customer_id'] = $customer['id'];
        $_SESSION['customer_name'] = $customer['name'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }
} elseif ($action === 'logout') {
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    echo json_encode(['status' => 'success']);
} elseif ($action === 'check') {
    if (isset($_SESSION['customer_id'])) {
        $stmt = $conn->prepare("SELECT name, phone, address FROM customers WHERE id = ?");
        $stmt->execute([$_SESSION['customer_id']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success', 
            'logged_in' => true, 
            'name' => $_SESSION['customer_name'],
            'customer' => $customer
        ]);
    } else {
        echo json_encode(['status' => 'success', 'logged_in' => false]);
    }
}
?>
