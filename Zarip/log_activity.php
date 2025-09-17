<?php
session_start();
include '_db.php'; // DB connection

$babysitter_id = $_SESSION['babysitter_id'] ?? null;

if (!$babysitter_id) {
    echo "You must be logged in as a babysitter to log activities.";
    exit;
}

$message = '';
$booking_id = $_POST['booking_id'] ?? null;  // from form submission

// Fetch babysitter bookings for the dropdown
$stmt = $dbc->prepare("SELECT id, start_date, end_date FROM babysitter_bookings WHERE babysitter_id = ? ORDER BY start_date DESC");
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
$bookings = $bookings_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$logs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_log'])) {
    // Validate booking_id belongs to babysitter
    $booking_id = (int)$booking_id;
    $valid_booking = false;
    foreach ($bookings as $b) {
        if ($b['id'] == $booking_id) {
            $valid_booking = true;
            break;
        }
    }
    if (!$valid_booking) {
        $message = "Invalid booking selected.";
    } else {
        $log_date = $_POST['log_date'] ?? '';
        $activity = trim($_POST['activity'] ?? '');

        if (!$log_date || !$activity) {
            $message = "Please provide both the date and activity details.";
        } else {
            $stmt = $dbc->prepare("INSERT INTO babysitter_logs (booking_id, log_date, activity) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $booking_id, $log_date, $activity);
            if ($stmt->execute()) {
                $message = "Activity logged successfully.";
                // Clear form fields after successful submission
                $log_date = date('Y-m-d');
                $activity = '';
            } else {
                $message = "Failed to log activity.";
            }
            $stmt->close();
        }
    }
} else {
    $log_date = date('Y-m-d');
    $activity = '';
}

// Fetch logs for the selected booking to show at bottom
if ($booking_id) {
    // Validate booking_id again for safety
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
        $logs = [];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log Babysitting Activity</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #fefae0; max-width: 600px; margin: auto; }
        label { display: block; margin-top: 15px; }
        select, input[type="date"], textarea { width: 100%; padding: 8px; font-size: 16px; margin-top: 5px; }
        textarea { height: 100px; }
        button { margin-top: 15px; padding: 10px 20px; background: #588157; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #3a5a40; }
        .message { margin-top: 15px; color: green; }
        .error { color: red; }
        a.back { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #588157; color: white; }
        .no-logs { margin-top: 20px; font-style: italic; }
    </style>
</head>
<body>

<a href="babysitter_dashboard.php" class="back">← Back to Dashboard</a>

<h1>Log Babysitting Activity</h1>

<?php if ($message): ?>
    <p class="<?= strpos($message, 'successfully') !== false ? 'message' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="post">
    <label for="booking_id">Select Booking</label>
    <select name="booking_id" id="booking_id" required onchange="this.form.submit()">
        <option value="">-- Select a booking --</option>
        <?php foreach ($bookings as $b): ?>
            <option value="<?= htmlspecialchars($b['id']) ?>"
                <?= (isset($booking_id) && $booking_id == $b['id']) ? 'selected' : '' ?>>
                Booking #<?= htmlspecialchars($b['id']) ?> (<?= htmlspecialchars($b['start_date']) ?> to <?= htmlspecialchars($b['end_date']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <?php if ($booking_id): ?>
        <label for="log_date">Date of Activity</label>
        <input type="date" name="log_date" id="log_date" required value="<?= htmlspecialchars($log_date) ?>">

        <label for="activity">Activity Details</label>
        <textarea name="activity" id="activity" required placeholder="Describe what activity was done..."><?= htmlspecialchars($activity) ?></textarea>

        <button type="submit" name="submit_log">Submit Activity Log</button>
    <?php endif; ?>
</form>

<?php if ($booking_id): ?>
    <h2>Activity Logs for Booking #<?= htmlspecialchars($booking_id) ?></h2>
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
