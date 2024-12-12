<?php
session_start();
require 'db.php';

global $db;

// Перевірка авторизації
if (!isset($_SESSION['is_authenticated']) || !$_SESSION['is_authenticated']) {
    header("Location: login_form.html");
    exit;
}

// Отримання ID користувача з сесії
$user_id = $_SESSION['user_id'];

// Отримання даних користувача з бази
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Ініціалізація змінних для повідомлень
$error = '';
$success = '';

// Обробка форми
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $birthday = trim($_POST['birthday']);

    if (empty($name) || empty($surname) || empty($email) || empty($phone) || empty($birthday)) {
        $error = "All fields are required.";
    } else {
        // Оновлення даних у базі
        $stmt = $db->prepare("UPDATE users SET name = ?, surname = ?, email = ?, phone = ?, birthday = ? WHERE id = ?");
        $stmt->execute([$name, $surname, $email, $phone, $birthday, $user_id]);

        // Повторне отримання оновлених даних з бази
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Оновлення сесії
        $_SESSION['user_name'] = $user['name'];

        $success = "Profile updated successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
</head>
<body>
<h1>Edit Profile</h1>

<?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<form method="POST" action="edit_profile.php">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

    <label for="surname">Surname:</label>
    <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br>

    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required><br>

    <button type="submit">Save Changes</button>
</form>

<p><a href="index.php">Back to Home</a></p>
</body>
</html>
