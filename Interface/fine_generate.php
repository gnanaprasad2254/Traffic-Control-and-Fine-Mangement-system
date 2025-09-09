<?php
session_name('officer_session');
include '../includes/db.php';
require '../assets/components/contact_mail.php';
require '../vendor/fpdf/fpdf/original/fpdf.php';

if (!isset($_SESSION['officer_id'])) {
  header('Location: fine_login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $vehicleNumber = $_POST['vehicle_number'];
  $ownerName = $_POST['owner_name'];
  $violationType = $_POST['violation_type'];
  $fineAmount = $_POST['fine_amount'];
  $location = $_POST['location'];
  $date = date('Y-m-d H:i:s');

  $userQuery = $conn->prepare("SELECT `user_id`,`chassis`, `engine no`, `email`, `name` FROM `user` WHERE `vehicle_no` = ?");
  $userQuery->bind_param('s', $vehicleNumber);
  $userQuery->execute();
  $userQuery->bind_result($userId, $chassisNumber, $engineNumber, $email, $name);

  if (!$userQuery->fetch()) {
    $chassisNumber = '';
    $engineNumber = '';
    $userId = '';
  }
  $userQuery->close();

  $officerId = $_SESSION['officer_id'];
  $ruleId = $violationType;
  $violationText = '';

  // Fetch violation description
  $violationQuery = mysqli_query($conn, "SELECT `violation_type`, `description`, `rule_number` FROM `rules` WHERE `rule_id` = '$ruleId'");
  if ($row = mysqli_fetch_assoc($violationQuery)) {
    $violationText = $row['description'];
    $violationTypeText = $row['violation_type'];
    $violationSection = $row['rule_number'];
  }
  mysqli_free_result($violationQuery);

  if (empty($violationText)) {
    die("Violation type not found. Cannot proceed.");
  }

  $violation_date = date("Y-m-d");
  $sql = "INSERT INTO `violation`(`officer_id`, `user_id`, `vehicle_number`, `chassis_number`, `engine_number`, `violation_type`, `violation_date`, `location`, `fine_amount`, `status`) 
          VALUES ('$officerId', '$userId','$vehicleNumber','$chassisNumber','$engineNumber','$violationType','$violation_date','$location', '$fineAmount', 'pending')";
  mysqli_query($conn, $sql);

  $fine_id = mysqli_insert_id($conn);

  $fineDir = "../assets/images/fines/$fine_id/";
  if (!is_dir($fineDir)) {
    mkdir($fineDir, 0755, true);
  }

  $photoPath = null;
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
      die('Error: The uploaded file is not a valid image. Allowed formats: JPG, JPEG, PNG, GIF.');
    }

    $photoPath = $fineDir . 'evidence.' . $fileExtension;
    move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
  }

  $historyQuery = mysqli_query($conn, "SELECT v.violation_date, r.violation_type, v.fine_amount, v.status, v.location 
  FROM violation v 
  JOIN rules r ON v.violation_type = r.rule_id 
  WHERE v.vehicle_number = '$vehicleNumber' 
  ORDER BY v.violation_date DESC");
  $historyCount = mysqli_num_rows($historyQuery);
  $pdf = new FPDF();
  $pdf->AddPage();

  $pdf->SetTitle('Traffic Violation Receipt');
  $pdf->SetAuthor('Fine-T System');

  $logoPath = '../assets/images/fine-t_logo-removebg-preview.png';
  if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 10, 6, 30);
  }

  $pdf->SetFont('Arial', 'B', 16);
  $pdf->SetTextColor(0, 51, 102); // Dark blue color
  $pdf->Cell(0, 35, 'TRAFFIC VIOLATION RECEIPT', 0, 1, 'C');

  // Add decorative line
  $pdf->SetDrawColor(255, 153, 0);
  $pdf->SetLineWidth(0.5);
  $pdf->Line(10, 40, 200, 40);
  $pdf->Ln(10);

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetFillColor(230, 230, 255);
  $pdf->Cell(0, 8, 'VIOLATION DETAILS', 0, 1, 'L', true);
  $pdf->Ln(2);

  $pdf->SetFont('Arial', '', 11);
  $pdf->SetTextColor(0, 0, 0); 

  $fill = false;
  $pdf->SetFillColor(245, 245, 245); 

  $pdf->Cell(50, 8, 'Receipt No:', 0, 0, 'L', $fill);
  $pdf->Cell(0, 8, 'FT-' . str_pad($fine_id, 6, '0', STR_PAD_LEFT), 0, 1, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Date & Time:', 0, 0, 'L', $fill);
  $pdf->Cell(0, 8, $date, 0, 1, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Owner Name:', 0, 0, 'L', $fill);
  $pdf->Cell(0, 8, $ownerName, 0, 1, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Vehicle Number:', 0, 0, 'L', $fill);
  $pdf->Cell(0, 8, $vehicleNumber, 0, 1, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Violation Type:', 0, 0, 'L', $fill);
  $pdf->Cell(0, 8, $violationTypeText, 0, 1, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Violation Details:', 0, 0, 'L', $fill);
  $pdf->MultiCell(0, 8, $violationText, 0, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Section:', 0, 0, 'L', $fill);
  $pdf->MultiCell(0, 8, $violationSection, 0, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Fine Amount (Rs):', 0, 0, 'L', $fill);
  $pdf->Cell(0, 8, number_format($fineAmount, 2), 0, 1, 'L', $fill);
  $fill = !$fill;

  $pdf->Cell(50, 8, 'Location:', 0, 0, 'L', $fill);
  $pdf->Cell(0, 8, $location, 0, 1, 'L', $fill);

  $pdf->Ln(10);

  // Evidence photo section
  if ($photoPath && file_exists($photoPath)) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(230, 230, 255);
    $pdf->Cell(0, 8, 'EVIDENCE PHOTO', 0, 1, 'L', true);
    $pdf->Ln(2);

    // Center the image with border
    $pdf->SetX(($pdf->GetPageWidth() - 120) / 2);
    $pdf->Image($photoPath, $pdf->GetX(), null, 120, 0, '', '../assets/components/view_fine.php?id=' . $fine_id);
    $pdf->Ln(10);
  }

  // Violation history section
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetFillColor(230, 230, 255);
  $pdf->Cell(0, 8, 'VIOLATION HISTORY (Last 5 Records)', 0, 1, 'L', true);
  $pdf->Ln(2);

  if ($historyCount > 0) {
    // Reset pointer to beginning in case we already fetched some rows
    mysqli_data_seek($historyQuery, 0);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell(40, 8, 'Date', 1, 0, 'C', true);
    $pdf->Cell(70, 8, 'Violation Type', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Amount (Rs)', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Status', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 9);
    $counter = 0;
    while ($row = mysqli_fetch_assoc($historyQuery)) {
      if ($counter >= 5) break;

      $fill = $counter % 2 == 0;
      $pdf->SetFillColor($fill ? 245 : 255);

      // Format date to be more readable
      $formattedDate = date('d/m/Y', strtotime($row['violation_date']));

      $pdf->Cell(40, 8, $formattedDate, 1, 0, 'C', $fill);
      $pdf->Cell(70, 8, $row['violation_type'], 1, 0, 'L', $fill);
      $pdf->Cell(30, 8, number_format($row['fine_amount'], 2), 1, 0, 'R', $fill);
      $pdf->Cell(30, 8, ucfirst($row['status']), 1, 1, 'C', $fill);

      $counter++;
    }

    if ($historyCount > 5) {
      $pdf->SetFont('Arial', 'I', 8);
      $pdf->Cell(0, 8, '* Showing 5 of ' . $historyCount . ' total violations', 0, 1, 'R');
    }
  } else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 8, 'No previous violations found for this vehicle', 0, 1, 'C');
  }
  $pdf->Ln(10);

  // Payment instructions section
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetFillColor(230, 230, 255);
  $pdf->Cell(0, 8, 'PAYMENT INSTRUCTIONS', 0, 1, 'L', true);
  $pdf->Ln(2);

  $pdf->SetFont('Arial', '', 10);
  $instructions = [
    "1. Online Payment: Visit our website and enter your vehicle number to pay online.",
    "2. Bank Payment: Pay at any authorized bank branch with this receipt number.",
    "3. Mobile Payment: Use our official app to scan the QR code and pay.",
    "",
    "* Payment must be made within 14 days to avoid additional penalties.",
    "* Keep this receipt as proof of payment."
  ];
  echo $name;
  echo $email;
  $body = "$name, You have been imposed a fine with Fine Number: $fine_id for the violation of $violationText under section $violationSection.";
  $sub = "FINE GENERATED NEAR $location on Vehicle with $vehicleNumber";
  contactEmail($email, $sub, $body);
  foreach ($instructions as $line) {
    $pdf->Cell(0, 6, $line, 0, 1);
  }


  $pdf->SetY(-20);
  $pdf->SetFont('Arial', 'I', 8);
  $pdf->SetTextColor(100, 100, 100);
  $pdf->Cell(0, 6, 'Generated by Fine-T System on ' . date('Y-m-d H:i:s'), 0, 0, 'C');
  $pdf->Ln();
  $pdf->Cell(0, 6, 'For any queries, contact: sidddareddyrajeshreddy@gmail.com', 0, 0, 'C');

  ob_end_clean();
  $pdfFileName = $fineDir . 'receipt.pdf';
  $pdf->Output('F', $pdfFileName);
  $pdfFileName = 'receipt_' . $fine_id . '.pdf';
  $pdf->Output('D', $pdfFileName);
  mysqli_free_result($historyQuery);
  header("Location: fine_generate.php?success=1&vehicle=" . urlencode($vehicleNumber));
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Generate Fine</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../src/output.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .history-item:nth-child(even) {
      background-color: #f8fafc;
    }

    .history-item:hover {
      background-color: #f1f5f9;
    }

    .toggle-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out;
    }

    .toggle-content.show {
      max-height: 500px;
      transition: max-height 0.5s ease-in;
    }
  </style>
</head>

<body class="bg-blue-950 min-h-screen flex flex-col items-center px-4 py-8">

  <div class="bg-white shadow-2xl rounded-lg p-6 md:p-8 w-full max-w-md md:max-w-2xl">
    <h2 class="text-2xl md:text-3xl font-bold text-blue-900 mb-6 text-center">Generate Traffic Fine</h2>

    <form class="space-y-5" action="" method="POST" enctype="multipart/form-data">

      <!-- Vehicle Number -->
      <div>
        <label for="vehicle_number" class="block text-blue-900 font-medium mb-1">Vehicle Number</label>
        <input type="text" id="vehicle_number" name="vehicle_number" placeholder="Enter vehicle number"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          onblur="fetchOwnerDetails()">
      </div>

      <!-- Owner Name -->
      <div>
        <label for="owner_name" class="block text-blue-900 font-medium mb-1">Owner Name</label>
        <input type="text" id="owner_name" name="owner_name" placeholder="Owner name will appear here"
          class="w-full border border-gray-300 rounded-md px-4 py-2 bg-gray-100" readonly>
      </div>

      <!-- Violation Type -->
      <div>
        <label for="violation_type" class="block text-blue-900 font-medium mb-1">Violation Type</label>
        <select id="violation_type" name="violation_type"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          onchange="fetchFineAmount()">
          <option value="">Select Violation</option>
          <?php
          include '../includes/connect.php';
          $query = "SELECT * FROM rules";
          $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['rule_id'] . '">' . $row['violation_type'] . ' (Rs. ' . $row['fine_amount'] . ')</option>';
          }
          ?>
        </select>
      </div>

      <!-- Fine Amount -->
      <div>
        <label for="fine_amount" class="block text-blue-900 font-medium mb-1">Fine Amount (₹)</label>
        <input type="number" id="fine_amount" name="fine_amount" placeholder="Fine amount will auto-fill"
          class="w-full border border-gray-300 rounded-md px-4 py-2 bg-gray-100" readonly>
      </div>

      <!-- Location -->
      <div>
        <label for="location" class="block text-blue-900 font-medium mb-1">Violation Location</label>
        <input type="text" id="location" name="location" placeholder="Enter location"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Photo Upload -->
      <div>
        <label for="photo" class="block text-blue-900 font-medium mb-1">Upload Evidence Photo (optional)</label>
        <div class="flex items-center space-x-2">
          <input type="file" accept="image/*" id="photo" name="photo" capture="environment"
            class="w-full border border-gray-300 rounded-md px-4 py-2 bg-white">
          <button type="button" onclick="document.getElementById('photo').click()"
            class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
            <i class="fas fa-camera"></i>
          </button>
        </div>
      </div>

      <!-- Violation History Toggle -->
      <div class="pt-4">
        <button type="button" onclick="toggleHistory()"
          class="flex items-center justify-between w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-md">
          <span>View Violation History</span>
          <i id="history-icon" class="fas fa-chevron-down transition-transform"></i>
        </button>

        <div id="history-content" class="toggle-content mt-2 border rounded-md">
          <div class="p-4">
            <div id="history-loader" class="text-center py-4">
              <i class="fas fa-spinner fa-spin text-blue-500"></i> Loading history...
            </div>
            <div id="history-results" class="hidden">
              <h4 class="font-semibold text-blue-900 mb-2">Past Violations</h4>
              <div id="history-items" class="space-y-2"></div>
              <p id="no-history" class="hidden text-gray-500 text-sm italic">No previous violations found for this vehicle</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit"
        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-md transition text-lg mt-6">
        Generate Fine
      </button>

    </form>
  </div>

  <!-- Log Out Button -->
  <a href="../Backend/fine_logout.php"
    class="w-[20vw] bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-md transition text-[1rem] mt-6 text-center block">
    Log Out
  </a>

  <script>
    function fetchOwnerDetails() {
      const vehicleInput = document.getElementById('vehicle_number').value.trim();
      const ownerNameInput = document.getElementById('owner_name');

      if (vehicleInput === "") {
        ownerNameInput.value = "";
        return;
      }

      var xhr = new XMLHttpRequest();
      xhr.open('GET', `/Fine-T/assets/components/fetch_owner.php?vehicle_no=${encodeURIComponent(vehicleInput)}`, true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          const data = JSON.parse(xhr.responseText);
          ownerNameInput.value = data.success ? data.name : "Not Found";

          // If vehicle found, fetch violation history
          if (data.success) {
            fetchViolationHistory(vehicleInput);
          } else {
            document.getElementById('history-results').classList.add('hidden');
            document.getElementById('no-history').classList.remove('hidden');
            document.getElementById('history-loader').classList.add('hidden');
          }
        } else {
          ownerNameInput.value = "Error fetching data";
        }
      };
      xhr.onerror = function() {
        ownerNameInput.value = "Error fetching data";
      };
      xhr.send();
    }

    function fetchFineAmount() {
      var ruleId = document.getElementById('violation_type').value;
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../assets/components/fetch_fine.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (this.status == 200) {
          document.getElementById('fine_amount').value = this.responseText;
        }
      }
      xhr.send('rule_id=' + ruleId);
    }

    function fetchViolationHistory(vehicleNumber) {
      document.getElementById('history-loader').classList.remove('hidden');
      document.getElementById('history-results').classList.add('hidden');

      var xhr = new XMLHttpRequest();
      xhr.open('GET', `../assets/components/fetch_violation_history.php?vehicle_no=${encodeURIComponent(vehicleNumber)}`, true);
      xhr.onload = function() {
        document.getElementById('history-loader').classList.add('hidden');
        document.getElementById('history-results').classList.remove('hidden');

        if (this.status == 200) {
          try {
            const response = JSON.parse(this.responseText);
            const historyContainer = document.getElementById('history-items');
            historyContainer.innerHTML = '';

            if (response.length > 0) {
              document.getElementById('no-history').classList.add('hidden');

              response.forEach(violation => {
                const statusClass = violation.status.toLowerCase() === 'paid' ? 'bg-green-100 text-green-800' :
                  violation.status.toLowerCase() === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800';

                // Format date for display
                const violationDate = new Date(violation.violation_date);
                const formattedDate = violationDate.toLocaleDateString('en-IN');

                const item = document.createElement('div');
                item.className = 'history-item p-3 rounded-md border';
                item.innerHTML = `
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-medium">${violation.violation_type}</span>
                                    <p class="text-sm text-gray-600">${formattedDate}</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold">Rs. ${violation.fine_amount}</span>
                                    <span class="text-xs px-2 py-1 rounded-full ${statusClass} ml-2">${violation.status}</span>
                                </div>
                            </div>
                            ${violation.location ? `<p class="text-xs mt-1 text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i>${violation.location}</p>` : ''}
                        `;
                historyContainer.appendChild(item);
              });
            } else {
              document.getElementById('no-history').classList.remove('hidden');
            }
          } catch (e) {
            console.error('Error parsing history data:', e);
            document.getElementById('no-history').classList.remove('hidden');
          }
        } else {
          document.getElementById('no-history').classList.remove('hidden');
        }
      };
      xhr.send();
    }

    function toggleHistory() {
      const content = document.getElementById('history-content');
      const icon = document.getElementById('history-icon');

      content.classList.toggle('show');
      icon.classList.toggle('fa-chevron-down');
      icon.classList.toggle('fa-chevron-up');

      const vehicleInput = document.getElementById('vehicle_number').value.trim();
      if (content.classList.contains('show') && vehicleInput && document.getElementById('history-items').innerHTML === '') {
        fetchViolationHistory(vehicleInput);
      }
    }
  </script>

</body>

</html>

<?php if (isset($_GET['success'])): ?>
<script>
// Display success message
alert('Fine successfully recorded for vehicle: <?php echo htmlspecialchars($_GET['vehicle']); ?>');

// Reset form and maintain officer details
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.reset(); // Reset form but keep officer session
    }
    
    // Optional: Focus on vehicle number field for next entry
    const vehicleField = document.querySelector('[name="vehicle_number"]');
    if (vehicleField) {
        vehicleField.focus();
    }
});
</script>
<?php endif; ?>