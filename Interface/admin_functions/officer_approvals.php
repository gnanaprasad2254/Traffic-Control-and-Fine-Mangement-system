<?php
include '../../includes/db.php';
if (!isset($_SESSION['username'])) {
    header("Location: ../../Interface/admin_login.php");
    exit();
}
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

function contactEmail($email, $sub, $body)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sidddareddyrajeshreddy@gmail.com';
    $mail->Password   = 'epmrpivxwotkbnfy';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom('sidddareddyrajeshreddy@gmail.com');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = $sub;
    $mail->Body    = $body;
    $mail->send();
}

$query = "SELECT * FROM `officer` WHERE `status` = 'email verified'";
$result = mysqli_query($conn, $query);

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    $officerQuery = mysqli_query($conn, "SELECT * FROM `officer` WHERE `officer_id` = '$id'");
    $row = mysqli_fetch_assoc($officerQuery);
    $name = $row['name'];
    $email = $row['email'];
    $sub = "OFFICER APPROVALS";

    if ($action === 'approve') {
        mysqli_query($conn, "UPDATE `officer` SET `status` = 'active' WHERE `officer_id` = $id");
        $body = "Dear Officer $name,<br>Your application for the position of an official in the E-Fine system has been <b>Approved</b>.<br>Thank you.";
    } elseif ($action === 'reject') {
        mysqli_query($conn, "DELETE FROM `officer` WHERE `officer_id` = $id");
        $path = '../../assets/officer/' . $row['officer_id'];
        if (is_dir($path)) {
            array_map('unlink', glob("$path/*.*"));
            rmdir($path);
        }
        $body = "Dear Officer $name,<br>Your application for the position of an official in the E-Fine system has been <b>Rejected</b>.<br>Please contact the concerned higher officer.";
    }

    contactEmail($email, $sub, $body);
    header("Location: officer_approvals.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Approvals</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../src/output.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-action {
            transition: transform 0.2s, background-color 0.2s;
        }

        .btn-action:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 flex">

    <?php include '../../includes/sidebar.php'; ?>

    <div class="flex-1 p-8 text-white">
        <h1 class="text-3xl text-orange-300 font-bold mb-6">Officer Approvals</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-gradient-to-br from-rose-100 to-pink-50 border border-rose-300 rounded-2xl p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition duration-300 ease-in-out">


                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-blue-900"><?php echo htmlspecialchars($row['name']); ?></h2>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-300 text-yellow-900">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </div>

                        <div class="space-y-2 mb-4 text-blue-800">
                            <p><b>Badge No:</b> <?php echo htmlspecialchars($row['badge_number']); ?></p>
                            <p><b>Station Name:</b> <?php echo htmlspecialchars($row['station_name']); ?></p>
                            <p><b>Phone:</b> <?php echo htmlspecialchars($row['phone']); ?></p>
                            <p><b>Email:</b> <?php echo htmlspecialchars($row['email']); ?></p>
                        </div>

                        <div class="flex flex-wrap gap-4 mb-6">
                            <?php
                            $docs = ['id.png' => 'ID Card', 'profile.png' => 'Profile'];
                            foreach ($docs as $file => $label):
                            ?>
                                <div class="relative group">
                                    <a href="../../assets/images/officer/<?php echo htmlspecialchars($row['officer_id']); ?>/<?php echo $file; ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="../../assets/images/officer/<?php echo htmlspecialchars($row['officer_id']); ?>/<?php echo $file; ?>"
                                            alt="No <?php echo $label; ?>"
                                            class="w-20 h-20 rounded-lg border-2 border-blue-200 object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex gap-4">
                            <a href="?action=approve&id=<?php echo $row['officer_id']; ?>" onclick="return confirm('Approve this officer?')"
                                class="flex-1 text-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg shadow-sm transition">
                                Approve
                            </a>
                            <a href="?action=reject&id=<?php echo $row['officer_id']; ?>" onclick="return confirm('Reject this officer?')"
                                class="flex-1 text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg shadow-sm transition">
                                Reject
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-gray-400">No pending officer approvals found.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function confirmAction(action) {
            return confirm(`Are you sure you want to ${action} this officer?`);
        }
    </script>

</body>

</html>