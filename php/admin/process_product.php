<?php
require_once 'admin_functions.php';
require_once 'response_functions.php';
validateAdminAccess();

$conn = connect_db();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product"])) {
    $name = trim($_POST["product_name"] ?? "");
    $description = trim($_POST["product_description"] ?? "");
    $price = floatval($_POST["product_price"] ?? 0);
    $category = trim($_POST["product_category"] ?? "");
    $errors = [];
    if (empty($name)) {
        $errors[] = "Product name is required.";
    }
    if (empty($description)) {
        $errors[] = "Product description is required.";
    }
    if ($price <= 0) {
        $errors[] = "Valid price is required.";
    }
    if (empty($category)) {
        $errors[] = "Product category is required.";
    }
    if (!isset($_FILES["product_image"]) || $_FILES["product_image"]["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "Product image is required.";
    }

    if (empty($errors)) {
        $image = $_FILES["product_image"];
        $imageFileType = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageFileType, $allowedTypes)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            $targetDir = "../../resources/uploads/products/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = uniqid() . "." . $imageFileType;
            $targetPath = $targetDir . $fileName;
            $dbImagePath = 'uploads/products/' . $fileName;

            if (move_uploaded_file($image["tmp_name"], $targetPath)) {
                $sql = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sds", $name, $price, $dbImagePath);

                if ($stmt->execute()) {
                    header("Location: manage-products.php?success=1");
                    exit;
                } else {
                    $errors[] = "Error adding product: " . $conn->error;
                    unlink($targetPath);
                }
                $stmt->close();
            } else {
                $errors[] = "Error uploading image.";
            }
        }
    }

    if (!empty($errors)) {
        $errorString = implode("\\n", $errors);
        header("Location: manage-products.php?error=" . urlencode($errorString));
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_product"])) {
    $productId = intval($_POST["product_id"] ?? 0);
    
    if ($productId > 0) {
        $sql = "SELECT image FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $imageFile = basename($row["image"]);
            $imagePath = "../../resources/uploads/products/" . $imageFile;
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            
            if ($stmt->execute()) {
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                header("Location: manage-products.php?success=2");
                exit;
            } else {
                header("Location: manage-products.php?error=" . urlencode("Error deleting product."));
                exit;
            }
        } else {
            header("Location: manage-products.php?error=" . urlencode("Product not found."));
            exit;
        }
        $stmt->close();
    } else {
        header("Location: manage-products.php?error=" . urlencode("Invalid product ID."));
        exit;
    }
}
header("Location: manage-products.php");
exit;