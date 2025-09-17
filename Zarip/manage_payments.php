<?php
session_start();
include '_db.php'; // Assumes $dbc as DB connection

$parent_id = $_SESSION['parent_id'] ?? null;

if (!$parent_id) {
    echo "You must be logged in as a parent to view this page.";
    exit;
}

// Fetch all bookings by parent along with payment info (if any)
$sql = "SELECT b.*, 
               p.payment_method, 
               p.total_price, 
               p.receipt, 
               p.submitted_at AS payment_date, 
               p.status AS payment_status,
               p.received_at
        FROM babysitter_bookings b
        LEFT JOIN babysitter_payments p ON b.id = p.booking_id
        WHERE b.parent_id = ?
        ORDER BY b.created_at DESC";

$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();

$pending = $accepted = $paid = $received = $rejected = [];

while ($row = $result->fetch_assoc()) {
    // Priority 1: Received payments
    if (!empty($row['received_at'])) {
        $received[] = $row;

    // Priority 2: Payment approved but not marked as received yet
    } elseif (!empty($row['payment_status']) && $row['payment_status'] === 'approved') {
        $paid[] = $row;

    // Priority 3: Paid but not yet approved
    } elseif (!empty($row['payment_status']) && $row['payment_status'] === 'pending') {
        $paid[] = $row;

    // Priority 4: Accepted bookings, no payment yet
    } elseif ($row['status'] === 'accepted' && empty($row['payment_status'])) {
        $accepted[] = $row;

    // Priority 5: Bookings waiting approval
    } elseif ($row['status'] === 'pending' && empty($row['payment_status'])) {
        $pending[] = $row;

    // Priority 6: Rejected bookings
    } elseif ($row['status'] === 'rejected') {
        $rejected[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fefae0;
            padding: 20px;
        }
        h1, h2 {
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #d4a373;
            color: white;
        }
        .pay-btn {
            background: #588157;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 5px;
        }
        .pay-btn:hover {
            background: #3a5a40;
        }
        .section {
            margin-bottom: 60px;
        }

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
<a href="homepage.php" class="back-btn">← Back to Dashboard</a>
<h1>Manage Babysitter Bookings</h1>

<!-- Waiting Approval -->
<div class="section">
    <h2>⏳ Waiting for Babysitter Approval</h2>
    <?php if (count($pending) > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hours/Day</th>
                <th>Total Days</th>
                <th>Total Hours</th>
                <th>Status</th>
            </tr>
            <?php foreach ($pending as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                    <td><?= htmlspecialchars($row['hours_per_day']) ?></td>
                    <td><?= htmlspecialchars($row['total_days']) ?></td>
                    <td><?= htmlspecialchars($row['total_hours']) ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No pending bookings.</p>
    <?php endif; ?>
</div>

<!-- Accepted Bookings -->
<div class="section">
    <h2>✅ Accepted Bookings - Proceed to Payment</h2>
    <?php if (count($accepted) > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hours/Day</th>
                <th>Total Days</th>
                <th>Total Hours</th>
                <th>Action</th>
            </tr>
            <?php foreach ($accepted as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                    <td><?= htmlspecialchars($row['hours_per_day']) ?></td>
                    <td><?= htmlspecialchars($row['total_days']) ?></td>
                    <td><?= htmlspecialchars($row['total_hours']) ?></td>
                    <td><a class="pay-btn" href="pay_booking.php?booking_id=<?= urlencode($row['id']) ?>">Pay Now</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No accepted bookings yet.</p>
    <?php endif; ?>
</div>

<!-- Paid Bookings (Pending/Approved) -->
<div class="section">
    <h2>💰 Paid Bookings - Awaiting Babysitter Confirmation</h2>
    <?php if (count($paid) > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hours/Day</th>
                <th>Total Days</th>
                <th>Total Hours</th>
                <th>Payment Method</th>
                <th>Total Price</th>
                <th>Receipt</th>
                <th>Payment Date</th>
                <th>Payment Status</th>
            </tr>
            <?php foreach ($paid as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                    <td><?= htmlspecialchars($row['hours_per_day']) ?></td>
                    <td><?= htmlspecialchars($row['total_days']) ?></td>
                    <td><?= htmlspecialchars($row['total_hours']) ?></td>
                    <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $row['payment_method'] ?? 'N/A'))) ?></td>
                    <td>RM <?= number_format($row['total_price'] ?? 0, 2) ?></td>
                    <td><?= !empty($row['receipt']) ? "<a href='" . htmlspecialchars($row['receipt']) . "' target='_blank'>View</a>" : 'N/A' ?></td>
                    <td><?= htmlspecialchars($row['payment_date'] ?? 'N/A') ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['payment_status'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No paid bookings yet.</p>
    <?php endif; ?>
</div>

<!-- Received Payments -->
<!-- Received Payments -->
<div class="section">
    <h2>📦 Payment Received</h2>
    <?php if (count($received) > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hours/Day</th>
                <th>Total Days</th>
                <th>Total Hours</th>
                <th>Payment Method</th>
                <th>Total Price</th>
                <th>Receipt</th>
                <th>Payment Date</th>
                <th>Received At</th>
                <th>Payment Status</th>
            </tr>
            <?php foreach ($received as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                    <td><?= htmlspecialchars($row['hours_per_day']) ?></td>
                    <td><?= htmlspecialchars($row['total_days']) ?></td>
                    <td><?= htmlspecialchars($row['total_hours']) ?></td>
                    <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $row['payment_method'] ?? 'N/A'))) ?></td>
                    <td>RM <?= number_format($row['total_price'] ?? 0, 2) ?></td>
                    <td><?= !empty($row['receipt']) ? "<a href='" . htmlspecialchars($row['receipt']) . "' target='_blank'>View</a>" : 'N/A' ?></td>
                    <td><?= htmlspecialchars($row['payment_date'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['received_at'] ?? 'N/A') ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['payment_status'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No received payments.</p>
    <?php endif; ?>
</div>
<!-- Rejected Bookings -->
<div class="section">
    <h2>❌ Rejected Bookings</h2>
    <?php if (count($rejected) > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hours/Day</th>
                <th>Total Days</th>
                <th>Total Hours</th>
                <th>Reason</th>
            </tr>
            <?php foreach ($rejected as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                    <td><?= htmlspecialchars($row['hours_per_day']) ?></td>
                    <td><?= htmlspecialchars($row['total_days']) ?></td>
                    <td><?= htmlspecialchars($row['total_hours']) ?></td>
                    <td><?= htmlspecialchars($row['rejection_reason'] ?? 'No reason given') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No rejected bookings.</p>
    <?php endif; ?>
</div>

</body>
</html>
