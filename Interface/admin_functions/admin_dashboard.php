<?php
include '../../includes/db.php';
if (!isset($_SESSION['username'])) {
  header("Location: ../../Interface/admin_login.php");
  exit();
}

$totalChallans = 0;
$pendingPayments = 0;
$resolvedCases = 0;
$recentChallans = [];

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM violation");
$row = mysqli_fetch_assoc($result);
$totalChallans = $row['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) AS pending FROM violation WHERE status = 'Pending'");
$row = mysqli_fetch_assoc($result);
$pendingPayments = $row['pending'];

$result = mysqli_query($conn, "SELECT COUNT(*) AS resolved FROM violation WHERE status = 'Paid'");
$row = mysqli_fetch_assoc($result);
$resolvedCases = $row['resolved'];

$result = mysqli_query($conn, "
  SELECT v.vehicle_number, v.violation_date, v.fine_amount, v.status
  FROM violation v
  ORDER BY v.violation_date DESC  
");
while ($row = mysqli_fetch_assoc($result)) {
  $recentChallans[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../src/output.css">
  <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
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
  <?php include '../../includes/sidebar.php'; ?>

  <!-- Dashboard Area -->
  <div class="flex-1 p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto">

      <!-- Dashboard Title -->
      <h1 class="text-3xl font-semibold text-white mb-8">Dashboard Overview</h1>

      <!-- Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-gradient-to-br from-blue-100 to-blue-200 text-blue-900 p-6 rounded-2xl shadow-lg hover:scale-105 transform transition">
          <h2 class="text-lg font-semibold">Total Challans</h2>
          <p id="totalChallans" class="count-up mt-3">0</p>
        </div>
        <div class="bg-gradient-to-br from-pink-100 to-pink-200 text-pink-900 p-6 rounded-2xl shadow-lg hover:scale-105 transform transition">
          <h2 class="text-lg font-semibold">Pending Payments</h2>
          <p id="pendingPayments" class="count-up mt-3">0</p>
        </div>
        <div class="bg-gradient-to-br from-green-100 to-green-200 text-green-900 p-6 rounded-2xl shadow-lg hover:scale-105 transform transition">
          <h2 class="text-lg font-semibold">Resolved Cases</h2>
          <p id="resolvedCases" class="count-up mt-3">0</p>
        </div>
      </div>

      <!-- Recent Challans Table -->
      <div class="bg-white text-blue-900 rounded-2xl shadow-md p-6 mb-16">
        <h2 class="text-2xl font-semibold mb-6">Recent Challans</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="bg-gray-100">
                <th class="py-3 px-6 text-left">Vehicle No.</th>
                <th class="py-3 px-6 text-left">Date</th>
                <th class="py-3 px-6 text-left">Amount</th>
                <th class="py-3 px-6 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentChallans)): ?>
                <?php foreach ($recentChallans as $challan): ?>
                  <tr class="border-t">
                    <td class="py-3 px-6"><?php echo htmlspecialchars($challan['vehicle_number']); ?></td>
                    <td class="py-3 px-6"><?php echo date('Y-m-d', strtotime($challan['violation_date'])); ?></td>
                    <td class="py-3 px-6">₹<?php echo number_format($challan['fine_amount'], 2); ?></td>
                    <td class="py-3 px-6 <?php echo ($challan['status'] == 'Paid') ? 'text-green-600' : 'text-red-600'; ?>">
                      <?php echo $challan['status']; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="py-6 text-center text-gray-500">No recent challans found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Important Traffic Rules Slider -->
      <div class="bg-gradient-to-r from-teal-900 to-blue-900 rounded-2xl shadow-xl">
        <div class="p-6">
          <h2 class="text-2xl font-semibold text-white mb-6">Important Traffic Rules</h2>
          
          <div class="relative h-40">
            <!-- Slides Container -->
            <div id="rulesSlider" class="h-full relative">
              <!-- Slide 1 -->
              <div class="rule-slide absolute inset-0 flex flex-col justify-center items-center text-center text-white p-4 opacity-0 transition-opacity duration-500">
                <span class="text-blue-200 font-bold text-lg mb-2">Section 129</span>
                <p class="text-white text-lg max-w-3xl">Always wear a helmet while riding a two-wheeler. Helmets reduce the risk of head injuries in case of accidents.</p>
              </div>
              
              <!-- Slide 2 -->
              <div class="rule-slide absolute inset-0 flex flex-col justify-center items-center text-center text-white p-4 opacity-0 transition-opacity duration-500">
                <span class="text-blue-200 font-bold text-lg mb-2">Section 185</span>
                <p class="text-white text-lg max-w-3xl">Never drive under the influence of alcohol or drugs. Penalties include fines and imprisonment.</p>
              </div>
              
              <!-- Slide 3 -->
              <div class="rule-slide absolute inset-0 flex flex-col justify-center items-center text-center text-white p-4 opacity-0 transition-opacity duration-500">
                <span class="text-blue-200 font-bold text-lg mb-2">Section 128</span>
                <p class="text-white text-lg max-w-3xl">Always carry your driving license and registration documents while driving.</p>
              </div>
              
              <!-- Slide 4 -->
              <div class="rule-slide absolute inset-0 flex flex-col justify-center items-center text-center text-white p-4 opacity-0 transition-opacity duration-500">
                <span class="text-blue-200 font-bold text-lg mb-2">Section 121</span>
                <p class="text-white text-lg max-w-3xl">Follow all road signs and traffic signals. Ignoring signals can result in fines or accidents.</p>
              </div>
            </div>
            
            <!-- Navigation Dots -->
            <div class="flex justify-center mt-4 space-x-2">
              <button class="slider-dot w-3 h-3 rounded-full bg-blue-300 focus:outline-none" data-index="0"></button>
              <button class="slider-dot w-3 h-3 rounded-full bg-blue-300 focus:outline-none" data-index="1"></button>
              <button class="slider-dot w-3 h-3 rounded-full bg-blue-300 focus:outline-none" data-index="2"></button>
              <button class="slider-dot w-3 h-3 rounded-full bg-blue-300 focus:outline-none" data-index="3"></button>
            </div>
          </div>
        </div>
      </div>

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

// Enhanced Slider Functionality
document.addEventListener('DOMContentLoaded', () => {
  // Counter animations
  animateCounter('totalChallans', <?php echo $totalChallans; ?>);
  animateCounter('pendingPayments', <?php echo $pendingPayments; ?>);
  animateCounter('resolvedCases', <?php echo $resolvedCases; ?>);
  
  // Slider functionality
  const slides = document.querySelectorAll('.rule-slide');
  const dots = document.querySelectorAll('.slider-dot');
  let currentSlide = 0;
  const totalSlides = slides.length;
  
  // Show first slide initially
  slides[0].classList.add('opacity-100');
  dots[0].classList.replace('bg-blue-300', 'bg-white');
  dots[0].classList.add('scale-125');
  
  function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => {
      slide.classList.remove('opacity-100');
      slide.classList.add('opacity-0');
    });
    
    // Reset all dots
    dots.forEach(dot => {
      dot.classList.replace('bg-white', 'bg-blue-300');
      dot.classList.remove('scale-125');
    });
    
    // Show selected slide
    slides[index].classList.remove('opacity-0');
    slides[index].classList.add('opacity-100');
    
    // Highlight current dot
    dots[index].classList.replace('bg-blue-300', 'bg-white');
    dots[index].classList.add('scale-125');
    
    currentSlide = index;
  }
  
  // Auto-advance slides
  setInterval(() => {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }, 5000);
  
  // Dot navigation
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      const slideIndex = parseInt(dot.getAttribute('data-index'));
      showSlide(slideIndex);
    });
  });
});
</script>

</body>
</html>