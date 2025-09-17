<?php
session_start();
include '_db.php';

if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

$parent_id = $_SESSION['parent_id'];
$success = $error = "";

// Handle profile and children update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parent details
    $name     = trim($_POST['name']);
    $age      = (int)trim($_POST['age']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $password = trim($_POST['password']);

    // Handle profile image upload
    $profile_image_path = null;
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "uploads/parents/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $filename = "parent_" . time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image_path = $target_file;
        } else {
            $error = "Failed to upload profile image.";
        }
    }

    // Update parent
    if (empty($error)) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE parents SET name=?, age=?, email=?, phone=?, address=?, password=?, profile_image=IFNULL(?, profile_image) WHERE id=?";
            $stmt = $dbc->prepare($sql);
            $stmt->bind_param("sisssssi", $name, $age, $email, $phone, $address, $hashed_password, $profile_image_path, $parent_id);
        } else {
            $sql = "UPDATE parents SET name=?, age=?, email=?, phone=?, address=?, profile_image=IFNULL(?, profile_image) WHERE id=?";
            $stmt = $dbc->prepare($sql);
            $stmt->bind_param("sissssi", $name, $age, $email, $phone, $address, $profile_image_path, $parent_id);
        }

        if ($stmt->execute()) {
            // Process children (update existing + insert new)
            if (isset($_POST['children']) && is_array($_POST['children'])) {
                foreach ($_POST['children'] as $child_id => $child_data) {
                    if ($child_id === "new") {
                        // Insert new children
                        foreach ($child_data as $new_child) {
                            $new_name = trim($new_child['name'] ?? '');
                            $new_age = (int)trim($new_child['age'] ?? 0);
                            if (!empty($new_name) && $new_age > 0) {
                                $insert_child = $dbc->prepare("INSERT INTO children (parent_id, child_name, child_age) VALUES (?, ?, ?)");
                                $insert_child->bind_param("isi", $parent_id, $new_name, $new_age);
                                $insert_child->execute();
                            }
                        }
                    } else {
                        // Update existing child
                        $child_name = trim($child_data['name'] ?? '');
                        $child_age  = (int)trim($child_data['age'] ?? 0);
                        if (!empty($child_name) && $child_age > 0) {
                            $child_id_int = (int)$child_id;
                            $update_child = $dbc->prepare("UPDATE children SET child_name=?, child_age=? WHERE id=? AND parent_id=?");
                            $update_child->bind_param("siii", $child_name, $child_age, $child_id_int, $parent_id);
                            $update_child->execute();
                        }
                    }
                }
            }

            if (empty($error)) {
                // Success, redirect now
                header("Location: parent_profile.php");
                exit();
            }
        } else {
            $error = "Error updating profile.";
        }
    }
}

// Fetch parent
$stmt = $dbc->prepare("SELECT * FROM parents WHERE id = ?");
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
$parent = $result->fetch_assoc();

// Fetch children
$children = [];
$child_query = $dbc->prepare("SELECT * FROM children WHERE parent_id = ?");
$child_query->bind_param("i", $parent_id);
$child_query->execute();
$child_result = $child_query->get_result();
while ($row = $child_result->fetch_assoc()) {
    $children[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Parent Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fefae0; padding: 20px; max-width: 700px; margin: auto; }
        h2, h3 { color: #3a5a40; }
        form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="number"], input[type="password"], textarea {
            width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;
        }
        button { margin-top: 20px; padding: 10px 20px; background: #588157; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .msg { margin-top: 15px; padding: 10px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        img.profile { margin-top: 10px; max-width: 150px; border-radius: 5px; }
        .child-block { background: #f3f3f3; padding: 15px; border-radius: 5px; margin-top: 15px; }
        .add-child-btn { margin-top: 15px; background: #8fbc8f; border: none; color: white; padding: 8px 12px; cursor: pointer; border-radius: 5px; }
    </style>
    <script>
        function addNewChild() {
            const container = document.getElementById('new-children-container');

            const index = container.children.length;
            const childDiv = document.createElement('div');
            childDiv.classList.add('child-block');

            childDiv.innerHTML = `
                <label>Child Name:</label>
                <input type="text" name="children[new][${index}][name]" required>

                <label>Child Age:</label>
                <input type="number" name="children[new][${index}][age]" required min="1" max="100">

                <button type="button" onclick="this.parentElement.remove()" style="margin-top:10px; background:#c94c4c; border:none; color:white; padding:5px 10px; border-radius:4px; cursor:pointer;">Remove</button>
            `;

            container.appendChild(childDiv);
        }
    </script>
</head>
<body>

<h2>Edit Your Profile</h2>

<?php if ($success): ?><div class="msg success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($parent['name']) ?>" required>

    <label>Age:</label>
    <input type="number" name="age" value="<?= htmlspecialchars($parent['age']) ?>" required min="1" max="120">

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($parent['email']) ?>" required>

    <label>Phone:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($parent['phone']) ?>">

    <label>Address:</label>
    <textarea name="address" rows="3"><?= htmlspecialchars($parent['address']) ?></textarea>

    <label>New Password (leave blank to keep current):</label>
    <input type="password" name="password">

    <label>Upload New Profile Image:</label>
    <input type="file" name="profile_image" accept="image/*">
    <?php if (!empty($parent['profile_image'])): ?>
        <br><img src="<?= htmlspecialchars($parent['profile_image']) ?>" alt="Profile Image" class="profile">
    <?php endif; ?>

    <h3>Your Children</h3>
    <?php if (!empty($children)): ?>
        <?php foreach ($children as $child): ?>
            <div class="child-block">
                <label>Child Name:</label>
                <input type="text" name="children[<?= $child['id'] ?>][name]" value="<?= htmlspecialchars($child['child_name']) ?>" required>

                <label>Child Age:</label>
                <input type="number" name="children[<?= $child['id'] ?>][age]" value="<?= htmlspecialchars($child['child_age']) ?>" required min="1" max="100">
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No children registered yet.</p>
    <?php endif; ?>

    <h3>Add New Children</h3>
    <div id="new-children-container"></div>
    <button type="button" class="add-child-btn" onclick="addNewChild()">+ Add Child</button>

    <button type="submit">Update Profile & Children</button>
</form>

</body>
</html>
