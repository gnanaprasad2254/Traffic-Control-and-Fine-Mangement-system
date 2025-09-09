<?php
include '../../includes/db.php';
if (!isset($_SESSION['username'])) {
  header("Location: ../../Interface/admin_login.php");
  exit();
}

// Get all officers
$officers = [];
$result = mysqli_query($conn, "SELECT * FROM officer ORDER BY name ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $officers[] = $row;
}

// Get all violators with their violations
$violators = [];
$result = mysqli_query($conn, "
    SELECT u.*, COUNT(v.violation_id) as violation_count, 
           SUM(CASE WHEN v.status = 'Paid' THEN v.fine_amount ELSE 0 END) as total_paid,
           SUM(CASE WHEN v.status = 'Pending' THEN v.fine_amount ELSE 0 END) as total_pending
    FROM user u
    LEFT JOIN violation v ON u.user_id = v.user_id
    GROUP BY u.user_id
    ORDER BY violation_count DESC
");
while ($row = mysqli_fetch_assoc($result)) {
    $violators[] = $row;
}

// Get recent violations for the violations table
$recentViolations = [];
$result = mysqli_query($conn, "
    SELECT v.*, r.violation_type, o.name as officer_name, u.name as user_name
    FROM violation v
    JOIN rules r ON v.violation_type = r.rule_id
    JOIN officer o ON v.officer_id = o.officer_id
    JOIN user u ON v.user_id = u.user_id
    ORDER BY v.violation_date DESC
");
while ($row = mysqli_fetch_assoc($result)) {
    $recentViolations[] = $row;
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - Reports</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../src/output.css">
  <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .tab-button.active {
      background-color: #1e40af;
      color: white;
    }
    .chart-container {
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: white;
      border-radius: 0.5rem;
      padding: 1.5rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      text-align: center;
    }
    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1e40af;
      margin: 0.5rem 0;
    }
    .stat-label {
      font-size: 0.875rem;
      color: #6b7280;
    }
    .charts-container {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      margin-bottom: 2rem;
      justify-content: center;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 flex">
  
  <!-- Sidebar -->
  <?php include '../../includes/sidebar.php'; ?>

  <!-- Dashboard Area -->
  <div class="flex-1 p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto">

      <!-- Dashboard Title -->
      <h1 class="text-3xl font-semibold text-blue-900 mb-8">Reports Dashboard</h1>

      <!-- Tabs -->
      <div class="flex mb-6 bg-white rounded-lg shadow overflow-hidden">
        <button class="tab-button py-3 px-6 font-medium text-blue-900 active" data-tab="officers">Officers</button>
        <button class="tab-button py-3 px-6 font-medium text-blue-900" data-tab="violators">Violators</button>
        <button class="tab-button py-3 px-6 font-medium text-blue-900" data-tab="violations">Violations</button>
      </div>

      <!-- Officers Tab Content -->
      <div id="officers" class="tab-content active">
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
          <h2 class="text-2xl font-semibold text-blue-900 mb-6">Traffic Officers</h2>
          
          <?php
          // Calculate officer stats
          $activeOfficers = 0;
          $inactiveOfficers = 0;
          $stations = [];
          
          foreach ($officers as $officer) {
              if ($officer['status'] == 'active') {
                  $activeOfficers++;
              } else {
                  $inactiveOfficers++;
              }
              
              $station = $officer['station_name'] ?: 'Unknown';
              if (!isset($stations[$station])) {
                  $stations[$station] = 0;
              }
              $stations[$station]++;
          }
          ?>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-value"><?php echo count($officers); ?></div>
              <div class="stat-label">Total Officers</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?php echo $activeOfficers; ?></div>
              <div class="stat-label">Active Officers</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?php echo $inactiveOfficers; ?></div>
              <div class="stat-label">Inactive Officers</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?php echo count($stations); ?></div>
              <div class="stat-label">Different Stations</div>
            </div>
          </div>
          
          <div class="charts-container">
            <div class="chart-container">
              <canvas id="officerStatusChart"></canvas>
            </div>
            <div class="chart-container">
              <canvas id="officerStationChart"></canvas>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Badge Number</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Station</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($officers as $officer): ?>
                <tr class="hover:bg-blue-50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($officer['name']); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($officer['email']); ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($officer['badge_number']); ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($officer['station_name']); ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($officer['phone']); ?></td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                      <?php echo $officer['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                      <?php echo ucfirst($officer['status']); ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Violators Tab Content -->
      <div id="violators" class="tab-content">
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
          <h2 class="text-2xl font-semibold text-blue-900 mb-6">Traffic Violators</h2>
          
          <?php
          // Calculate violator stats
          $totalViolations = 0;
          $totalFinesPaid = 0;
          $totalFinesPending = 0;
          $repeatViolators = 0;
          $noViolations = 0;
          
          foreach ($violators as $violator) {
              $totalViolations += $violator['violation_count'];
              $totalFinesPaid += $violator['total_paid'];
              $totalFinesPending += $violator['total_pending'];
              
              if ($violator['violation_count'] > 1) {
                  $repeatViolators++;
              } elseif ($violator['violation_count'] == 0) {
                  $noViolations++;
              }
          }
          ?>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-value"><?php echo count($violators); ?></div>
              <div class="stat-label">Total Drivers</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?php echo $totalViolations; ?></div>
              <div class="stat-label">Total Violations</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?php echo $repeatViolators; ?></div>
              <div class="stat-label">Repeat Violators</div>
            </div>
            <div class="stat-card">
              <div class="stat-value">₹<?php echo number_format($totalFinesPaid + $totalFinesPending, 2); ?></div>
              <div class="stat-label">Total Fines</div>
            </div>
          </div>
          
          <div class="charts-container">
            <div class="chart-container">
              <canvas id="violatorDistributionChart"></canvas>
            </div>
            <div class="chart-container">
              <canvas id="finesStatusChart"></canvas>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Details</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violations</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fines</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($violators as $violator): ?>
                <tr class="hover:bg-blue-50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($violator['name']); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($violator['license']); ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($violator['vehicle_no']); ?></div>
                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($violator['rc']); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($violator['phone']); ?></div>
                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($violator['email']); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                      <?php echo $violator['violation_count'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                      <?php echo $violator['violation_count']; ?> violations
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-green-600">₹<?php echo number_format($violator['total_paid'], 2); ?> paid</div>
                    <div class="text-sm text-red-600">₹<?php echo number_format($violator['total_pending'], 2); ?> pending</div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Violations Tab Content -->
      <div id="violations" class="tab-content">
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
          <h2 class="text-2xl font-semibold text-blue-900 mb-6">Recent Violations</h2>
          
          <?php
          // Calculate violation stats
          $paidViolations = 0;
          $pendingViolations = 0;
          $appealedViolations = 0;
          $totalFineAmount = 0;
          $violationTypes = [];
          
          foreach ($recentViolations as $violation) {
              $totalFineAmount += $violation['fine_amount'];
              
              if ($violation['status'] == 'Paid') {
                  $paidViolations++;
              } elseif ($violation['status'] == 'Pending') {
                  $pendingViolations++;
              } elseif ($violation['status'] == 'Appealed') {
                  $appealedViolations++;
              }
              
              $type = $violation['violation_type'];
              if (!isset($violationTypes[$type])) {
                  $violationTypes[$type] = 0;
              }
              $violationTypes[$type]++;
          }
          
          arsort($violationTypes);
          $topViolationTypes = array_slice($violationTypes, 0, 5, true);
          ?>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-value"><?php echo count($recentViolations); ?></div>
              <div class="stat-label">Recent Violations</div>
            </div>
            <div class="stat-card">
              <div class="stat-value">₹<?php echo number_format($totalFineAmount, 2); ?></div>
              <div class="stat-label">Total Fines</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?php echo $paidViolations; ?></div>
              <div class="stat-label">Paid Violations</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?php echo $pendingViolations; ?></div>
              <div class="stat-label">Pending Violations</div>
            </div>
          </div>
          
          <div class="charts-container">
            <div class="chart-container">
              <canvas id="violationStatusChart"></canvas>
            </div>
            <div class="chart-container">
              <canvas id="violationTypeChart"></canvas>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violator</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violation</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fine Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($recentViolations as $violation): ?>
                <tr class="hover:bg-blue-50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <?php echo date('M d, Y', strtotime($violation['violation_date'])); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($violation['user_name']); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <?php echo htmlspecialchars($violation['vehicle_number']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($violation['violation_type']); ?></div>
                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($violation['location']); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <?php echo htmlspecialchars($violation['officer_name']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ₹<?php echo number_format($violation['fine_amount'], 2); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                      <?php 
                        if ($violation['status'] == 'Paid') echo 'bg-green-100 text-green-800';
                        elseif ($violation['status'] == 'Pending') echo 'bg-yellow-100 text-yellow-800';
                        elseif ($violation['status'] == 'Appealed') echo 'bg-blue-100 text-blue-800';
                        else echo 'bg-red-100 text-red-800';
                      ?>">
                      <?php echo $violation['status']; ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Tab functionality
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');
  
  tabButtons.forEach(button => {
    button.addEventListener('click', () => {
      // Remove active class from all buttons and contents
      tabButtons.forEach(btn => btn.classList.remove('active'));
      tabContents.forEach(content => content.classList.remove('active'));
      
      // Add active class to clicked button
      button.classList.add('active');
      
      // Show corresponding content
      const tabId = button.getAttribute('data-tab');
      document.getElementById(tabId).classList.add('active');
    });
  });

  // Officer Status Chart
  const officerStatusCtx = document.getElementById('officerStatusChart').getContext('2d');
  new Chart(officerStatusCtx, {
    type: 'pie',
    data: {
      labels: ['Active Officers', 'Inactive Officers'],
      datasets: [{
        data: [<?php echo $activeOfficers; ?>, <?php echo $inactiveOfficers; ?>],
        backgroundColor: ['#10B981', '#EF4444'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Officer Status Distribution',
          font: { size: 16 }
        },
        legend: { position: 'bottom' }
      }
    }
  });

  // Officer Station Chart
  const officerStationCtx = document.getElementById('officerStationChart').getContext('2d');
  new Chart(officerStationCtx, {
    type: 'pie',
    data: {
      labels: <?php echo json_encode(array_keys($stations)); ?>,
      datasets: [{
        data: <?php echo json_encode(array_values($stations)); ?>,
        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Officers by Station',
          font: { size: 16 }
        },
        legend: { position: 'bottom' }
      }
    }
  });

  // Violator Distribution Chart
  const violatorDistributionCtx = document.getElementById('violatorDistributionChart').getContext('2d');
  new Chart(violatorDistributionCtx, {
    type: 'pie',
    data: {
      labels: ['No Violations', '1 Violation', 'Repeat Violators'],
      datasets: [{
        data: [<?php echo $noViolations; ?>, <?php echo count($violators) - $repeatViolators - $noViolations; ?>, <?php echo $repeatViolators; ?>],
        backgroundColor: ['#10B981', '#3B82F6', '#EF4444'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Violator Distribution',
          font: { size: 16 }
        },
        legend: { position: 'bottom' }
      }
    }
  });

  // Fines Status Chart
  const finesStatusCtx = document.getElementById('finesStatusChart').getContext('2d');
  new Chart(finesStatusCtx, {
    type: 'pie',
    data: {
      labels: ['Paid Fines', 'Pending Fines'],
      datasets: [{
        data: [<?php echo $totalFinesPaid; ?>, <?php echo $totalFinesPending; ?>],
        backgroundColor: ['#10B981', '#F59E0B'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Fines Payment Status',
          font: { size: 16 }
        },
        legend: { position: 'bottom' }
      }
    }
  });

  // Violation Status Chart
  const violationStatusCtx = document.getElementById('violationStatusChart').getContext('2d');
  new Chart(violationStatusCtx, {
    type: 'pie',
    data: {
      labels: ['Paid', 'Pending', 'Appealed'],
      datasets: [{
        data: [<?php echo $paidViolations; ?>, <?php echo $pendingViolations; ?>, <?php echo $appealedViolations; ?>],
        backgroundColor: ['#10B981', '#F59E0B', '#3B82F6'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Violation Status',
          font: { size: 16 }
        },
        legend: { position: 'bottom' }
      }
    }
  });

  // Violation Type Chart
  const violationTypeCtx = document.getElementById('violationTypeChart').getContext('2d');
  new Chart(violationTypeCtx, {
    type: 'pie',
    data: {
      labels: <?php echo json_encode(array_keys($topViolationTypes)); ?>,
      datasets: [{
        data: <?php echo json_encode(array_values($topViolationTypes)); ?>,
        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Top Violation Types',
          font: { size: 16 }
        },
        legend: { position: 'bottom' }
      }
    }
  });
});
</script>

</body>
</html>