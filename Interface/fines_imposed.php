<?php
include '../includes/db.php';

// Check if officer is logged in
if (!isset($_SESSION['b_no'])) {
    header("Location: ./officer_login.php");
    exit();
}

// Get officer ID from session
$b_no = $_SESSION['b_no'];
$query = "SELECT v.*, 
                 r.violation_type, 
                 r.description AS violation_description,
                 u.name AS user_name,
                 u.phone AS user_phone,
                 u.license AS user_license
          FROM violation v
          JOIN rules r ON v.violation_type = r.rule_id
          LEFT JOIN user u ON v.user_id = u.user_id
          WHERE v.officer_id IN (SELECT officer_id FROM officer WHERE badge_number = ?)
          ORDER BY v.violation_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $b_no);
$stmt->execute();
$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines Imposed</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <style>
        .fine-card {
            min-width: 350px;
            transition: all 0.3s ease;
        }
        .fine-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .evidence-img {
            max-height: 200px;
            object-fit: contain;
        }
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #3b82f6 #f1f1f1;
        }
        .scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .scroll-container::-webkit-scrollbar-thumb {
            background-color: #3b82f6;
            border-radius: 20px;
        }
        .summary-card {
            transition: all 0.3s ease;
        }
        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../includes/header.php'; ?>
    
    <div class="flex">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 shadow-xl flex flex-col px-6 py-8">
            <h2 class="text-2xl font-bold text-white mb-8">Officer Panel</h2>
            <nav class="flex flex-col gap-4">
                <a href="../Interface/officer_dashboard.php" class="text-white font-medium hover:text-orange-300 flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>
                <a href="../Interface/fines_imposed.php" class="text-white font-medium hover:text-orange-300 flex items-center gap-3 p-2 rounded-lg bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    Imposed Fines
                </a>
                <a href="#" class="text-white font-medium hover:text-orange-300 flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Reports on You
                </a>
                <a href="#" class="text-white font-medium hover:text-orange-300 flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    Admin Messages
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-blue-700">
                <a href="../Backend/logout.php" class="flex items-center justify-center gap-2 w-full text-center bg-red-600 text-white font-semibold py-2 rounded-lg hover:bg-red-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                    </svg>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="max-w-7xl mx-auto">
                <!-- Page Header -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Fines Imposed</h1>
                        <p class="text-gray-600">View all fines you have issued</p>
                    </div>
                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg">
                        Total Fines: <?php echo count($fines); ?>
                    </div>
                </div>

                <?php if (empty($fines)): ?>
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <img src="../assets/images/no-fines.svg" alt="No fines" class="w-48 mx-auto mb-4">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-2">No Fines Issued Yet</h2>
                        <p class="text-gray-500">You haven't issued any traffic fines yet.</p>
                    </div>
                <?php else: ?>
                    <!-- Summary Statistics -->
                    <div class="bg-white rounded-xl shadow-md p-6 mb-8 summary-card">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Performance Summary</h2>
                            <span class="text-sm text-gray-500">As of <?php echo date('M d, Y') ?></span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-600 font-medium">Total Fines</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo count($fines) ?></p>
                                <p class="text-xs text-gray-500 mt-2"><?php echo count($fines) ?> violations recorded</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-100">
                                <p class="text-sm text-green-600 font-medium">Total Amount</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">₹<?php 
                                    $total = array_sum(array_column($fines, 'fine_amount'));
                                    echo number_format($total, 2) 
                                ?></p>
                                <p class="text-xs text-gray-500 mt-2">Total fines imposed</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-100">
                                <p class="text-sm text-purple-600 font-medium">Paid Fines</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">
                                    <?php 
                                        $paid = array_filter($fines, fn($f) => $f['status'] == 'Paid');
                                        echo count($paid);
                                    ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-2"><?php echo round(count($paid)/max(1,count($fines))*100) ?>% payment rate</p>
                            </div>
                        </div>
                    </div>

                    <!-- Fines List -->
                    <div class="mb-6 overflow-x-auto scroll-container">
                        <div class="flex space-x-6 pb-4">
                            <?php foreach ($fines as $fine):
                                $evidence_path = "../assets/images/fines/{$fine['violation_id']}/*";
                                $evidence_files = glob($evidence_path);
                                $has_evidence = !empty($evidence_files);
                            ?>
                                <div class="fine-card bg-white rounded-xl shadow-md overflow-hidden flex-shrink-0">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                                                    <?php echo $fine['status'] == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?php echo $fine['status'] ?>
                                                </span>
                                                <h3 class="text-xl font-bold text-gray-800 mt-2">
                                                    <?php echo htmlspecialchars($fine['violation_type']) ?>
                                                </h3>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($fine['violation_date'])) ?></p>
                                                <p class="text-2xl font-bold text-blue-600">₹<?php echo number_format($fine['fine_amount'], 2) ?></p>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="text-gray-600 mb-1">
                                                <span class="font-semibold">Vehicle:</span> <?php echo htmlspecialchars($fine['vehicle_number']) ?>
                                            </p>
                                            <?php if ($fine['user_name']): ?>
                                                <p class="text-gray-600 mb-1">
                                                    <span class="font-semibold">Violator:</span> <?php echo htmlspecialchars($fine['user_name']) ?>
                                                </p>
                                                <p class="text-gray-600">
                                                    <span class="font-semibold">License:</span> <?php echo htmlspecialchars($fine['user_license']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mb-4">
                                            <p class="text-gray-700">
                                                <?php echo htmlspecialchars($fine['violation_description']) ?>
                                            </p>
                                            <p class="text-sm text-gray-500 mt-2">
                                                <span class="font-semibold">Location:</span> <?php echo htmlspecialchars($fine['location']) ?>
                                            </p>
                                        </div>

                                        <?php if ($has_evidence): ?>
                                            <div class="mt-4 border-t pt-4">
                                                <h4 class="font-semibold text-gray-700 mb-2">Evidence</h4>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <?php foreach ($evidence_files as $file):
                                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                            <div class="relative group">
                                                                <a href="<?php echo $file ?>" target="_blank" class="block">
                                                                    <img src="<?php echo $file ?>" alt="Evidence" class="evidence-img rounded-lg border hover:shadow-md transition">
                                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                                        </svg>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        <?php elseif ($ext === 'pdf'): ?>
                                                            <div class="border rounded-lg p-3 bg-gray-50 flex flex-col items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                </svg>
                                                                <a href="<?php echo $file ?>" download class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                                    Download PDF
                                                                </a>
                                                                <span class="text-xs text-gray-500 mt-1"><?php echo round(filesize($file) / 1024) ?> KB</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Simple animation for cards when they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fine-card, .summary-card').forEach(card => {
            card.style.opacity = 0;
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>