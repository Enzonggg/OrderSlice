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
    $stmt = $conn->query("SELECT * FROM cakes ORDER BY created_at DESC");
    $cakes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Since images are now stored locally, prepend the root directory if it's a relative path.
    // In our JS we reference it directly. If it starts with 'uploads/', then it works from the root (index.php and dashboard.php).
    // In admin/dashboard.php, since it's inside /admin/, it might need '../' prepended on the frontend.
    // Let's ensure the frontend gets the absolute or relative path correctly. We'll prepend '../' on the frontend for admin if needed, or just let frontend handle it.
    // Our JS fetch is in assets/js/, but executes from index.php (root) and dashboard.php (root), and admin/dashboard.php.
    // To make it safe, we'll store 'uploads/cakes/filename.jpg' and the frontend will just use it as is if it's relative.
    // Wait, admin is in admin/dashboard.php, so 'uploads/...' will look for 'admin/uploads/...'.
    // We should return '/cake-reservation/uploads/...' or let the JS prepend '../' if in admin.
    // Actually, in `admin.js`, let's just make sure it handles the image path correctly.
    // Storing relative path 'uploads/cakes/xxx.png' is fine.
    
    echo json_encode(['status' => 'success', 'data' => $cakes]);
} elseif ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? 0;
        
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $file_name = uniqid() . '_' . basename(preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['image']['name']));
            $upload_dir = '../uploads/cakes/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($tmp_name, $target_path)) {
                $image_url = 'uploads/cakes/' . $file_name;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Please select an image.']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO cakes (name, description, price, image_url) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $description, $price, $image_url])) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add cake.']);
        }
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? 0;
        
        $image_url = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $file_name = uniqid() . '_' . basename(preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['image']['name']));
            $upload_dir = '../uploads/cakes/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($tmp_name, $target_path)) {
                $image_url = 'uploads/cakes/' . $file_name;
                
                // Delete old image
                $stmt = $conn->prepare("SELECT image_url FROM cakes WHERE id = ?");
                $stmt->execute([$id]);
                $old = $stmt->fetch();
                if ($old && !empty($old['image_url'])) {
                    $file_path = '../' . $old['image_url'];
                    if (file_exists($file_path)) unlink($file_path);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload new image.']);
                exit;
            }
        }

        if ($image_url !== null) {
            $stmt = $conn->prepare("UPDATE cakes SET name=?, description=?, price=?, image_url=? WHERE id=?");
            $result = $stmt->execute([$name, $description, $price, $image_url, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE cakes SET name=?, description=?, price=? WHERE id=?");
            $result = $stmt->execute([$name, $description, $price, $id]);
        }
        
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update cake.']);
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        
        // Optionally delete the physical image file
        $stmt = $conn->prepare("SELECT image_url FROM cakes WHERE id = ?");
        $stmt->execute([$id]);
        $cake = $stmt->fetch();
        if ($cake && !empty($cake['image_url'])) {
            $file_path = '../' . $cake['image_url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $stmt = $conn->prepare("DELETE FROM cakes WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete cake']);
        }
    }
}
?>
