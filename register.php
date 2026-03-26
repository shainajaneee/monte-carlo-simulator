<?php
session_start();
if(isset($_SESSION['error'])){
    echo '<p class="text-red-500 mb-4 text-center">'.$_SESSION['error'].'</p>';
    unset($_SESSION['error']);
}
if(isset($_SESSION['success'])){
    echo '<p class="text-green-500 mb-4 text-center">'.$_SESSION['success'].'</p>';
    unset($_SESSION['success']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TradeSim Sign Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-white flex items-center justify-center min-h-screen">

  <div class="bg-[#1e293b] p-10 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-3xl font-bold mb-6 text-center text-[#2563eb]">Create Your Account</h2>

    <form action="register_process.php" method="POST" class="space-y-4">
      <div>
        <label class="block text-gray-300 mb-1" for="name">Full Name</label>
        <input type="text" name="name" id="name" required 
          class="w-full px-4 py-2 rounded-lg bg-[#0f172a] text-white border border-gray-600 focus:border-[#2563eb] outline-none" />
      </div>

      <div>
        <label class="block text-gray-300 mb-1" for="email">Email</label>
        <input type="email" name="email" id="email" required 
          class="w-full px-4 py-2 rounded-lg bg-[#0f172a] text-white border border-gray-600 focus:border-[#2563eb] outline-none" />
      </div>

      <div>
        <label class="block text-gray-300 mb-1" for="password">Password</label>
        <input type="password" name="password" id="password" required 
          class="w-full px-4 py-2 rounded-lg bg-[#0f172a] text-white border border-gray-600 focus:border-[#2563eb] outline-none" />
      </div>

      <div>
        <label class="block text-gray-300 mb-1" for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required 
          class="w-full px-4 py-2 rounded-lg bg-[#0f172a] text-white border border-gray-600 focus:border-[#2563eb] outline-none" />
      </div>

      <button type="submit" 
        class="w-full py-3 bg-[#2563eb] rounded-lg font-bold hover:bg-[#1d4ed8] transition">
        Sign Up
      </button>
    </form>

    <p class="text-gray-400 text-center mt-4">
      Already have an account? 
      <a href="login.php" class="text-[#2563eb] hover:underline">Login</a>
    </p>
  </div>

</body>
</html>
