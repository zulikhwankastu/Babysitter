<?php
session_start();
include '_db.php'; // assumes it connects via $conn

$parent_id = $_SESSION['parent_id'] ?? null;

if (!$parent_id) {
    echo "You must be logged in as a parent to view this page.";
    exit;
}

// Fetch all messages involving this parent
$sql = "SELECT * FROM contact_messages WHERE parent_id = ? ORDER BY sent_at ASC";
$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Babysitter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fffaf2;
            padding: 20px;
        }
        .message-container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .message {
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 8px;
        }
        .sent {
            background: #d1e7dd;
            text-align: right;
        }
        .received {
            background: #f8d7da;
            text-align: left;
        }
        .reply {
            background: #fef3c7;
            text-align: left;
        }
        .timestamp {
            font-size: 0.8em;
            color: #666;
        }
        .sender-label {
            font-size: 0.9em;
            font-weight: bold;
            margin-bottom: 4px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background: #333;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h2>Messages with Babysitter</h2>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Parent's message
                if (!empty($row['message'])) {
                    ?>
                    <div class="message sent">
                        <div class="sender-label">You (Parent)</div>
                        <div><?php echo htmlspecialchars($row['message']); ?></div>
                        <div class="timestamp"><?php echo $row['sent_at']; ?></div>
                    </div>
                    <?php
                }

                // Babysitter's reply (if available)
                if (!empty($row['reply'])) {
                    ?>
                    <div class="message reply">
                        <div class="sender-label">Reply from Babysitter #<?php echo $row['babysitter_id']; ?></div>
                        <div><?php echo htmlspecialchars($row['reply']); ?></div>
                        <div class="timestamp">Replied</div>
                    </div>
                    <?php
                }
            }
        } else {
            echo "<p>No messages yet.</p>";
        }

        $stmt->close();
        $dbc->close();
        ?>

        <a href="homepage.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>
