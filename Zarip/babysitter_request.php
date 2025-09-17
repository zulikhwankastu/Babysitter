<?php
session_start();
include '_db.php';

if (!isset($_SESSION['babysitter_id'])) {
    echo "You must be logged in as a babysitter to see requests.";
    exit;
}

$babysitter_id = intval($_SESSION['babysitter_id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $rejection_reason = trim($_POST['rejection_reason'] ?? '');

    if ($booking_id > 0 && $action === 'accept') {
        $stmt = $dbc->prepare("UPDATE babysitter_bookings SET status = 'accepted' WHERE id = ? AND babysitter_id = ?");
        $stmt->bind_param("ii", $booking_id, $babysitter_id);
        $message = $stmt->execute() ? "Booking request accepted." : "Failed to update booking.";
        $stmt->close();
    }

    if ($booking_id > 0 && $action === 'reject') {
        if ($rejection_reason === '') {
            $message = "Rejection reason is required.";
        } else {
            $stmt = $dbc->prepare("UPDATE babysitter_bookings SET status = 'rejected', rejection_reason = ? WHERE id = ? AND babysitter_id = ?");
            $stmt->bind_param("sii", $rejection_reason, $booking_id, $babysitter_id);
            $message = $stmt->execute() ? "Booking request rejected." : "Failed to reject booking.";
            $stmt->close();
        }
    }
}

// Fetch all pending requests
$stmt = $dbc->prepare("
    SELECT bb.id, bb.start_date, bb.end_date, bb.hours_per_day, bb.total_days, bb.total_hours,
           p.name as parent_name, p.email as parent_email, p.phone as parent_phone
    FROM babysitter_bookings bb
    JOIN parents p ON bb.parent_id = p.id
    WHERE bb.babysitter_id = ? AND bb.status = 'pending'
    ORDER BY bb.start_date ASC
");
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Babysitter Booking Requests</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
            margin: 0; padding: 20px; color: #333;
        }
        h1 {
            margin-bottom: 20px;
            color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
        button {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .accept {
            background-color: #27ae60;
            color: white;
        }
        .reject {
            background-color: #e74c3c;
            color: white;
        }
        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .reason-box {
            display: none;
            margin-top: 8px;
        }
        textarea {
            width: 100%;
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }
    </style>
    <script>
        function showReasonForm(id) {
            const box = document.getElementById('reason-box-' + id);
            box.style.display = 'block';
        }
    </script>
</head>
<body>

    <h1>Booking Requests</h1>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'Failed') === false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Parent Name</th>
                    <th>Contact Email</th>
                    <th>Contact Phone</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Hours/Day</th>
                    <th>Total Days</th>
                    <th>Total Hours</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['parent_name']) ?></td>
                        <td><?= htmlspecialchars($row['parent_email']) ?></td>
                        <td><?= htmlspecialchars($row['parent_phone']) ?></td>
                        <td><?= htmlspecialchars($row['start_date']) ?></td>
                        <td><?= htmlspecialchars($row['end_date']) ?></td>
                        <td><?= htmlspecialchars($row['hours_per_day']) ?></td>
                        <td><?= htmlspecialchars($row['total_days']) ?></td>
                        <td><?= htmlspecialchars($row['total_hours']) ?></td>
                        <td>
                            <!-- Accept Form -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="action" value="accept" class="accept" onclick="return confirm('Accept this booking request?')">Accept</button>
                            </form>

                            <!-- Reject Button that reveals textarea -->
                            <button class="reject" onclick="showReasonForm(<?= $row['id'] ?>)">Reject</button>

                            <!-- Reject Form with Reason -->
                            <div class="reason-box" id="reason-box-<?= $row['id'] ?>">
                                <form method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                    <textarea name="rejection_reason" rows="2" placeholder="Enter reason for rejection" required></textarea>
                                    <button type="submit" name="action" value="reject" class="reject">Confirm Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><em>No pending booking requests at the moment.</em></p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
?>
