<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | TradeSim Intelligence</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #030712; }
    .mesh-bg {
      position: absolute; top: 0; left: 0; width: 100%; height: 100%;
      background-image: 
        radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.1) 0px, transparent 50%),
        radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.08) 0px, transparent 50%);
      z-index: -1;
    }
    .glass { 
      background: rgba(15, 23, 42, 0.8); 
      backdrop-filter: blur(24px); 
      border: 1px solid rgba(255, 255, 255, 0.08); 
    }
    .input-field {
      background: rgba(3, 7, 18, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }
    .input-field:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
      outline: none;
    }
  </style>
</head>
<body class="text-slate-200 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

  <div class="mesh-bg"></div>

  <div class="w-full max-w-md relative z-10">
    
    <div class="text-center mb-8">
      <a href="index.html" class="inline-flex items-center gap-2 group">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <span class="text-2xl font-bold tracking-tight text-white uppercase">TradeSim</span>
      </a>
    </div>

    <div class="glass p-8 md:p-10 rounded-[2rem] shadow-2xl">
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Welcome back</h2>
        <p class="text-slate-400 text-sm">Enter your credentials to access the simulator.</p>
      </div>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center gap-3 text-red-400 text-sm">
          <svg class="w-5 h-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <?php if(isset($_SESSION['success'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center gap-3 text-green-400 text-sm">
          <svg class="w-5 h-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <form action="login_process.php" method="POST" class="space-y-6">
        <div>
          <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1" for="email">Email Address</label>
          <input type="email" name="email" id="email" required placeholder="name@company.com"
            class="input-field w-full px-5 py-4 rounded-2xl text-white placeholder:text-slate-600" />
        </div>

        <div>
          <div class="flex justify-between mb-2 ml-1">
            <label class="text-xs font-bold uppercase tracking-widest text-slate-500" for="password">Password</label>
            <a href="#" class="text-xs font-semibold text-blue-500 hover:text-blue-400 transition">Forgot?</a>
          </div>
          <input type="password" name="password" id="password" required placeholder="••••••••"
            class="input-field w-full px-5 py-4 rounded-2xl text-white placeholder:text-slate-600" />
        </div>

        <button type="submit" 
          class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-500 shadow-xl shadow-blue-600/20 transition-all transform active:scale-[0.98] mt-4">
          Sign In to Dashboard
        </button>
      </form>

      <div class="mt-8 pt-8 border-t border-white/5 text-center">
        <p class="text-slate-400 text-sm">
          New to the platform? 
          <a href="register.php" class="text-white font-bold hover:text-blue-400 transition ml-1">Create an account</a>
        </p>
      </div>
    </div>

    <p class="mt-8 text-center text-slate-600 text-xs uppercase tracking-widest font-medium">
      &copy; 2026 Secured by TradeSim Auth
    </p>
  </div>

</body>
</html>