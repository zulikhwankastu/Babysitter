<?php
session_start();
include '_db.php'; // Your DB connection

// Optional: check if user is admin or authorized to view babysitters
// For now, no auth check

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    // Delete babysitter by ID
    $stmt = $dbc->prepare("DELETE FROM babysitters WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Babysitter ID $delete_id deleted successfully.";
    } else {
        $message = "Failed to delete babysitter ID $delete_id.";
    }
    $stmt->close();
}

// Fetch all babysitters
$query = "SELECT id, name, email, gender, created_at FROM babysitters ORDER BY created_at DESC";
$result = $dbc->query($query);

if (!$result) {
    die("Database query failed: " . $dbc->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Babysitters</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #fefae0; max-width: 900px; margin: auto; }
        h1 { color: #3a5a40; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #588157; color: white; }
        tr:nth-child(even) { background: #f3f3f3; }
        a.back { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #444; }
        form { margin: 0; }
        button.delete-btn {
            background-color: #c94c4c;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
        }
        button.delete-btn:hover {
            background-color: #a03b3b;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            max-width: 900px;
            margin: 15px auto;
            border-radius: 5px;
        }
    </style>
    <script>
        function confirmDelete(name) {
            return confirm("Are you sure you want to delete babysitter: " + name + " ?");
        }
    </script>
</head>
<body>

<a href="admin_dashboard.php" class="back">← Back to Admin Dashboard</a>

<h1>Registered Babysitters</h1>

<?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>           
            <th>Gender</th>
            <th>Registered On</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>           
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>
                <form method="post" onsubmit="return confirmDelete('<?= htmlspecialchars(addslashes($row['name'])) ?>')">
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No babysitters registered yet.</p>
<?php endif; ?>

</body>
</html>
