<?php 
include 'koneksi/koneksi.php'; 

// Ambil data setting
$q_setting = mysqli_query($conn, "SELECT * FROM settings WHERE id_setting = 1");
$setting = mysqli_fetch_assoc($q_setting) ?? [
    'nama_website' => 'Community Hub', 
    'deskripsi_website' => 'Multisport Community', 
    'wa_admin' => '6281291992648',
    'logo_website' => '',
    'stats_member' => '0',
    'stats_venue' => '0'
];

// SEO
$site_name   = $setting['nama_website'];
$site_desc   = $setting['deskripsi_website'];
$site_logo   = "assets/" . $setting['logo_website'];
$site_url    = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// Saldo Kas
$q_m = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='masuk'");
$m = mysqli_fetch_assoc($q_m);

$q_k = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='keluar'");
$k = mysqli_fetch_assoc($q_k);

$saldo_akhir = ($m['total'] ?? 0) - ($k['total'] ?? 0);
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title><?php echo $site_name; ?> | Community Hub</title>
    <meta name="description" content="<?php echo $site_desc; ?>">
    <meta name="keywords" content="futsal depok, minisoccer depok, sepakbola depok, komunitas olahraga depok, sparing futsal, football community">
    <meta name="author" content="<?php echo $site_name; ?>">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $site_name; ?>">
    <meta property="og:description" content="<?php echo $site_desc; ?>">
    <meta property="og:image" content="<?php echo $site_logo; ?>">
    <meta property="og:url" content="<?php echo $site_url; ?>">
    <meta property="og:type" content="website">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $site_name; ?>">
    <meta name="twitter:description" content="<?php echo $site_desc; ?>">
    <meta name="twitter:image" content="<?php echo $site_logo; ?>">

    <link rel="canonical" href="<?php echo $site_url; ?>">
    <link rel="icon" type="image/png" href="<?php echo $site_logo; ?>">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            color: white;
        }

        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .text-gradient {
            background: linear-gradient(to right, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes scrollMarquee {
            0% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-container {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .animate-scroll-fast {
            display: flex;
            align-items: center;
            width: max-content;
            animation: scrollMarquee 25s linear infinite;
        }

        .animate-scroll-fast:hover {
            animation-play-state: paused;
        }

        .sponsor-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 30px;
        }

        .sponsor-box {
            width: 180px;
            height: 100px;
            background: white;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: .4s ease;
            overflow: hidden;
        }

        .sponsor-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sponsor-box:hover {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 15px 40px rgba(251,191,36,0.3);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
        }
    </style>
</head>

<body class="overflow-x-hidden">

<!-- NAVBAR -->
<nav class="fixed w-full z-50 transition-all duration-300 py-6 px-6" id="navbar">
    <div class="max-w-7xl mx-auto flex justify-between items-center">

        <div class="flex items-center gap-3">
            <img src="<?php echo $site_logo; ?>" class="h-10 object-contain" alt="<?php echo $site_name; ?>">
            <h1 class="text-2xl font-black tracking-tighter text-gradient uppercase">
                <?php echo $site_name; ?>
            </h1>
        </div>

        <div class="hidden md:flex space-x-6 font-bold text-[10px] uppercase tracking-widest text-gray-400">
            <a href="javascript:void(0)" onclick="window.scrollTo({top:0,behavior:'smooth'})" class="hover:text-yellow-400">Home</a>

            <a href="javascript:void(0)" onclick="scrollToSection('about')" class="hover:text-yellow-400">About</a>

            <a href="javascript:void(0)" onclick="scrollToSection('events')" class="hover:text-yellow-400">Schedules</a>

            <a href="javascript:void(0)" onclick="scrollToSection('venues')" class="hover:text-yellow-400">Venues</a>

            <a href="javascript:void(0)" onclick="scrollToSection('gallery')" class="hover:text-yellow-400">Gallery</a>

            <a href="javascript:void(0)" onclick="scrollToSection('kas')" class="hover:text-yellow-400">Kas</a>
        </div>

        <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>"
           class="bg-yellow-500 text-black px-6 py-2 rounded-full font-bold text-xs hover:bg-yellow-400 transition">
            JOIN COMMUNITY
        </a>

    </div>
</nav>

<!-- HERO -->
<section class="h-screen flex items-center justify-center relative px-6 overflow-hidden">

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#020617]"></div>

        <img src="https://images.unsplash.com/photo-1543351611-58f69d7c1781?q=80&w=2070"
             class="w-full h-full object-cover opacity-30">
    </div>

    <div class="relative z-10 text-center" data-aos="fade-up">

        <span class="inline-block px-4 py-1 mb-6 border border-yellow-500/30 rounded-full text-yellow-500 text-[10px] font-bold tracking-[0.3em] uppercase">
            Football, Futsal dan Minisoccer
        </span>

        <h1 class="text-6xl md:text-9xl font-black mb-6 tracking-tighter leading-none uppercase">
            ONE TEAM.<br>
            <span class="text-gradient">ONE GOAL.</span>
        </h1>

        <p class="max-w-xl mx-auto text-gray-400 text-sm md:text-lg mb-10 italic">
            "<?php echo $site_desc; ?>"
        </p>

        <a href="javascript:void(0)"
           onclick="scrollToSection('events')"
           class="bg-white text-black px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-yellow-500 transition-all">
            Cek Jadwal Sekarang
        </a>

    </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-32 px-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

        <div data-aos="fade-right">

            <p class="text-yellow-500 font-bold text-xs tracking-[0.4em] uppercase mb-4">
                Discovery
            </p>

            <h2 class="text-4xl md:text-5xl font-black uppercase mb-8 leading-tight">
                We Are <span class="text-gradient"><?php echo $site_name; ?></span>
            </h2>

            <p class="text-gray-400 leading-relaxed mb-6">
                Berawal dari semangat kebersamaan di lapangan hijau, 
                <?php echo $site_name; ?> kini tumbuh menjadi komunitas multisport 
                yang mewadahi pecinta sepakbola dan futsal di Depok dan sekitarnya.
            </p>

            <div class="grid grid-cols-2 gap-6 text-center mt-10">

                <div class="glass p-4 rounded-2xl">
                    <h4 class="text-2xl font-black text-yellow-500">
                        <?php echo $setting['stats_member']; ?>+
                    </h4>
                    <p class="text-[10px] uppercase tracking-widest text-gray-500">
                        Active Members
                    </p>
                </div>

                <div class="glass p-4 rounded-2xl">
                    <h4 class="text-2xl font-black text-yellow-500">
                        <?php echo $setting['stats_venue']; ?>+
                    </h4>
                    <p class="text-[10px] uppercase tracking-widest text-gray-500">
                        Homebase Venues
                    </p>
                </div>

            </div>
        </div>

        <div class="relative" data-aos="fade-left">
            <img src="<?php echo $site_logo; ?>"
                 class="relative w-full h-auto max-w-sm mx-auto"
                 alt="<?php echo $site_name; ?>">
        </div>

    </div>
</section>

<!-- SPONSOR -->
<section class="py-20 bg-white/[0.01] border-y border-white/5 overflow-hidden">

    <div class="text-center mb-14" data-aos="fade-up">

        <p class="text-yellow-500 font-black text-[10px] tracking-[0.5em] uppercase mb-3">
            Support System
        </p>

        <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter italic">
            Our <span class="text-gradient">Partners</span>
        </h2>

    </div>

    <div class="marquee-container">

        <div class="animate-scroll-fast">

            <?php
            $sql_marquee = mysqli_query($conn, "SELECT * FROM sponsors");

            if(mysqli_num_rows($sql_marquee) > 0):

                while($s = mysqli_fetch_assoc($sql_marquee)):
            ?>

            <div class="sponsor-item">

                <div class="sponsor-box">

                    <?php
                    $logo = $s['logo_icon'];

                    if(!empty($logo)):
                    ?>

                        <img src="<?php echo $logo; ?>"
                             alt="<?php echo $s['nama_sponsor']; ?>">

                    <?php else: ?>

                        <span class="text-black font-black text-xl">
                            <?php echo $s['nama_sponsor']; ?>
                        </span>

                    <?php endif; ?>

                </div>

                <span class="mt-4 text-[10px] font-black uppercase tracking-[0.4em] text-yellow-500/70">
                    <?php echo $s['nama_sponsor']; ?>
                </span>

            </div>

            <?php 
                endwhile;

                // DUPLIKASI BIAR LOOP HALUS
                mysqli_data_seek($sql_marquee, 0);

                while($s = mysqli_fetch_assoc($sql_marquee)):
            ?>

            <div class="sponsor-item">

                <div class="sponsor-box">

                    <?php if(!empty($s['logo_icon'])): ?>

                        <img src="<?php echo $s['logo_icon']; ?>"
                             alt="<?php echo $s['nama_sponsor']; ?>">

                    <?php endif; ?>

                </div>

                <span class="mt-4 text-[10px] font-black uppercase tracking-[0.4em] text-yellow-500/70">
                    <?php echo $s['nama_sponsor']; ?>
                </span>

            </div>

            <?php 
                endwhile;

            endif;
            ?>

        </div>

    </div>
</section>

<!-- FOOTER -->
<footer class="pt-20 pb-10 text-center border-t border-white/5">

    <img src="<?php echo $site_logo; ?>"
         class="h-14 mx-auto mb-5"
         alt="<?php echo $site_name; ?>">

    <h2 class="text-3xl font-black text-gradient uppercase mb-3">
        <?php echo $site_name; ?>
    </h2>

    <p class="text-gray-500 text-sm mb-6">
        <?php echo $site_desc; ?>
    </p>

    <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>"
       class="inline-block bg-yellow-500 text-black px-8 py-3 rounded-full font-black text-xs uppercase tracking-widest hover:bg-yellow-400 transition">
        Join WhatsApp
    </a>

    <div class="mt-12 text-[10px] tracking-[0.3em] uppercase text-gray-600">
        © <?php echo date('Y'); ?> <?php echo $site_name; ?> — All Rights Reserved
    </div>

</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({
        once: true,
        duration: 800
    });

    function scrollToSection(sectionId) {

        const element = document.getElementById(sectionId);

        if(element){

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

    window.onscroll = function(){

        const nav = document.getElementById('navbar');

        if(window.pageYOffset > 50){

            nav.classList.add('glass','py-4','shadow-2xl');
            nav.classList.remove('py-6');

        } else {

            nav.classList.remove('glass','py-4','shadow-2xl');
            nav.classList.add('py-6');
        }
    }
</script>

</body>
</html>