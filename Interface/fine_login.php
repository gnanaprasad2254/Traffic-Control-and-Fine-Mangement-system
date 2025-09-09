<?php
session_name('officer_session');    
include '../includes/db.php'; // adjust path if needed

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $badge_number = trim($_POST['badge_number']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT officer_id, badge_number, password_hash, status FROM officer WHERE badge_number = ?");
    $stmt->bind_param('s', $badge_number);
    $stmt->execute();
    $stmt->store_result();
 
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($officer_id, $db_badge_number, $db_password, $status );
        $stmt->fetch();

        // Direct password comparison (no hashing for now)
        if (password_verify($password, $db_password) && $status == 'active') {
            // Password matched
            
            $_SESSION['officer_id'] = $officer_id;
            $_SESSION['badge_number'] = $db_badge_number;

            header('Location: fine_generate.php'); 
            exit;
        } else {
            $error = 'Invalid password! or Inactive ';
        }
    } else {
        $error = 'Officer not found!';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Officer Login</title>
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
</head>
<body class="bg-blue-950 min-h-screen flex items-center justify-center">

  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-900">Fine Generate Login</h2>

    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-4">
        <label class="block text-blue-900 mb-2" for="badge_number">Badge Number</label>
        <input class="w-full border border-gray-300 rounded px-3 py-2" type="text" name="badge_number" id="badge_number" required>
      </div>

      <div class="mb-6">
        <label class="block text-blue-900 mb-2" for="password">Password</label>
        <input class="w-full border border-gray-300 rounded px-3 py-2" type="password" name="password" id="password" required>
      </div>

      <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded" type="submit">
        Login
      </button>
    </form>
  </div>

</body>
</html>
