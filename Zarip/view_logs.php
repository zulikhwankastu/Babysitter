<?php
session_start();
include '_db.php'; // DB connection

$babysitter_id = $_SESSION['babysitter_id'] ?? null;

if (!$babysitter_id) {
    echo "You must be logged in as a babysitter to view activity logs.";
    exit;
}

$message = '';
$booking_id = $_GET['booking_id'] ?? null;

// Fetch babysitter bookings for the dropdown
$stmt = $dbc->prepare("SELECT id, start_date, end_date FROM babysitter_bookings WHERE babysitter_id = ? ORDER BY start_date DESC");
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
$bookings = $bookings_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$logs = [];

if ($booking_id) {
    // Validate booking belongs to babysitter
    $valid_booking = false;
    foreach ($bookings as $b) {
        if ($b['id'] == $booking_id) {
            $valid_booking = true;
            break;
        }
    }

    if ($valid_booking) {
        $stmt = $dbc->prepare("SELECT log_date, activity FROM babysitter_logs WHERE booking_id = ? ORDER BY log_date DESC, id DESC");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $logs = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $message = "Invalid booking selected.";
        $booking_id = null; // reset
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Babysitting Activity Logs</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #fefae0; max-width: 700px; margin: auto; }
        label { display: block; margin-top: 15px; }
        select { width: 100%; padding: 8px; font-size: 16px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #588157; color: white; }
        .message { margin-top: 15px; color: green; }
        .error { color: red; }
        a.back { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #444; }
        .no-logs { margin-top: 20px; font-style: italic; }
    </style>
    <script>
        function onBookingChange() {
            const bookingSelect = document.getElementById('booking_id');
            const selectedBooking = bookingSelect.value;
            window.location.href = "?booking_id=" + encodeURIComponent(selectedBooking);
        }
    </script>
</head>
<body>

<a href="homepage.php" class="back">← Back to Dashboard</a>

<h1>View Babysitting Activity Logs</h1>

<?php if ($message): ?>
    <p class="error"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<label for="booking_id">Select Booking</label>
<select id="booking_id" name="booking_id" onchange="onBookingChange()">
    <option value="">-- Select a booking --</option>
    <?php foreach ($bookings as $b): ?>
        <option value="<?= htmlspecialchars($b['id']) ?>"
            <?= ($booking_id == $b['id']) ? 'selected' : '' ?>>
            Booking #<?= htmlspecialchars($b['id']) ?> (<?= htmlspecialchars($b['start_date']) ?> to <?= htmlspecialchars($b['end_date']) ?>)
        </option>
    <?php endforeach; ?>
</select>

<?php if ($booking_id): ?>
    <?php if (count($logs) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['log_date']) ?></td>
                        <td><?= nl2br(htmlspecialchars($log['activity'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-logs">No activity logs found for this booking.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
