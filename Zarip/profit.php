<?php
session_start();
include '_db.php'; // DB connection

// --- Summary Query: Total bookings and profit per babysitter ---
$summary_query = "
    SELECT 
        bs.id AS babysitter_id,
        bs.name,
        bs.email,
        COUNT(DISTINCT b.id) AS total_bookings,
        COALESCE(SUM(p.total_price), 0) AS total_profit
    FROM babysitters bs
    LEFT JOIN babysitter_bookings b ON bs.id = b.babysitter_id
    LEFT JOIN babysitter_payments p ON b.id = p.booking_id
    WHERE p.total_price > 0 AND p.received_at IS NOT NULL
    GROUP BY bs.id
    ORDER BY total_profit DESC
";

$summary_result = $dbc->query($summary_query);
if (!$summary_result) {
    die("Summary query failed: " . $dbc->error);
}

// --- Detail Query: List all individual payments ---
$detail_query = "
    SELECT 
        p.booking_id,
        bs.name AS babysitter_name,
        p.total_price,
        p.received_at
    FROM babysitter_payments p
    INNER JOIN babysitter_bookings b ON p.booking_id = b.id
    INNER JOIN babysitters bs ON b.babysitter_id = bs.id
    WHERE p.total_price > 0 AND p.received_at IS NOT NULL
    ORDER BY p.received_at DESC
";

$detail_result = $dbc->query($detail_query);
if (!$detail_result) {
    die("Detail query failed: " . $dbc->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Babysitter Profit Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #fefae0;
            max-width: 1000px;
            margin: auto;
        }
        h1, h2 {
            color: #3a5a40;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #588157;
            color: white;
        }
        tr:nth-child(even) {
            background: #f3f3f3;
        }
        a.back {
            display: inline-block;
            margin-bottom: 15px;
            text-decoration: none;
            color: #444;
        }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="back">← Back to Admin Dashboard</a>

<h1>Babysitter Profit Overview (Summary)</h1>

<?php if ($summary_result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Babysitter Name</th>
                <th>Email</th>
                <th>Total Bookings</th>
                <th>Total Profit (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $summary_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['total_bookings']) ?></td>
                    <td>RM <?= number_format($row['total_profit'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No confirmed payments found.</p>
<?php endif; ?>

<h2>All Confirmed Payments (Details)</h2>

<?php if ($detail_result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Babysitter Name</th>
                <th>Total Price (RM)</th>
                <th>Received At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $detail_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['booking_id']) ?></td>
                    <td><?= htmlspecialchars($row['babysitter_name']) ?></td>
                    <td>RM <?= number_format($row['total_price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['received_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No payment records found.</p>
<?php endif; ?>

</body>
</html>
