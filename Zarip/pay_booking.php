<?php
include '_db.php';
session_start();

$booking_id = intval($_GET['booking_id'] ?? 0);
if ($booking_id <= 0) {
    echo "Invalid booking ID.";
    exit;
}

// Fetch booking with babysitter rate
$stmt = $dbc->prepare("
    SELECT bb.*, p.name AS parent_name, b.name AS babysitter_name, b.rate
    FROM babysitter_bookings bb
    JOIN parents p ON bb.parent_id = p.id
    JOIN babysitters b ON bb.babysitter_id = b.id
    WHERE bb.id = ?
");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Booking not found.";
    exit;
}

$booking = $result->fetch_assoc();
$rate_per_hour = floatval($booking['rate']);
$total_price = $booking['total_hours'] * $rate_per_hour;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pay Booking</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            padding: 20px;
            color: #333;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
        }
        .payment-method {
            margin-top: 20px;
        }
        .qr-image {
            display: none;
            margin-top: 15px;
            text-align: center;
        }
        .qr-image img {
            width: 200px;
        }
        button {
            padding: 10px 20px;
            font-weight: bold;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
    <script>
        function showMethod(method) {
            document.getElementById('qr-section').style.display = (method === 'qr' || method === 'bank_transfer') ? 'block' : 'none';
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Booking Payment</h2>

    <table>
        <tr><th>Parent Name</th><td><?= htmlspecialchars($booking['parent_name']) ?></td></tr>
        <tr><th>Babysitter Name</th><td><?= htmlspecialchars($booking['babysitter_name']) ?></td></tr>
        <tr><th>Start Date</th><td><?= htmlspecialchars($booking['start_date']) ?></td></tr>
        <tr><th>End Date</th><td><?= htmlspecialchars($booking['end_date']) ?></td></tr>
        <tr><th>Hours per Day</th><td><?= $booking['hours_per_day'] ?></td></tr>
        <tr><th>Total Days</th><td><?= $booking['total_days'] ?></td></tr>
        <tr><th>Total Hours</th><td><?= $booking['total_hours'] ?></td></tr>
        <tr><th>Rate per Hour</th><td>RM <?= number_format($rate_per_hour, 2) ?></td></tr>
        <tr class="total"><th>Total Price</th><td>RM <?= number_format($total_price, 2) ?></td></tr>
    </table>

    <h3>Select Payment Method</h3>
    <div class="payment-method">
        <form method="POST" action="upload_payment.php" enctype="multipart/form-data">
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
             <input type="hidden" name="total_price" value="<?= number_format($total_price, 2, '.', '') ?>">
            <label>
                <input type="radio" name="payment_method" value="qr" onchange="showMethod('qr')" required> QR Code
            </label><br>
            <label>
                <input type="radio" name="payment_method" value="bank_transfer" onchange="showMethod('bank_transfer')"> Bank Transfer
            </label><br>
            <label>
                <input type="radio" name="payment_method" value="cod" onchange="showMethod('cod')"> Cash on Delivery (COD)
            </label>

            <div id="qr-section" class="qr-image">
                <p>Scan this QR code or use bank details to make payment:</p>
                <img src="images/duitnow_qr.png" alt="QR Code"><br><br>
                <label>Upload Payment Receipt (QR/Bank Transfer):</label><br>
                <input type="file" name="receipt" accept="image/*"><br>
            </div>

            <br>
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</div>

</body>
</html>
