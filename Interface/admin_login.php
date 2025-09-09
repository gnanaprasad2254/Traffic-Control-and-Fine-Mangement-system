<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Log In</title>
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
  <style>
    body {
      overflow: hidden;
    }
  </style>
</head>
<link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">

<body class="bg-[#001f3f] min-h-screen flex flex-col items-center justify-center p-4">

  <div class="flex w-[90vw] max-w-6xl h-[80vh] rounded-lg overflow-hidden shadow-2xl mt-8"> <!-- Added mt-8 for space above -->
    <div class="w-1/2">
      <img src="../assets/images/control_center_v2.jpg" alt="Admin Control Center" class="object-cover h-full w-full">
    </div>
    <div class="w-1/2 bg-[#001f3f] p-10 flex flex-col justify-center space-y-6">
    
      <!-- Added space above the heading -->
      <div class="mb-8"> <!-- Increased margin-bottom for more space -->
        <h2 style="font-family: 'Courier New', Courier, monospace;" class="text-white text-3xl text-center font-bold">Admin Log In</h2>
      </div>

      <form action="../Backend/login_backend.php" method="POST" class="flex flex-col space-y-4">
        <input type="email" name="iden" placeholder="Email" required
          class="rounded-md p-3 bg-black text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <input type="password" name="password" placeholder="REFERENCE" required
          class="rounded-md p-3 bg-black text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400">

        <button type="submit" value="admin" name="mode" id="mode"
          class="bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-md font-bold hover:scale-105 transition">
          LOG IN
        </button>
      </form>

      <div class="text-center pt-4"> <!-- Added padding-top for space above link -->
        <a href="../Interface/admin_forgot_password.php" class="text-blue-300 text-sm hover:underline">Forgot password?</a>
      </div>
    </div>
  </div>

</body>
</html>