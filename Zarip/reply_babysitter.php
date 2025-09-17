<?php
session_start();
include '_db.php';

$babysitter_id = $_SESSION['babysitter_id'] ?? null;

if (!$babysitter_id) {
    echo "You must be logged in as a babysitter.";
    exit;
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['message_id'])) {
    $message_id = intval($_POST['message_id']);
    $reply = trim($_POST['reply']);

    if (!empty($reply)) {
        $stmt = $dbc->prepare("UPDATE contact_messages SET reply = ? WHERE id = ? AND babysitter_id = ?");
        $stmt->bind_param("sii", $reply, $message_id, $babysitter_id);
        $stmt->execute();
        $stmt->close();
        $success = "Reply sent.";
    }
}

// Fetch parent messages for this babysitter
$sql = "SELECT * FROM contact_messages WHERE babysitter_id = ? ORDER BY sent_at DESC";
$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reply to Parents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fffaf2;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .message-block {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .message-block p {
            margin: 5px 0;
        }
        .message {
            font-weight: bold;
        }
        .reply-box {
            margin-top: 10px;
        }
        textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            font-size: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        button {
            margin-top: 10px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .timestamp {
            font-size: 0.85em;
            color: #777;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reply to Parents</h2>

        <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message-block">
                <p><span class="message">From Parent #<?= $row['parent_id'] ?>:</span> <?= htmlspecialchars($row['message']) ?></p>
                <p class="timestamp">Sent at: <?= $row['sent_at'] ?></p>

                <?php if ($row['reply']): ?>
                    <p><strong>Your Reply:</strong> <?= htmlspecialchars($row['reply']) ?></p>
                <?php else: ?>
                    <form class="reply-box" method="POST">
                        <input type="hidden" name="message_id" value="<?= $row['id'] ?>">
                        <textarea name="reply" placeholder="Write your reply here..." required></textarea>
                        <button type="submit">Send Reply</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>

        <a href="babysitter_dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>
