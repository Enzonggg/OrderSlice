<?php
require_once 'config.php';

try {
    // Admins table
    $conn->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Insert default admin if not exists
    $stmt = $conn->query("SELECT * FROM `admins` WHERE `username` = 'admin'");
    if ($stmt->rowCount() == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->exec("INSERT INTO `admins` (`username`, `password`) VALUES ('admin', '$hash')");
    }

    // Cakes table
    $conn->exec("CREATE TABLE IF NOT EXISTS `cakes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `description` text NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `image_url` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Customers table
    $conn->exec("CREATE TABLE IF NOT EXISTS `customers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `email` varchar(100) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `phone` varchar(20) NOT NULL,
        `address` text NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // We will drop reservations if we are restructuring it to ensure clean state
    $conn->exec("DROP TABLE IF EXISTS `reservations`");

    // Reservations table (updated with customer_id)
    $conn->exec("CREATE TABLE IF NOT EXISTS `reservations` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `customer_id` int(11) NOT NULL,
        `cake_id` int(11) NOT NULL,
        `delivery_address` text NOT NULL,
        `reservation_date` date NOT NULL,
        `reservation_time` time NOT NULL,
        `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`cake_id`) REFERENCES `cakes`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    echo json_encode(["status" => "success", "message" => "Database updated with Customer schema successfully!"]);
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
