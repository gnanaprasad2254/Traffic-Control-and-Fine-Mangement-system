<?php
session_name("superadmin");
include '../includes/db.php';

if (!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] !== true) {
    http_response_code(403);
    echo "<h2>Access Denied.</h2>";
    exit;
}

$query = "SELECT * FROM `admin` WHERE `status` = 'email verified'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Admins</title>
    <link rel="stylesheet" href="../src/output.css">
</head>
<body class="bg-gray-100 font-sans p-10">
    <!-- Logout Button -->
    <div class="flex justify-end mb-6">
        <form action="../Backend/superadmin_logout.php" method="POST">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Logout
            </button>
        </form>
    </div>

    <h1 class="text-3xl font-bold text-center mb-8">Pending Admin Confirmations</h1>

    <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-xl">
        <?php if (mysqli_num_rows($result) === 0): ?>
            <p class="text-center text-gray-700">No pending admin registrations.</p>
        <?php else: ?>
            <div class="grid gap-6">
                <?php while ($admin = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-gray-50 p-5 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start">
                        <img src="../assets/images/admin/<?= htmlspecialchars($admin['admin_id']) ?>/profile.png" alt="Admin Photo" class="w-32 h-32 object-cover rounded-full border-2 border-blue-400 mr-6 mb-4 md:mb-0">
                        
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-blue-900"><?= htmlspecialchars($admin['name']) ?></h2>
                            <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($admin['phone']) ?></p>
                            <p><strong>Role:</strong> <?= htmlspecialchars($admin['role']) ?></p>
                            <p><strong>Designation:</strong> <?= htmlspecialchars($admin['designation']) ?></p>
                            <p><strong>Address:</strong> <?= htmlspecialchars($admin['address']) ?></p>
                            <img src="../assets/images/admin/<?= htmlspecialchars($admin['admin_id']) ?>/id.png" alt="Admin id" class="w-[75%] h-32 object-cover rounded-[40px] border-2 border-blue-400 mr-6 mb-4 md:mb-0">
                            <form method="POST" action="../Backend/approve_admin.php" class="mt-4 flex gap-4">
                                <input type="hidden" name="admin_id" value="<?= $admin['admin_id'] ?>">
                                <button name="action" value="approve" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Approve</button>
                                <button name="action" value="reject" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reject</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
