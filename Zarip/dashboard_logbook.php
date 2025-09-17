<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Logbook Entry</title>
    
</head>
<body>
    <h2>Student Logbook Entry</h2>
    
    <form action="insert_logbook.php" method="post">
        <table border="1">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Breakfast</th>
                    <th>Lunch</th>
                    <th>Tea Break</th>
                    <th>Learning</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="date" name="entry_date" required></td>
                    <td><input type="text" name="day" required></td>
                    <td><input type="text" name="breakfast" required></td>
                    <td><input type="text" name="lunch" required></td>
                    <td><input type="text" name="tea_break" required></td>
                    <td><textarea name="learning" rows="4" required></textarea></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="Submit Logbook">
    </form>
</body>
</html>


    <?php
// Include your database connection script
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['entries'])) {
    foreach ($_POST['entries'] as $entry) {
        // Assuming you have a column for each: date, work_done, hours_worked, and remarks
        $stmt = $pdo->prepare("INSERT INTO logbook (entry_date, day, activity, learning) VALUES (:entry_date, :day, :activity, :learning)");
        
        // Bind parameters
        $stmt->bindParam(':entry_date', $entry['date']);
        $stmt->bindParam(':day', date('l', strtotime($entry['date']))); // This will convert the date to a day of the week
        $stmt->bindParam(':activity', $entry['work_done']);
        $stmt->bindParam(':learning', $entry['remarks']);

        // Execute the statement
        $stmt->execute();
    }

    echo "Logbook saved successfully.";
    // You should add error handling and success confirmation
}
?>

</body>
</html>
