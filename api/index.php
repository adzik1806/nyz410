<?php 
// 1. Koneksi Database
include 'koneksi/koneksi.php'; 

// 2. Ambil data setting (Ditambahkan pencegahan error jika data kosong)
$q_setting = mysqli_query($conn, "SELECT * FROM settings WHERE id_setting = 1");
$setting = mysqli_fetch_assoc($q_setting);
if (!$setting) {
    $setting = ['nama_website' => 'My Website', 'logo_website' => '', 'deskripsi_website' => '', 'wa_admin' => '', 'stats_member' => 0, 'stats_venue' => 0];
}

// 3. Hitung Saldo Kas Masuk
$q_m = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='masuk'");
$m = mysqli_fetch_assoc($q_m);

// 4. Hitung Saldo Kas Keluar
$q_k = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='keluar'");
$k = mysqli_fetch_assoc($q_k);

// 5. Hitung Saldo Akhir
$saldo_akhir = ($m['total'] ?? 0) - ($k['total'] ?? 0);
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $setting['nama_website'] ?? 'Dashboard'; ?> | Community Hub</title>
    
    <link rel="icon" type="image/png" href="<?php echo $setting['logo_website']; ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .text-gradient { background: linear-gradient(to right, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        @keyframes scrollMarquee { 0% { transform: translateX(100vw); } 100% { transform: translateX(-100%); } }
        .animate-scroll-fast { display: flex; width: max-content; animation: scrollMarquee 20s linear infinite; }
        .marquee-container { width: 100%; overflow: hidden; mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); }
        .sponsor-box { flex: 0 0 auto; background-color: white; border-radius: 18px; display: flex; align-items: center; justify-content: center; width: 140px; height: 85px; padding: 15px; }
        
        @media (min-width: 768px) { .sponsor-box { width: 220px; height: 120px; padding: 25px; } }
        ::-webkit-scrollbar { width: 8px; } ::-webkit-scrollbar-track { background: #020617; } ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="overflow-x-hidden">

    <nav class="fixed w-full z-50 transition-all duration-300 py-6 px-6" id="navbar">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="<?php echo $setting['logo_website']; ?>" class="h-8 md:h-10 object-contain" alt="Logo">
                <h1 class="text-2xl font-black tracking-tighter text-gradient uppercase"><?php echo $setting['nama_website']; ?></h1>
            </div>
            
            <div class="hidden md:flex space-x-6 font-bold text-[10px] uppercase tracking-widest text-gray-400">
                <a href="#" class="hover:text-yellow-400 transition">Home</a>
                <a href="javascript:void(0)" onclick="scrollToSection('about')" class="hover:text-yellow-400 transition">About</a>
                <a href="javascript:void(0)" onclick="scrollToSection('events')" class="hover:text-yellow-400 transition">Schedules</a>
                <a href="javascript:void(0)" onclick="scrollToSection('venues')" class="hover:text-yellow-400 transition">Venues</a>
                <a href="javascript:void(0)" onclick="scrollToSection('gallery')" class="hover:text-yellow-400 transition">Gallery</a>
                <a href="javascript:void(0)" onclick="scrollToSection('kas')" class="hover:text-yellow-400 transition text-yellow-500">Kas</a>
            </div>

            <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>" class="bg-yellow-500 text-black px-6 py-2 rounded-full font-bold text-xs hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/20">JOIN COMMUNITY</a>
        </div>
    </nav>

    <section class="h-screen flex items-center justify-center relative px-6 overflow-hidden">
        <div class="relative z-10 text-center">
            <h1 class="text-6xl md:text-9xl font-black mb-6 tracking-tighter leading-none uppercase">ONE TEAM.<br><span class="text-gradient">ONE GOAL.</span></h1>
            <p class="max-w-xl mx-auto text-gray-400 text-sm md:text-lg mb-10 italic">"<?php echo $setting['deskripsi_website']; ?>"</p>
        </div>
    </section>

    <section class="py-20 bg-white/[0.01]">
        <div class="marquee-container">
            <div class="animate-scroll-fast">
                <?php 
                $sql_marquee = mysqli_query($conn, "SELECT * FROM sponsors");
                while($s = mysqli_fetch_assoc($sql_marquee)): ?>
                    <div class="sponsor-item px-10">
                        <div class="sponsor-box">
                            <img src="<?php echo $s['logo_icon']; ?>" class="max-h-full max-w-full object-contain" alt="Partner">
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="events" class="py-32 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM events WHERE status_event = 'tersedia'");
            while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="match-card glass p-8 rounded-[40px]">
                <h3 class="text-2xl font-black mb-1 uppercase"><?php echo $row['judul_event']; ?></h3>
                <p class="text-gray-500 text-xs"><?php echo $row['kategori']; ?> | <?php echo $row['lokasi']; ?></p>
                <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>" class="block w-full bg-yellow-500 text-black mt-4 py-4 rounded-xl text-center font-bold">JOIN</a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="venues" class="py-24 bg-white/[0.02] px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php 
            $q_venues = mysqli_query($conn, "SELECT * FROM venues");
            while($v = mysqli_fetch_assoc($q_venues)): ?>
            <div class="glass p-5 rounded-[35px]">
                <img src="<?php echo $v['foto_venue']; ?>" class="w-full h-40 object-cover rounded-2xl mb-4" alt="Venue">
                <h4 class="font-black text-sm uppercase"><?php echo $v['nama_venue']; ?></h4>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="kas" class="py-24 px-6 bg-yellow-500 text-black">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-4xl font-black uppercase">Saldo Kas: Rp <?php echo number_format($saldo_akhir, 0, ',', '.'); ?></h2>
        </div>
    </section>

    <footer class="pt-24 pb-12 px-6 border-t border-white/10 text-center">
        <p>© 2026 <?php echo $setting['nama_website']; ?></p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        function scrollToSection(id) { document.getElementById(id).scrollIntoView({ behavior: 'smooth' }); }
    </script>
</body>
</html>