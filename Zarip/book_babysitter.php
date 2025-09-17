<?php
session_start();
include '_db.php';

if (!isset($_SESSION['parent_id'])) {
    echo "You must be logged in as a parent to book a babysitter.";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Babysitter ID is required.";
    exit;
}

$babysitter_id = intval($_GET['id']);
$parent_id = intval($_SESSION['parent_id']);
$success = '';
$error = '';

// Fetch babysitter rate per hour from DB (assumed stored in babysitters table)
$stmt = $dbc->prepare("SELECT rate, name FROM babysitters WHERE id = ?");
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$stmt->bind_result($rate_per_hour, $babysitter_name);
if (!$stmt->fetch()) {
    die("Babysitter not found.");
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if this is the confirmation form submission
    if (isset($_POST['confirm_booking']) && $_POST['confirm_booking'] === 'yes') {
        // Actual insert to DB
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $hours_per_day = floatval($_POST['hours_per_day']);
        $total_days = intval($_POST['total_days']);
        $total_hours = floatval($_POST['total_hours']);

        $stmt = $dbc->prepare("INSERT INTO babysitter_bookings (parent_id, babysitter_id, start_date, end_date, hours_per_day, total_days, total_hours) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissidd", $parent_id, $babysitter_id, $start_date, $end_date, $hours_per_day, $total_days, $total_hours);

        if ($stmt->execute()) {
            $success = "Booking successful! You booked for $total_days day(s), $hours_per_day hour(s) per day, total $total_hours hours.";
        } else {
            $error = "Failed to save booking. Please try again.";
        }
        $stmt->close();

    } else {
        // First submission: validate input and show confirmation
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $hours_per_day = floatval($_POST['hours_per_day'] ?? 0);

        if (!$start_date || !$end_date || !$hours_per_day) {
            $error = "Please fill in all fields.";
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
            $error = "Invalid date format. Use YYYY-MM-DD.";
        } elseif ($start_date > $end_date) {
            $error = "Start date cannot be after end date.";
        } elseif ($hours_per_day <= 0 || $hours_per_day > 24) {
            $error = "Please enter valid hours per day (1 to 24).";
        } else {
            $start_dt = new DateTime($start_date);
            $end_dt = new DateTime($end_date);
            $interval = $start_dt->diff($end_dt);
            $total_days = $interval->days + 1;
            $total_hours = $total_days * $hours_per_day;

            // Show confirmation form below (flag $show_confirmation = true)
            $show_confirmation = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Book Babysitter</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom right, #f9f9f9, #cce7ff);
            margin: 0; padding: 0; color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        h1 {
            color: #34495e;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        input[type="number"] {
            max-width: 100px;
        }
        button {
            margin-top: 30px;
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1c5980;
        }
        .message {
            margin-top: 20px;
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
        a.back-link {
            display: inline-block;
            margin-top: 25px;
            color: #2980b9;
            text-decoration: none;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .confirm-buttons {
            margin-top: 25px;
        }
        .confirm-buttons button {
            margin-right: 15px;
            width: 120px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Book Babysitter: <?php echo htmlspecialchars($babysitter_name); ?></h1>

    <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <a href="babysitter_details.php?id=<?php echo $babysitter_id; ?>" class="back-link">← Back to Babysitter Profile</a>
    <?php elseif ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <a href="babysitter_details.php?id=<?php echo $babysitter_id; ?>" class="back-link">← Back to Babysitter Profile</a>
    <?php elseif (!empty($show_confirmation)): ?>
        <!-- Confirmation Page -->
        <h2>Please confirm your booking details</h2>
       <table>
    <tr><td>Start Date</td><td><?php echo htmlspecialchars($start_date); ?></td></tr>
    <tr><td>End Date</td><td><?php echo htmlspecialchars($end_date); ?></td></tr>
    <tr><td>Number of Days</td><td><?php echo $total_days; ?></td></tr>
    <tr><td>Hours per Day</td><td><?php echo $hours_per_day; ?></td></tr>
    <tr><td>Total Hours</td><td><?php echo $total_hours; ?></td></tr> <!-- NEW ROW -->
    <tr><td>Rate per Hour</td><td>RM <?php echo number_format($rate_per_hour, 2); ?></td></tr>
    <tr><td><strong>Total Price</strong></td><td><strong>RM <?php echo number_format($total_hours * $rate_per_hour, 2); ?></strong></td></tr>
</table>


        <form method="POST" action="">
            <!-- Pass the booking details hidden for confirmation -->
            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <input type="hidden" name="hours_per_day" value="<?php echo htmlspecialchars($hours_per_day); ?>">
            <input type="hidden" name="total_days" value="<?php echo $total_days; ?>">
            <input type="hidden" name="total_hours" value="<?php echo $total_hours; ?>">
            <input type="hidden" name="confirm_booking" value="yes">

            <div class="confirm-buttons">
                <button type="submit">Confirm Booking</button>
                <button type="button" onclick="window.history.back();">Cancel</button>
            </div>
        </form>

    <?php else: ?>
        <!-- Initial booking form -->
        <form method="POST" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="hours_per_day">Hours per Day:</label>
            <input type="number" id="hours_per_day" name="hours_per_day" min="1" max="24" step="0.5" required>

            <button type="submit">Book Now</button>
        </form>
        <a href="babysitter_details.php?id=<?php echo $babysitter_id; ?>" class="back-link">← Back to Babysitter Profile</a>
    <?php endif; ?>
</div>
</body>
</html>
