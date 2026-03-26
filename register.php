<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account | TradeSim Engine</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #030712; }
    .mesh-bg {
      position: absolute; top: 0; left: 0; width: 100%; height: 100%;
      background-image: 
        radial-gradient(at 0% 100%, rgba(37, 99, 235, 0.1) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(124, 58, 237, 0.08) 0px, transparent 50%);
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

  <div class="w-full max-w-lg relative z-10 py-10">
    
    <div class="text-center mb-8">
      <a href="index.html" class="inline-flex items-center gap-2 group">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <span class="text-2xl font-bold tracking-tight text-white uppercase">TradeSim</span>
      </a>
    </div>

    <div class="glass p-8 md:p-10 rounded-[2.5rem] shadow-2xl">
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Create Account</h2>
        <p class="text-slate-400 text-sm">Join the platform to run advanced Monte Carlo simulations.</p>
      </div>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-center gap-3">
          <svg class="w-5 h-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <form action="register_process.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <div class="md:col-span-2">
          <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1" for="name">Full Name</label>
          <input type="text" name="name" id="name" required placeholder="Shaina Jane Tanguan"
            class="input-field w-full px-5 py-3.5 rounded-2xl text-white placeholder:text-slate-600" />
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1" for="email">Email Address</label>
          <input type="email" name="email" id="email" required placeholder="shaina@example.com"
            class="input-field w-full px-5 py-3.5 rounded-2xl text-white placeholder:text-slate-600" />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1" for="password">Password</label>
          <input type="password" name="password" id="password" required placeholder="••••••••"
            class="input-field w-full px-5 py-3.5 rounded-2xl text-white placeholder:text-slate-600" />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1" for="confirm_password">Confirm</label>
          <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••••"
            class="input-field w-full px-5 py-3.5 rounded-2xl text-white placeholder:text-slate-600" />
        </div>

        <button type="submit" 
          class="md:col-span-2 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-bold hover:from-blue-500 hover:to-indigo-500 shadow-xl shadow-blue-600/20 transition-all transform active:scale-[0.98] mt-2">
          Create My Free Account
        </button>
      </form>

      <div class="mt-8 pt-8 border-t border-white/5 text-center">
        <p class="text-slate-400 text-sm">
          Already a member? 
          <a href="login.php" class="text-white font-bold hover:text-blue-400 transition ml-1">Login here</a>
        </p>
      </div>
    </div>

    <div class="mt-10 grid grid-cols-3 gap-4 px-4">
        <div class="text-center">
            <p class="text-white font-bold text-lg leading-tight">10k+</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold">Simulations/s</p>
        </div>
        <div class="text-center border-x border-white/5">
            <p class="text-white font-bold text-lg leading-tight">99.9%</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold">Accuracy</p>
        </div>
        <div class="text-center">
            <p class="text-white font-bold text-lg leading-tight">AES-256</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold">Encryption</p>
        </div>
    </div>
  </div>

</body>
</html>