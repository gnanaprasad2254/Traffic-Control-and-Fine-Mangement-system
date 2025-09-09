<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Access Portal - E-Fine System</title>
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
  <style>
    .access-card {
      transition: all 0.3s ease;
      min-width: 280px;
    }
    .access-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body class="bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 min-h-screen flex flex-col">

<?php
include '../includes/header.php';

$role = isset($_GET['role']) ? strtolower($_GET['role']) : null;
if ($role === 'admin') {
    $title = "Administrator Portal";
    $login_link = "../Interface/admin_login.php";
    $signup_link = "../Interface/sign_up.php";
    $button_color = "from-purple-600 to-purple-800";
    $border_color = "border-purple-500 text-purple-600";
    $icon = "admin-icon.png";
    $description = "Access the administration dashboard for system configuration, user management, and analytics.";
} elseif ($role === 'officer') {
    $title = "Traffic Officer Portal";
    $login_link = "../Interface/officer_login.php";
    $signup_link = "../Interface/sign_up.php";
    $button_color = "from-teal-600 to-teal-800";
    $border_color = "border-teal-500 text-teal-600";
    $icon = "officer-icon.png";
    $description = "Access tools for issuing fines, verifying documents, and managing traffic violations.";
} else {
    echo '<div class="flex-1 flex justify-center items-center text-white text-2xl font-bold">Invalid Access Role Specified</div>';
    include '../includes/footer.php';
    exit;
}
?>

<div class="flex-1 flex flex-col items-center justify-center px-4 py-12">
  <div class="max-w-md w-full bg-gray-800 rounded-xl p-8 shadow-lg border-t-4 <?= $border_color ?> mb-10 text-center">
    <img src="../assets/images/<?= $icon ?>" alt="<?= $title ?>" class="w-20 h-20 mx-auto mb-6">
    <h1 class="text-3xl font-bold text-white mb-4"><?= htmlspecialchars($title) ?></h1>
    <p class="text-gray-300 mb-8"><?= $description ?></p>
    
    <div class="flex flex-col space-y-4">
      <a href="<?= htmlspecialchars($login_link) ?>" 
         class="bg-gradient-to-r <?= $button_color ?> text-white px-6 py-3 rounded-lg text-lg font-semibold transition hover:opacity-90">
        Login to Your Account
      </a>
      
      <a href="<?= htmlspecialchars($signup_link) ?>" 
         class="border-2 <?= $border_color ?>px-6 py-3 rounded-lg text-lg font-semibold transition hover:bg-white">
        Register New Account
      </a>
      
      <?php if($role == 'officer'): ?>
      <a href="../Interface/fine_generate.php" 
         class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-3 rounded-lg text-lg font-semibold transition hover:opacity-90">
        Generate New Fine
      </a>
      <?php endif; ?>
    </div>
  </div>

  <div class="text-center text-gray-400">
    <p>Need help accessing your account? <a href="../Interface/contact.php" class="text-blue-400 hover:underline">Contact support</a></p>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>