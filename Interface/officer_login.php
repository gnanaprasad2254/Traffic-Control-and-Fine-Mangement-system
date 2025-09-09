<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Police login In</title>
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
</head>

<body class="bg-gradient-to-r from-black via-blue-950 to-black min-h-screen flex items-center justify-center">

  <div class="flex bg-gray-900 rounded-lg shadow-2xl overflow-hidden w-[80vw] max-w-5xl">

    <div class="bg-gray-700 flex items-center justify-center w-1/2 p-10">
      <img src="../assets/images/police_badge.jpg" alt="Police Badge" class="object-contain h-60">
    </div>
    <div class="w-1/2 bg-gradient-to-r from-blue-900 to-blue-700 p-10 flex flex-col justify-center space-y-6">
      <h2 style="font-family: 'Courier New', Courier, monospace;" class="text-white text-3xl text-center font-bold">Login In</h2>

      <form action="../Backend/login_backend.php" method="POST" class="flex flex-col space-y-4">
        <input type="text" name="iden" placeholder="Badge No" required
          class="rounded-md p-3 bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <input type="password" name="password" placeholder="Password" required
          class="rounded-md p-3 bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400">
        
        <button type="submit" value= "officer" name="mode" id = "mode"
          class="bg-gradient-to-r from-blue-400 to-blue-600 text-white py-3 rounded-md font-bold hover:scale-105 transition">
          LOG IN
        </button>
      </form>

      <div class="text-center">
        <a href="../Interface/forgot_password.php" class="text-blue-300 text-sm hover:underline">Forgot password?</a>
      </div>
    </div>

  </div>

</body>

</html>
