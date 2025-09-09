<?php
if(!isset($_SESSION['username']))
{
    echo ' <nav class="flex items-center justify-between text-white bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900  shadow-inner px-6 py-5 h-[14vh] sticky top-0 z-50">
  <img src="../assets/images/LogoW-removebg-preview.png" class="w-[10vw] hover:scale-105 transition" alt="">

  <div class="flex items-center relative">

    <a href="../Interface/home.php" class="bg-blue-500 text-white px-[1vw] py-1 rounded-[10px] flex items-center space-x-2 hover:scale-105 transition">
      <p style="font-family: '.'Courier New'.', Courier, monospace;">Home</p>
      <img src="../assets/images/homev3.png" class="w-[1.25rem]" alt="">
    </a>

    <a href="../Interface/sign_up.php" style="font-family:'. 'Courier New'.', Courier, monospace;" class="bg-teal-400 text-white mx-[1vw] px-[1vw] py-1 rounded-[10px] hover:scale-105 transition">Sign up</a>

    <button id="loginButton" class="bg-blue-700 text-white px-[1vw] py-1 rounded-[10px] flex items-center space-x-2 hover:scale-105 transition relative">
      <p style="font-family: '.'Courier New'.', Courier, monospace;">Login</p>
      <img src="../assets/images/lock.png" class="w-[1.25rem]" alt="">
    </button>

<div id="loginMenu" class="hidden absolute top-20 right-0 w-64 bg-white bg-opacity-95 backdrop-blur-md shadow-2xl rounded-xl flex flex-col py-4 px-6 space-y-4 border border-gray-200">
  <div class="text-gray-800 text-lg font-semibold mb-2">Login Portal</div>
  
  <a href="../Interface/officer_login.php" class="w-full text-center py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition duration-300">
    Officer Login
  </a>
  
  <a href="../Interface/admin_login.php" class="w-full text-center py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition duration-300">
    Admin Login
  </a>
</div>

  </div>
</nav>
';
}
?>
<script src="../JavaScript/header.js">
</script>
