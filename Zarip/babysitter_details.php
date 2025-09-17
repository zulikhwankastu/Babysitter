<?php
require_once '_db.php';

$babysitter_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch babysitter data
$stmt = $dbc->prepare("SELECT * FROM babysitters WHERE id = ?");
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();
$babysitter = $result->fetch_assoc();

// Fetch reviews
$reviews = [];
$review_stmt = $dbc->prepare("SELECT * FROM reviews WHERE babysitter_id = ? ORDER BY created_at DESC");
$review_stmt->bind_param("i", $babysitter_id);
$review_stmt->execute();
$reviews_result = $review_stmt->get_result();
while ($row = $reviews_result->fetch_assoc()) {
    $reviews[] = $row;
}

// Render available days (reuse function)
function renderAvailability($availabilityStr) {
    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    $availableDays = array_map('trim', explode(",", $availabilityStr));
    $html = "";
    foreach ($days as $day) {
        $class = in_array($day, $availableDays) ? "available-day" : "unavailable-day";
        $shortDay = strtoupper(substr($day, 0, 2));
        $html .= "<span class='$class' title='$day'>$shortDay</span> ";
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Babysitter Details</title>
  <style>
    body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom right, #f2f6fa, #dbeeff);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px 40px;
            margin-bottom: 30px;
        }

        .profile-info p {
            font-size: 1.1rem;
            margin: 0;
        }

        .profile-pic {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-pic img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #3498db;
        }

        .about-box {
            background-color: #e8f6ff;
            padding: 1.5rem;
            border-left: 5px solid #3498db;
            border-radius: 10px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .availability span {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
            margin: 4px;
            font-size: 0.95rem;
        }

        .buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            justify-content: center;
        }

        .buttons a {
            text-decoration: none;
            padding: 12px 24px;
            background-color: #2ecc71;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .buttons a:hover {
            background-color: #27ae60;
        }

        .review {
            background-color: #f4f4f4;
            border-left: 5px solid #3498db;
            padding: 1.2rem;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .review strong {
            color: #34495e;
        }

        hr {
            margin: 40px 0;
        }
  </style>
</head>
<body>

    <div class="container"> 
        <div class="buttons">
                <a href="homepage.php ">Go back to homepage</a>
            </div>
        <?php if ($babysitter): ?>
            <h1><?php echo htmlspecialchars($babysitter['name']); ?></h1>

            <div class="profile-pic">
                <img src="<?php echo htmlspecialchars($babysitter['profile_image']); ?>" alt="Profile Picture">
            </div>

            <div class="profile-info">
                <p><strong>Age:</strong> <?php echo htmlspecialchars($babysitter['age']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($babysitter['gender']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($babysitter['email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($babysitter['address']); ?></p>
                <p><strong>Rate (Per Hour):</strong> RM <?php echo htmlspecialchars($babysitter['rate']); ?></p>
                <p><strong>Preferred Time:</strong> <?php echo htmlspecialchars($babysitter['preferred_time']); ?></p>
            </div>

            <h2>Available Days</h2>
            <div class="availability">
                <?php
                    $days = explode(',', $babysitter['available_days']);
                    foreach ($days as $day) {
                        echo "<span>" . htmlspecialchars(trim($day)) . "</span>";
                    }
                ?>
            </div>

            <h2>About Me</h2>
            <div class="about-box">
                <?php echo nl2br(htmlspecialchars($babysitter['description'])); ?>
            </div>

            <div class="buttons">
                <a href="book_babysitter.php?id=<?php echo $babysitter_id; ?>">Book Babysitter</a>
                <a href="contact_babysitter.php?id=<?php echo $babysitter_id; ?>">Contact Babysitter</a>
            </div>

            <hr>

            <h2>Reviews</h2>
            <?php
            $review_stmt = $dbc->prepare("SELECT * FROM reviews WHERE babysitter_id = ?");
            $review_stmt->bind_param("i", $babysitter_id);
            $review_stmt->execute();
            $review_result = $review_stmt->get_result();

           if ($review_result->num_rows > 0) {
    while ($review = $review_result->fetch_assoc()) {
        echo "<div class='review'>";
        echo "<strong>" . htmlspecialchars($review['reviewer_name']) . ":</strong><br>";

        // Display stars based on rating
        $rating = (int)$review['rating'];
        echo "<div class='rating'>";
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= $rating ? "★" : "☆";
        }
        echo "</div>";

        echo "<p>" . htmlspecialchars($review['content']) . "</p>";
        echo "</div><hr>";
    }
} else {
    echo "<p>No reviews available.</p>";
}
            ?>

               <div class="buttons">
               
                <a href="review_form.php?id=<?php echo $babysitter_id; ?>">Leave a review</a>
            </div>
        <?php else: ?>
            <p>Babysitter not found.</p>
        <?php endif; ?>


         
    </div>
</body>
</html>