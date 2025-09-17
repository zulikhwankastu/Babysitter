<?php
session_start();
include '_db.php'; // DB connection

// Optional: check admin auth here

// Fetch all parents
$parents_query = "SELECT id, name, email,  created_at FROM parents ORDER BY created_at DESC";
$parents_result = $dbc->query($parents_query);

if (!$parents_result) {
    die("Database query failed: " . $dbc->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Parents and Children</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #fefae0; max-width: 1000px; margin: auto; }
        h1 { color: #3a5a40; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #588157; color: white; }
        tr:nth-child(even) { background: #f3f3f3; }
        a.back { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #444; }
        .children-table { width: 90%; margin-top: 5px; margin-bottom: 15px; border: 1px solid #aaa; }
        .children-table th, .children-table td { font-size: 14px; padding: 6px; }
        .children-table th { background: #7b9e75; color: white; }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="back">← Back to Admin Dashboard</a>

<h1>Registered Parents and Their Children</h1>

<?php if ($parents_result->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>Parent ID</th>
            <th>Parent Name</th>
            <th>Email</th>
            
            <th>Registered On</th>
            <th>Children</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($parent = $parents_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($parent['id']) ?></td>
                <td><?= htmlspecialchars($parent['name']) ?></td>
                <td><?= htmlspecialchars($parent['email']) ?></td>
               
                <td><?= htmlspecialchars($parent['created_at']) ?></td>
                <td>
                    <?php
                    // Fetch children for this parent
                   $stmt = $dbc->prepare("SELECT child_name, child_age FROM children WHERE parent_id = ? ORDER BY child_name ASC");

                    $stmt->bind_param("i", $parent['id']);
                    $stmt->execute();
                    $children_result = $stmt->get_result();

                    if ($children_result->num_rows > 0):
                    ?>
                    <table class="children-table">
                        <thead>
                           
                        </thead>
                        <tbody>
                            <?php while ($child = $children_result->fetch_assoc()): ?>
                           
                             <?php echo "<li>" . htmlspecialchars($child['child_name']) . " (Age: " . htmlspecialchars($child['child_age']) . ")</li> "?>
                                
                           
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php
                    else:
                        echo "<em>No children registered</em>";
                    endif;
                    $stmt->close();
                    ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No parents registered yet.</p>
<?php endif; ?>

</body>
</html>
