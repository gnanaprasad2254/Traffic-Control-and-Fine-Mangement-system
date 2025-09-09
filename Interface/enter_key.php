<?php
session_name("superadmin");
session_start();

$SUPER_ADMIN_KEY = "SUPERADMIN";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = $_POST['access_key'] ?? '';
    if ($key === $SUPER_ADMIN_KEY) {
        $_SESSION['superadmin'] = true;
        header("Location: ../Interface/_verify_admins_x91h.php");
        exit;
    } else {
        $error = "Invalid key.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Access</title>
    <link rel="stylesheet" href="../src/output.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <form method="POST" class="bg-white shadow-xl p-10 rounded-xl space-y-4">
        <h2 class="text-xl font-bold text-center text-blue-900">Super Admin Access</h2>
        <input type="password" name="access_key" placeholder="Enter Secret Key" required
               class="w-full border border-gray-300 p-3 rounded-md focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Enter</button>
        <?php if (isset($error)): ?>
            <p class="text-red-600 text-sm text-center"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
