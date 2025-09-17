<?php
session_start();
include '_db.php'; // Assumes $dbc is your mysqli connection

$babysitter_id = $_SESSION['babysitter_id'] ?? null;

if (!$babysitter_id) {
    echo "You must be logged in as a babysitter to view this page.";
    exit;
}

// Handle "Received" button click
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_received'])) {
    $booking_id = $_POST['booking_id'];

    $update = $dbc->prepare("UPDATE babysitter_payments SET status = 'received', received_at = NOW() WHERE booking_id = ? AND status = 'pending'");
    $update->bind_param("i", $booking_id);
    $update->execute();
    $update->close();
}

// Fetch bookings & payments for babysitter
$sql = "SELECT b.*, 
               p.payment_method, 
               p.total_price, 
               p.receipt, 
               p.submitted_at AS payment_date, 
               p.status AS payment_status, 
               p.received_at 
        FROM babysitter_bookings b
        INNER JOIN babysitter_payments p ON b.id = p.booking_id
        WHERE b.babysitter_id = ? AND (p.status = 'pending' OR p.status = 'received')
        ORDER BY p.submitted_at DESC";

$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Babysitter Payments</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #343a40; color: white; }
        .btn { padding: 8px 14px; background: #198754; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn[disabled] { background: #ccc; cursor: not-allowed; }
        .back-btn {
    display: inline-block;
    background: #6c757d;
    color: white;
    padding: 10px 16px;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: 20px;
    font-weight: bold;
}
.back-btn:hover {
    background: #495057;
}

    </style>
</head>
<body>
<a href="babysitter_dashboard.php" class="back-btn">← Back to Dashboard</a>

<h2>🧾 Babysitter Payment Confirmations</h2>

<?php if (count($bookings) > 0): ?>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Parent ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Price</th>
            <th>Payment Method</th>
            <th>Receipt</th>
            <th>Submitted At</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($bookings as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['parent_id'] ?></td>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['end_date'] ?></td>
                <td>RM <?= number_format($row['total_price'], 2) ?></td>
                <td><?= ucfirst($row['payment_method']) ?></td>
                <td>
                    <?php if (!empty($row['receipt'])): ?>
                        <a href="<?= $row['receipt'] ?>" target="_blank">View</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td><?= $row['payment_date'] ?></td>
                <td><?= ucfirst($row['payment_status']) ?></td>
                <td>
                    <?php if ($row['payment_status'] === 'pending'): ?>
                        <form method="post">
                            <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="mark_received" class="btn">Mark as Received</button>
                        </form>
                    <?php else: ?>
                        ✅ Received on <?= $row['received_at'] ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No payments to confirm.</p>
<?php endif; ?>

</body>
</html>
