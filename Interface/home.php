<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>E-FINE AND VIOLATION CONTROL</title>
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
  <style>
    .hero-banner {
      background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../assets/images/Banner1_v4.png');
      background-size: cover;
      background-position: center;
      height: 60vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
    }

    .feature-card {
      transition: all 0.3s ease;
      min-height: 250px;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body class="bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 min-h-screen flex flex-col">
  <?php include '../includes/header.php'; ?>


  <div class="container mx-auto px-4 py-16 flex-1">
    <h2 class="text-3xl font-bold text-center text-white mb-16">Our Services</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto">
      <a href="../Interface/challan.php" class="group">
        <div class="feature-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-8 text-white flex flex-col items-center text-center">
          <div class="bg-white bg-opacity-20 p-4 rounded-full mb-6 group-hover:rotate-12 transition">
            <img src="../assets/images/profile.png" class="w-12 h-12" alt="E-Challan">
          </div>
          <h3 class="text-2xl font-bold mb-4">E-Fine System</h3>
          <p class="text-blue-100">Streamlined digital platform for issuing and paying traffic violation fines.</p>
        </div>
      </a>

      <a href="../Interface/access_options.php?role=officer" class="group">
        <div class="feature-card bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl p-8 text-white flex flex-col items-center text-center">
          <div class="bg-white bg-opacity-20 p-4 rounded-full mb-6 group-hover:rotate-12 transition">
            <img src="../assets/images/officer-icon.png" class="w-12 h-12" alt="Officer Portal">
          </div>
          <h3 class="text-2xl font-bold mb-4">Officer Portal</h3>
          <p class="text-teal-100">Comprehensive tools for traffic officers to document violations and manage cases.</p>
        </div>
      </a>

      <a href="../Interface/access_options.php?role=admin" class="group">
        <div class="feature-card bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl p-8 text-white flex flex-col items-center text-center">
          <div class="bg-white bg-opacity-20 p-4 rounded-full mb-6 group-hover:rotate-12 transition">
            <img src="../assets/images/admin-icon.png" class="w-12 h-12" alt="Administration">
          </div>
          <h3 class="text-2xl font-bold mb-4">Administration</h3>
          <p class="text-purple-100">Centralized control panel for system configuration and user management.</p>
        </div>
      </a>
    </div>
    <div class="max-w-4xl mx-auto mt-24 text-center">
      <h2 class="text-3xl font-bold text-white mb-6">System Features</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-white">

        <div class="p-6">
          <div class="text-blue-400 mb-4">
            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3">Fast Processing</h3>
          <p class="text-gray-300">Reduce paperwork with our efficient digital workflow.</p>
        </div>

        <div class="p-6">
          <div class="text-teal-400 mb-4">
            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3">Secure Platform</h3>
          <p class="text-gray-300">Advanced security measures protect all your data.</p>
        </div>

        <div class="p-6">
          <div class="text-purple-400 mb-4">
            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3">Detailed Reporting</h3>
          <p class="text-gray-300">Valuable insights through comprehensive analytics.</p>
        </div>
      </div>
    </div>
  </div>
  <?php include '../includes/footer.php'; ?>
</body>

</html>