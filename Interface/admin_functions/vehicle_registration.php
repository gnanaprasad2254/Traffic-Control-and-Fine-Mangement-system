<?php
include '../../includes/db.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

function contactEmail($email, $sub, $body)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sidddareddyrajeshreddy@gmail.com';
    $mail->Password = 'epmrpivxwotkbnfy';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('sidddareddyrajeshreddy@gmail.com');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = $sub;
    $mail->Body    = $body;
    $mail->send();
}

if (!isset($_SESSION['username'])) {
    header("Location: ../../Interface/admin_login.php");
    exit();
}

$query = "SELECT * FROM `user` WHERE `status` = 'email verified'";
$result = mysqli_query($conn, $query);

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM `user` WHERE `user_id` = '$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $email = $row['email'];
    $action = $_GET['action'];
    $sub = "VEHICLE REGISTRATION PROCESS";

    if ($action == 'approve') {
        mysqli_query($conn, "UPDATE `user` SET `status` = 'active' WHERE `user_id` = $id");
        $body = "Dear $name,<br>Your vehicle has been <b>successfully</b> registered with the E-Fine.<br>Thank You.";
    } elseif ($action == 'reject') {
        mysqli_query($conn, "DELETE FROM `user` WHERE `user_id`= $id");
        $path = '../../assets/users/' . $row['vehicle_no'];
        if (is_dir($path)) {
            array_map('unlink', glob("$path/*.*"));
            rmdir($path);
        }
        $body = "Dear $name,<br>Your vehicle registration was <b>rejected</b> in the E-Fine system.<br>Please re-register.<br>Thank You.";
    }

    contactEmail($email, $sub, $body);
    header("Location: vehicle_registration.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Registration Approvals</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../src/output.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 flex">
    <?php include '../../includes/sidebar.php'; ?>

    <div class="flex-1 p-8 text-white">
        <h1 class="text-3xl font-bold mb-6 text-teal-400">Vehicle Registration Approvals</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-gradient-to-br from-green-100 to-teal-50 border border-green-300 rounded-2xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition duration-300 ease-in-out">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-blue-900"><?php echo htmlspecialchars($row['name']); ?></h2>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-300 text-yellow-900">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </div>

                        <div class="space-y-2 mb-4 text-blue-800">
                            <p><b>Vehicle No:</b> <?php echo htmlspecialchars($row['vehicle_no']); ?></p>
                            <p><b>Chassis No:</b> <?php echo htmlspecialchars($row['chassis']); ?></p>
                            <p><b>Engine No:</b> <?php echo htmlspecialchars($row['engine no']); ?></p>
                        </div>

                        <div class="flex flex-wrap gap-4 mb-6">
                            <?php
                            $docs = ['aadhar.png' => 'Aadhar', 'photo.png' => 'Profile', 'rc.png' => 'RC', 'license.png' => 'License'];
                            foreach ($docs as $file => $label):
                            ?>
                                <div class="relative group">
                                    <a href="../../assets/images/users/<?php echo htmlspecialchars($row['vehicle_no']); ?>/<?php echo $file; ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="../../assets/images/users/<?php echo htmlspecialchars($row['vehicle_no']); ?>/<?php echo $file; ?>"
                                            alt="No <?php echo $label; ?>"
                                            class="w-20 h-20 rounded-lg border-2 border-blue-200 object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex gap-4">
                            <a href="?action=approve&id=<?php echo $row['user_id']; ?>" onclick="return confirm('Approve this registration?')"
                                class="flex-1 text-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg shadow-sm transition">
                                Approve
                            </a>
                            <a href="?action=reject&id=<?php echo $row['user_id']; ?>" onclick="return confirm('Reject this registration?')"
                                class="flex-1 text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg shadow-sm transition">
                                Reject
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-gray-400">No pending vehicle registrations found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>