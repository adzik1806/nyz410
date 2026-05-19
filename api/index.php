<?php 
// 1. Koneksi Database
include 'koneksi/koneksi.php'; 

// 2. Ambil data setting (Gunakan mysqli_fetch_assoc)
$q_setting = mysqli_query($conn, "SELECT * FROM settings WHERE id_setting = 1");
$setting = mysqli_fetch_assoc($q_setting);

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
    
    <title><?php echo $setting['nama_website']; ?> | Community Hub</title>
    
    <link rel="icon" type="image/png" href="assets/<?php echo $setting['logo_website']; ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: white; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .text-gradient { background: linear-gradient(to right, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        @keyframes scrollMarquee {
            0% { transform: translateX(100vw); } 
            100% { transform: translateX(-100%); } 
        }
        
        .animate-scroll-fast {
            display: flex;
            width: max-content;
            animation: scrollMarquee 20s linear infinite;
        }

        .marquee-container {
            width: 100%;
            overflow: hidden;
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }

        .sponsor-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 0 30px;
        }

        .sponsor-box {
            flex: 0 0 auto;
            background-color: white;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
            width: 140px;
            height: 85px;
            padding: 15px;
        }

        @media (min-width: 768px) {
            .sponsor-box { width: 220px; height: 120px; padding: 25px; margin: 0 10px; border-radius: 30px; }
            .sponsor-item { margin: 0 40px; }
        }

        .sponsor-box:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 15px 40px rgba(251, 191, 36, 0.3);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="overflow-x-hidden">

    <nav class="fixed w-full z-50 transition-all duration-300 py-6 px-6" id="navbar">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="assets/<?php echo $setting['logo_website']; ?>" class="h-8 md:h-10 object-contain" alt="Logo">
                <h1 class="text-2xl font-black tracking-tighter text-gradient uppercase"><?php echo $setting['nama_website']; ?></h1>
            </div>
            
            <div class="hidden md:flex space-x-6 font-bold text-[10px] uppercase tracking-widest text-gray-400">
                <a href="javascript:void(0)" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="hover:text-yellow-400 transition">Home</a>
                <a href="javascript:void(0)" onclick="scrollToSection('about')" class="hover:text-yellow-400 transition">About</a>
                <a href="javascript:void(0)" onclick="scrollToSection('events')" class="hover:text-yellow-400 transition">Schedules</a>
                <a href="javascript:void(0)" onclick="scrollToSection('venues')" class="hover:text-yellow-400 transition">Venues</a>
                <a href="javascript:void(0)" onclick="scrollToSection('gallery')" class="hover:text-yellow-400 transition">Gallery</a>
                <a href="javascript:void(0)" onclick="scrollToSection('kas')" class="hover:text-yellow-400 transition text-yellow-500">Kas</a>
            </div>

            <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>" class="bg-yellow-500 text-black px-6 py-2 rounded-full font-bold text-xs hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/20">
                JOIN COMMUNITY
            </a>
        </div>
    </nav>

    <section class="h-screen flex items-center justify-center relative px-6 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#020617]"></div>
            <img src="https://images.unsplash.com/photo-1543351611-58f69d7c1781?q=80&w=2070" class="w-full h-full object-cover opacity-30">
        </div>
        
        <div class="relative z-10 text-center" data-aos="fade-up" data-aos-duration="1000">
            <span class="inline-block px-4 py-1 mb-6 border border-yellow-500/30 rounded-full text-yellow-500 text-[10px] font-bold tracking-[0.3em] uppercase">
                Football, Futsal dan Minisoccer Multisport Hub
            </span>
            <h1 class="text-6xl md:text-9xl font-black mb-6 tracking-tighter leading-none uppercase">
                ONE TEAM.<br><span class="text-gradient">ONE GOAL.</span>
            </h1>
            <p class="max-w-xl mx-auto text-gray-400 text-sm md:text-lg mb-10 italic">
                "<?php echo $setting['deskripsi_website']; ?>"
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="javascript:void(0)" onclick="scrollToSection('events')" class="bg-white text-black px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-yellow-500 transition-all active:scale-95">
                    Cek Jadwal Sekarang
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="py-32 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <p class="text-yellow-500 font-bold text-xs tracking-[0.4em] uppercase mb-4">Discovery</p>
                <h2 class="text-4xl md:text-5xl font-black uppercase mb-8 leading-tight">We Are <span class="text-gradient"><?php echo $setting['nama_website']; ?></span></h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    Berawal dari semangat kebersamaan di lapangan hijau, <?php echo $setting['nama_website']; ?> kini tumbuh menjadi komunitas multisport yang mewadahi pecinta Sepakbola dan Futsal di wilayah Depok dan sekitarnya.
                </p>
                <p class="text-gray-400 leading-relaxed mb-8">
                    Kami percaya bahwa olahraga bukan sekadar kompetisi, melainkan sarana untuk mempererat silaturahmi, menjaga kesehatan, dan membangun koneksi positif antar sesama pecinta olahraga.
                </p>
                <div class="grid grid-cols-2 gap-6 text-center">
                    <div class="glass p-4 rounded-2xl border-white/5">
                        <h4 class="text-2xl font-black text-yellow-500"><?php echo $setting['stats_member']; ?>+</h4>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Active Members</p>
                    </div>
                    <div class="glass p-4 rounded-2xl border-white/5">
                        <h4 class="text-2xl font-black text-yellow-500"><?php echo $setting['stats_venue']; ?>+</h4>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Homebase Venues</p>
                    </div>
                </div>
            </div>
            <div class="relative" data-aos="fade-left">
                <div class="absolute -inset-4 bg-yellow-500/10 blur-3xl rounded-full"></div>
                <img src="assets/6a021cab23106_logo nyenkzer.png" class="relative w-full h-auto max-w-sm mx-auto drop-shadow-[0_0_30px_rgba(251,191,36,0.2)]" alt="About Logo">
            </div>
        </div>
    </section>

    <section class="py-20 bg-white/[0.01] border-y border-white/5 overflow-hidden">
        <div class="text-center mb-12" data-aos="fade-up">
            <p class="text-yellow-500 font-black text-[10px] tracking-[0.5em] uppercase mb-2">Support System</p>
            <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tighter italic">Our <span class="text-gradient">Partners</span></h2>
        </div>
        <div class="marquee-container">
            <div class="animate-scroll-fast">
                <?php 
                $sql_marquee = mysqli_query($conn, "SELECT * FROM sponsors");
                if(mysqli_num_rows($sql_marquee) > 0) {
                    while($s = mysqli_fetch_assoc($sql_marquee)): ?>
                        <div class="sponsor-item">
                            <div class="sponsor-box">
                                <img src="assets/sponsors/<?php echo $s['logo_icon']; ?>" class="max-h-full max-w-full object-contain" alt="Partner">
                            </div>
                            <span class="hidden md:block font-black uppercase tracking-[0.4em] text-[10px] text-yellow-500/60"><?php echo $s['nama_sponsor']; ?></span>
                        </div>
                <?php endwhile; } ?>
            </div>
        </div>
    </section>

    <section id="events" class="py-32 px-6">
        <div class="max-w-7xl mx-auto text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-black mb-4 uppercase tracking-tighter italic">Match <span class="text-gradient">Schedules</span></h2>
            <div class="flex justify-center flex-wrap gap-2 mt-8">
                <button onclick="filterMatch('all')" class="tab-btn active bg-yellow-500 text-black px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest">All</button>
                <button onclick="filterMatch('Sepakbola')" class="tab-btn bg-white/5 text-gray-400 px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest">Football</button>
                <button onclick="filterMatch('Futsal')" class="tab-btn bg-white/5 text-gray-400 px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest">Futsal</button>
                <button onclick="filterMatch('Minisoccer')" class="tab-btn bg-white/5 text-gray-400 px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest">Minisoccer</button>
            </div>
        </div>
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8" id="match-container">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM events WHERE status_event = 'tersedia' ORDER BY tanggal ASC");
            while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="match-card glass p-8 rounded-[40px] border-white/5 shadow-2xl" data-category="<?php echo $row['kategori']; ?>" data-aos="zoom-in">
                <div class="flex justify-between items-start mb-8">
                    <span class="bg-white/10 text-[10px] px-4 py-1.5 rounded-full font-bold uppercase text-yellow-500 border border-yellow-500/20"><?php echo $row['kategori']; ?></span>
                    <div class="text-right">
                        <?php if ($row['kategori'] == 'Sepakbola' || $row['kategori'] == 'Minisoccer'): ?>
                            <p class="text-[9px] text-gray-500 uppercase font-black">Entry Fee</p>
                            <p class="text-lg font-black text-white">Pemain: <?php echo number_format($row['harga']/1000, 0); ?>K</p>
                            <p class="text-[11px] font-bold text-yellow-500">Kiper: <?php echo number_format(($row['harga'] - 10000)/1000, 0); ?>K</p>
                        <?php else: ?>
                            <p class="text-[9px] text-gray-500 uppercase font-black">Entry Fee</p>
                            <p class="text-2xl font-black text-white"><?php echo number_format($row['harga']/1000, 0); ?>K</p>
                        <?php endif; ?>
                    </div>
                </div>
                <h3 class="text-2xl font-black mb-1 uppercase tracking-tight"><?php echo $row['judul_event']; ?></h3>
                <p class="text-gray-500 text-xs mb-8 uppercase font-bold tracking-widest"><i class="fa-solid fa-location-dot text-yellow-500 mr-2"></i> <?php echo $row['lokasi']; ?></p>
                <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>?text=Halo Admin, saya ingin Join match <?php echo $row['kategori']; ?>: <?php echo $row['judul_event']; ?>" class="block w-full bg-yellow-500 text-black py-5 rounded-3xl text-center font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:scale-[1.02] transition-all">Join Now</a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="venues" class="py-24 bg-white/[0.02] px-6">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-black mb-12 text-gradient uppercase tracking-tighter italic">Our Homebase <br>Venues</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <?php 
                $q_venues = mysqli_query($conn, "SELECT * FROM venues ORDER BY id_venue DESC LIMIT 4");
                while($v = mysqli_fetch_assoc($q_venues)): ?>
                <div class="glass p-5 rounded-[35px] group transition-all hover:border-yellow-500/30 flex flex-col h-full">
                    <div class="w-full h-44 overflow-hidden rounded-3xl mb-6 relative border border-white/5 shadow-inner">
                        <?php if(!empty($v['foto_venue']) && file_exists('assets/venues/'.$v['foto_venue'])): ?>
                            <img src="assets/venues/<?php echo $v['foto_venue']; ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110" alt="Venue">
                        <?php else: ?>
                            <div class="w-full h-full bg-white/5 flex items-center justify-center text-yellow-500/10 text-6xl">
                                <i class="fa-solid fa-building-shield"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-black text-sm uppercase tracking-widest mb-2 text-white"><?php echo $v['nama_venue']; ?></h4>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-relaxed">
                            <span class="text-yellow-500"><?php echo $v['kategori_sport']; ?></span> <br> 
                            <?php echo $v['alamat_venue']; ?>
                        </p>
                    </div>
                    <?php if(!empty($v['maps_link'])): ?>
                    <a href="<?php echo $v['maps_link']; ?>" target="_blank" class="mt-6 block w-full text-center py-3 rounded-2xl bg-white/5 border border-white/5 text-[9px] font-black uppercase tracking-[0.2em] hover:bg-yellow-500 hover:text-black transition-all shadow-lg">
                        <i class="fa-solid fa-map-location-dot mr-2"></i> Lihat Lokasi
                    </a>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="kas" class="py-24 px-6 bg-yellow-500 text-black">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div data-aos="fade-right">
                <h2 class="text-4xl font-black uppercase mb-4 tracking-tighter italic leading-none">Transparansi <br>Kas Online</h2>
                <p class="text-black/70 font-bold mb-6 text-sm">Member dapat mengecek saldo iuran komunitas secara realtime.</p>
                <div class="bg-black/10 p-5 rounded-2xl border border-black/10 flex justify-between items-center">
                    <span class="font-black uppercase tracking-widest text-[10px]">Total Saldo Kas</span>
                    <span class="text-2xl font-black italic">Rp <?php echo number_format($saldo_akhir, 0, ',', '.'); ?></span>
                </div>
            </div>
            <div data-aos="fade-left" class="bg-white p-6 rounded-[30px] shadow-xl">
                <h4 class="font-black uppercase mb-4 text-center text-xs tracking-[0.3em]">History Terakhir</h4>
                <div class="space-y-3 max-h-48 overflow-y-auto pr-2">
                    <?php 
                    $kas_h = mysqli_query($conn, "SELECT * FROM kas ORDER BY id_kas DESC LIMIT 5");
                    while($rk = mysqli_fetch_assoc($kas_h)): ?>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <div>
                            <p class="font-bold text-[11px] uppercase"><?php echo $rk['keterangan']; ?></p>
                            <p class="text-[9px] text-gray-400 font-bold"><?php echo date('d/m/y', strtotime($rk['tanggal'])); ?></p>
                        </div>
                        <p class="<?php echo $rk['tipe'] == 'masuk' ? 'text-green-600' : 'text-red-500'; ?> font-black text-xs">
                            <?php echo $rk['tipe'] == 'masuk' ? '+' : '-'; ?> <?php echo number_format($rk['jumlah'], 0, ',', '.'); ?>
                        </p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="gallery" class="py-32 px-6">
        <div class="max-w-7xl mx-auto text-center mb-16" data-aos="fade-up">
            <p class="text-yellow-500 font-bold text-xs tracking-[0.4em] uppercase mb-4">Moments</p>
            <h2 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter">Activity <span class="text-gradient">Gallery</span></h2>
        </div>
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php 
            $gal = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC LIMIT 8");
            while($rg = mysqli_fetch_assoc($gal)): ?>
            <div class="group relative overflow-hidden rounded-3xl h-64 shadow-xl border border-white/5" data-aos="zoom-in">
                <img src="assets/gallery/<?php echo $rg['foto']; ?>" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black opacity-0 group-hover:opacity-90 transition-all flex items-end p-6">
                    <p class="font-bold text-[10px] uppercase tracking-widest text-yellow-500"><?php echo $rg['caption']; ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer class="pt-24 pb-12 px-6 border-t border-white/10 bg-[#010409] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-500/5 blur-[120px] rounded-full -mr-48 -mt-48"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <img src="assets/<?php echo $setting['logo_website']; ?>" class="h-10 object-contain" alt="Logo">
                        <h2 class="text-2xl font-black text-gradient tracking-tighter uppercase"><?php echo $setting['nama_website']; ?></h2>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed italic">"<?php echo $setting['deskripsi_website']; ?>"</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-xl glass flex items-center justify-center hover:bg-yellow-500 hover:text-black transition-all"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-xl glass flex items-center justify-center hover:bg-yellow-500 hover:text-black transition-all"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="w-10 h-10 rounded-xl glass flex items-center justify-center hover:bg-yellow-500 hover:text-black transition-all"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-black uppercase tracking-[0.2em] text-xs mb-8">Navigation</h4>
                    <ul class="space-y-4 text-sm text-gray-500 font-bold uppercase tracking-widest">
                        <li><a href="javascript:void(0)" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="hover:text-yellow-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Home</a></li>
                        <li><a href="javascript:void(0)" onclick="scrollToSection('about')" class="hover:text-yellow-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> About Community</a></li>
                        <li><a href="javascript:void(0)" onclick="scrollToSection('events')" class="hover:text-yellow-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Match Schedules</a></li>
                        <li><a href="javascript:void(0)" onclick="scrollToSection('kas')" class="hover:text-yellow-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Kas Transparency</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-black uppercase tracking-[0.2em] text-xs mb-8">Get In Touch</h4>
                    <ul class="space-y-6 text-sm text-gray-400">
                        <li class="flex items-start gap-4">
                            <i class="fas fa-phone text-yellow-500 mt-1"></i>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-600 mb-1">WhatsApp Admin</p>
                                <p class="font-bold text-white">+<?php echo $setting['wa_admin']; ?></p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <i class="fas fa-envelope text-yellow-500 mt-1"></i>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-600 mb-1">Email Inquiry</p>
                                <p class="font-bold text-white uppercase">hi@<?php echo strtolower(str_replace(' ', '', $setting['nama_website'])); ?>.com</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-black uppercase tracking-[0.2em] text-xs mb-8">Our Base</h4>
                    <div class="rounded-2xl overflow-hidden glass p-1 border-white/10">
                        <div class="h-32 w-full bg-slate-800 rounded-xl flex items-center justify-center text-center p-4">
                            <div class="text-gray-500">
                                <i class="fas fa-map-marked-alt text-2xl mb-2"></i>
                                <p class="text-[10px] font-black uppercase">Depok, West Java<br>Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8 text-[10px] text-gray-600 uppercase tracking-[0.4em]">
                <p>© 2026 <?php echo $setting['nama_website']; ?> • ALL RIGHTS RESERVED</p>
                <div class="flex items-center gap-6">
                    <a href="admin/login.php" class="bg-white/5 px-4 py-2 rounded-full hover:text-yellow-500 transition-all border border-white/5"><i class="fas fa-user-shield mr-2"></i>Admin</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // 1. Fungsi Scroll Tanpa Merubah URL
        function scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                const offset = 80; 
                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = element.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }

        // 2. Bersihkan URL fragment saat load
        if (window.location.hash) {
            window.history.replaceState('', document.title, window.location.pathname + window.location.search);
        }

        // Paksa scroll ke atas saat refresh
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo(0, 0);

        AOS.init({ once: true, duration: 800 });

        window.onscroll = function() {
            var nav = document.getElementById('navbar');
            if (window.pageYOffset > 50) { 
                nav.classList.add('glass', 'py-4', 'shadow-2xl'); 
                nav.classList.remove('py-6'); 
            } else { 
                nav.classList.remove('glass', 'py-4', 'shadow-2xl'); 
                nav.classList.add('py-6'); 
            }
        };

        function filterMatch(category) {
            const cards = document.querySelectorAll('.match-card');
            const btns = document.querySelectorAll('.tab-btn');
            btns.forEach(btn => { 
                btn.classList.remove('bg-yellow-500', 'text-black'); 
                btn.classList.add('bg-white/5', 'text-gray-400'); 
            });
            if(event) event.currentTarget.classList.add('bg-yellow-500', 'text-black');
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) { 
                    card.style.display = 'block'; 
                } else { 
                    card.style.display = 'none'; 
                }
            });
        }
    </script>
</body>
</html>