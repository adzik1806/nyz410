<?php
session_start();
include '../koneksi/koneksi.php';

// --- AMBIL DATA SETTING UNTUK FAVICON ---
$setting = mysqli_fetch_assoc(mysqli_query($conn, "SELECT logo_website FROM settings WHERE id_setting = 1"));

if(isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $pass_md5 = md5($pass);

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$user' AND password = '$pass_md5'");
    $data  = mysqli_fetch_assoc($query);

    if($data) {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Kredensial Tidak Valid!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorized Access | Nyenkzer 410</title>
    
    <link rel="icon" type="image/png" href="../assets/<?php echo $setting['logo_website']; ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #020617; overflow: hidden; }

        .blob {
            position: absolute; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(251, 191, 36, 0.1) 0%, rgba(2, 6, 23, 0) 70%);
            border-radius: 50%; z-index: -1; filter: blur(60px);
            animation: float 15s infinite alternate ease-in-out;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(100px, 50px) scale(1.2); }
            100% { transform: translate(-50px, 100px) scale(0.9); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        .text-gradient {
            background: linear-gradient(to right, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .input-focus:focus-within {
            border-color: rgba(251, 191, 36, 0.5);
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.1);
            transform: scale(1.01);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-6">

    <div class="blob" style="top: -5%; left: 10%;"></div>
    <div class="blob" style="bottom: 5%; right: 10%; animation-delay: -7s;"></div>

    <div class="login-card w-full max-w-md rounded-[35px] p-10 shadow-2xl relative">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-yellow-500/10 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-yellow-500/20 rotate-12 hover:rotate-0 transition-transform duration-500 overflow-hidden">
                <img src="../assets/<?php echo $setting['logo_website']; ?>" class="w-12 h-12 object-contain" alt="Logo">
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tighter uppercase">Nyenkzer <span class="text-gradient">Admin</span></h2>
        </div>

        <?php if(isset($error)): ?>
            <div class="shake bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] py-3 px-4 rounded-xl mb-6 text-center font-bold uppercase tracking-widest">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="input-focus transition-all duration-300 border border-white/5 bg-white/[0.02] rounded-2xl p-1">
                <div class="flex items-center px-4">
                    <i class="fa-solid fa-user-gear text-gray-600"></i>
                    <input type="text" name="username" placeholder="Username" required
                        class="w-full bg-transparent py-4 px-4 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div class="input-focus transition-all duration-300 border border-white/5 bg-white/[0.02] rounded-2xl p-1">
                <div class="flex items-center px-4 relative">
                    <i class="fa-solid fa-key text-gray-600"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required
                        class="w-full bg-transparent py-4 px-4 text-sm text-white focus:outline-none">
                    <button type="button" onclick="togglePassword()" class="text-gray-600 hover:text-yellow-500 transition-colors px-2">
                        <i id="eye-icon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" 
                class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 rounded-2xl shadow-lg shadow-yellow-500/20 transition-all active:scale-95 uppercase text-xs tracking-[0.2em]">
                Unlock Dashboard
            </button>
        </form>

        <p class="text-center mt-10">
            <a href="../index.php" class="text-gray-600 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-colors">
                <i class="fa-solid fa-chevron-left mr-2"></i> Exit to Website
            </a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>