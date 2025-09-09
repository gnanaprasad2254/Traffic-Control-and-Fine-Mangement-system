<?php
include '../includes/db.php';

if (!isset($_SESSION['b_no'])) {
    header("Location: ./officer_login.php");
    exit();
}

$b_no = $_SESSION['b_no'];
$query = "SELECT `officer_id` FROM `officer` WHERE `badge_number` = '$b_no'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$officer_id = $row['officer_id'];

$total_fines = 0;
$pending_payments = 0;
$resolved_cases = 0;

// Query for total fines
$query = "SELECT COUNT(*) as total FROM violation WHERE officer_id = $officer_id";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_fines = $row['total'];
}

// Query for pending payments
$query = "SELECT COUNT(*) as pending FROM violation WHERE officer_id = $officer_id AND status = 'Pending'";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pending_payments = $row['pending'];
}

// Query for resolved cases
$query = "SELECT COUNT(*) as resolved FROM violation WHERE officer_id = $officer_id AND status = 'Paid'";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $resolved_cases = $row['resolved'];
}

// Get recent violations
$recent_violations = [];
$query = "SELECT v.violation_id, v.vehicle_number, v.violation_date, v.fine_amount, v.status 
          FROM violation v
          WHERE v.officer_id = $officer_id
          ORDER BY v.violation_date DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recent_violations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Officer Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .count-up { font-size: 2.5rem; font-weight: bold; }
    
    /* Slider animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .rule-slide {
      animation: fadeIn 0.8s ease-out;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 flex">
  
  <!-- Sidebar -->
  <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 shadow-xl flex flex-col px-6 py-8">
    <h2 class="text-2xl font-bold text-white mb-8">Officer Panel</h2>
    <nav class="flex flex-col gap-4">
      <a href="../Interface/officer_dashboard.php" class="text-white font-medium hover:text-orange-300 flex items-center gap-3 p-2 rounded-lg bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        Dashboard
      </a>
      <a href="../Interface/fines_imposed.php" class="text-white font-medium hover:text-orange-300 flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700">
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

  <!-- Dashboard Area -->
  <div class="flex-1 p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto">

      <!-- Dashboard Title -->
      <h1 class="text-3xl font-semibold text-blue-900 mb-8">Officer Dashboard</h1>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-gradient-to-br from-blue-100 to-blue-200 text-blue-900 p-6 rounded-2xl shadow-lg hover:scale-105 transform transition">
          <h2 class="text-lg font-semibold">Total Fines Issued</h2>
          <p id="totalFines" class="count-up mt-3"><?php echo $total_fines; ?></p>
        </div>
        <div class="bg-gradient-to-br from-pink-100 to-pink-200 text-pink-900 p-6 rounded-2xl shadow-lg hover:scale-105 transform transition">
          <h2 class="text-lg font-semibold">Pending Payments</h2>
          <p id="pendingPayments" class="count-up mt-3"><?php echo $pending_payments; ?></p>
        </div>
        <div class="bg-gradient-to-br from-green-100 to-green-200 text-green-900 p-6 rounded-2xl shadow-lg hover:scale-105 transform transition">
          <h2 class="text-lg font-semibold">Resolved Cases</h2>
          <p id="resolvedCases" class="count-up mt-3"><?php echo $resolved_cases; ?></p>
        </div>
      </div>

      <!-- Recent Fines Table -->
      <div class="bg-white text-blue-900 rounded-2xl shadow-md p-6 mb-16">
        <h2 class="text-2xl font-semibold mb-6">Recent Fines Issued</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="bg-gray-100">
                <th class="py-3 px-6 text-left">Challan No.</th>
                <th class="py-3 px-6 text-left">Vehicle No.</th>
                <th class="py-3 px-6 text-left">Date</th>
                <th class="py-3 px-6 text-left">Amount</th>
                <th class="py-3 px-6 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recent_violations)): ?>
                <?php foreach ($recent_violations as $violation): ?>
                  <tr class="border-t">
                    <td class="py-3 px-6">CH<?php echo str_pad($violation['violation_id'], 5, '0', STR_PAD_LEFT); ?></td>
                    <td class="py-3 px-6"><?php echo htmlspecialchars($violation['vehicle_number']); ?></td>
                    <td class="py-3 px-6"><?php echo date('Y-m-d', strtotime($violation['violation_date'])); ?></td>
                    <td class="py-3 px-6">₹<?php echo number_format($violation['fine_amount'], 2); ?></td>
                    <td class="py-3 px-6 <?php echo $violation['status'] == 'Paid' ? 'text-green-600' : 'text-red-600'; ?> font-medium">
                      <?php echo $violation['status']; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="py-6 text-center text-gray-500">No fines issued yet</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

<script>
function animateCounter(id, target) {
  const element = document.getElementById(id);
  let current = 0;
  const increment = target / 100;
  function update() {
    current += increment;
    if (current < target) {
      element.innerText = Math.floor(current);
      requestAnimationFrame(update);
    } else {
      element.innerText = target;
    }
  }
  update();
}

document.addEventListener('DOMContentLoaded', () => {
  animateCounter('totalFines', <?php echo $total_fines; ?>);
  animateCounter('pendingPayments', <?php echo $pending_payments; ?>);
  animateCounter('resolvedCases', <?php echo $resolved_cases; ?>);
});
</script>

</body>
</html>