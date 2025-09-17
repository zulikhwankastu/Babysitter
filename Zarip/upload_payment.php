<?php
session_start();
include '_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $payment_method = $_POST['payment_method'] ?? '';
    $total_price = floatval($_POST['total_price'] ?? 0);
    $allowed_methods = ['qr', 'bank_transfer', 'cod'];

    if ($booking_id <= 0 || !in_array($payment_method, $allowed_methods)) {
        echo "Invalid submission.";
        exit;
    }

    // Handle file upload if method is qr or bank_transfer
    $receipt_path = null;
    if (in_array($payment_method, ['qr', 'bank_transfer'])) {
        if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $tmpName = $_FILES['receipt']['tmp_name'];
            $fileName = basename($_FILES['receipt']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
            if (!in_array($fileExt, $allowedExt)) {
                echo "Invalid file type. Only images allowed.";
                exit;
            }

            $newFileName = uniqid('receipt_') . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpName, $destination)) {
                $receipt_path = 'uploads/receipts/' . $newFileName;
            } else {
                echo "Failed to upload receipt.";
                exit;
            }
        } else {
            echo "Receipt upload is required for this payment method.";
            exit;
        }
    }

    // Insert payment info including total_price
    $stmt = $dbc->prepare("INSERT INTO babysitter_payments (booking_id, payment_method, receipt, total_price) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo "Prepare failed: (" . $dbc->errno . ") " . $dbc->error;
        exit;
    }
    $stmt->bind_param("issd", $booking_id, $payment_method, $receipt_path, $total_price);

    if ($stmt->execute()) {
        $stmt->close();

        // Optional: update booking status here if needed
        // $updateStmt = $dbc->prepare("UPDATE babysitter_bookings SET status = 'payment_pending' WHERE id = ?");
        // $updateStmt->bind_param("i", $booking_id);
        // $updateStmt->execute();
        // $updateStmt->close();

        // Show thank you message
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment Submitted</title>
            <style>
                body {
                    font-family: 'Segoe UI', sans-serif;
                    background: #f5f5f5;
                    padding: 50px;
                    text-align: center;
                    color: #2c3e50;
                }
                .message-box {
                    background: white;
                    max-width: 500px;
                    margin: auto;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 15px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #27ae60;
                }
                a {
                    display: inline-block;
                    margin-top: 20px;
                    text-decoration: none;
                    color: #2980b9;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="message-box">
                <h1>Thank You!</h1>
                <p>Your payment information has been submitted successfully.</p>
                <a href="manage_payments.php">Back to Payments</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    } else {
        echo "Failed to save payment information.";
        exit;
    }
} else {
    echo "Invalid request method.";
}
