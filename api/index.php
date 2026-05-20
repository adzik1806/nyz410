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
    
    <title><?php echo $setting['nama_website']; ?> | Community Hub</title>

    <link rel="icon" type="image/png" href="assets/<?php echo $setting['logo_website']; ?>">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background:#020617;
            color:white;
            overflow-x:hidden;
        }

        .glass{
            background:rgba(255,255,255,0.04);
            backdrop-filter:blur(14px);
            border:1px solid rgba(255,255,255,0.08);
        }

        .text-gradient{
            background:linear-gradient(to right,#fbbf24,#f59e0b);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .hero-bg::before{
            content:'';
            position:absolute;
            inset:0;
            background:linear-gradient(to bottom,rgba(2,6,23,0.1),#020617);
            z-index:1;
        }

        @keyframes scrollMarquee{
            0%{
                transform:translateX(100vw);
            }
            100%{
                transform:translateX(-100%);
            }
        }

        .animate-scroll-fast{
            display:flex;
            width:max-content;
            animation:scrollMarquee 20s linear infinite;
        }

        .marquee-container{
            width:100%;
            overflow:hidden;
            mask-image:linear-gradient(to right,transparent,black 10%,black 90%,transparent);
            -webkit-mask-image:linear-gradient(to right,transparent,black 10%,black 90%,transparent);
        }

        .sponsor-item{
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            gap:1rem;
            margin:0 30px;
        }

        .sponsor-box{
            flex:0 0 auto;
            background:white;
            border-radius:20px;
            display:flex;
            align-items:center;
            justify-content:center;
            width:150px;
            height:90px;
            padding:15px;
            transition:all .4s ease;
        }

        .sponsor-box:hover{
            transform:translateY(-8px) scale(1.04);
            box-shadow:0 15px 40px rgba(251,191,36,.3);
        }

        @media(min-width:768px){
            .sponsor-box{
                width:220px;
                height:120px;
                padding:25px;
                border-radius:28px;
            }

            .sponsor-item{
                margin:0 40px;
            }
        }

        .match-card{
            transition:.4s ease;
        }

        .match-card:hover{
            transform:translateY(-10px);
            border-color:rgba(251,191,36,.4);
        }

        .gallery-item img{
            transition:.7s ease;
        }

        .gallery-item:hover img{
            transform:scale(1.1);
        }

        ::-webkit-scrollbar{
            width:8px;
        }

        ::-webkit-scrollbar-track{
            background:#020617;
        }

        ::-webkit-scrollbar-thumb{
            background:#1e293b;
            border-radius:20px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav id="navbar" class="fixed top-0 left-0 w-full z-50 py-6 px-6 transition-all duration-300">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        <div class="flex items-center gap-3">
            <img src="assets/<?php echo $setting['logo_website']; ?>" class="h-8 md:h-10 object-contain">
            <h1 class="text-2xl font-black uppercase tracking-tight text-gradient">
                <?php echo $setting['nama_website']; ?>
            </h1>
        </div>

        <div class="hidden md:flex items-center gap-6 text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400">

            <a href="javascript:void(0)" onclick="window.scrollTo({top:0,behavior:'smooth'})" class="hover:text-yellow-400 transition">
                Home
            </a>

            <a href="javascript:void(0)" onclick="scrollToSection('about')" class="hover:text-yellow-400 transition">
                About
            </a>

            <a href="javascript:void(0)" onclick="scrollToSection('events')" class="hover:text-yellow-400 transition">
                Schedule
            </a>

            <a href="javascript:void(0)" onclick="scrollToSection('venues')" class="hover:text-yellow-400 transition">
                Venues
            </a>

            <a href="javascript:void(0)" onclick="scrollToSection('gallery')" class="hover:text-yellow-400 transition">
                Gallery
            </a>

            <a href="javascript:void(0)" onclick="scrollToSection('kas')" class="hover:text-yellow-400 transition">
                Kas
            </a>

        </div>

        <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>"
           class="bg-yellow-500 text-black px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-yellow-400 transition">
            Join Community
        </a>

    </div>
</nav>

<!-- HERO -->
<section class="relative h-screen flex items-center justify-center px-6 overflow-hidden">

    <div class="absolute inset-0 hero-bg">
        <img src="https://images.unsplash.com/photo-1543351611-58f69d7c1781?q=80&w=2070"
             class="w-full h-full object-cover opacity-30">
    </div>

    <div class="relative z-10 text-center" data-aos="fade-up">

        <span class="inline-block px-5 py-2 rounded-full border border-yellow-500/30 text-yellow-500 text-[10px] uppercase tracking-[0.3em] font-bold mb-6">
            Football • Futsal • Minisoccer
        </span>

        <h1 class="text-6xl md:text-9xl font-black uppercase leading-none tracking-tighter mb-6">
            ONE TEAM.<br>
            <span class="text-gradient">ONE GOAL.</span>
        </h1>

        <p class="max-w-2xl mx-auto text-gray-400 italic mb-10">
            "<?php echo $setting['deskripsi_website']; ?>"
        </p>

        <a href="javascript:void(0)"
           onclick="scrollToSection('events')"
           class="inline-block bg-white text-black px-10 py-5 rounded-2xl uppercase tracking-widest font-black text-sm hover:bg-yellow-500 transition">
            Cek Jadwal Sekarang
        </a>

    </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-32 px-6">

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

        <div data-aos="fade-right">

            <p class="text-yellow-500 uppercase tracking-[0.4em] text-xs font-bold mb-4">
                Discovery
            </p>

            <h2 class="text-4xl md:text-5xl font-black uppercase leading-tight mb-8">
                We Are <span class="text-gradient"><?php echo $setting['nama_website']; ?></span>
            </h2>

            <p class="text-gray-400 leading-relaxed mb-6">
                Berawal dari semangat kebersamaan di lapangan hijau,
                <?php echo $setting['nama_website']; ?> kini tumbuh menjadi komunitas multisport
                yang mewadahi pecinta sepakbola dan futsal.
            </p>

            <p class="text-gray-400 leading-relaxed mb-8">
                Kami percaya olahraga bukan sekadar kompetisi,
                tetapi juga tempat membangun relasi dan kebersamaan.
            </p>

            <div class="grid grid-cols-2 gap-6">

                <div class="glass rounded-3xl p-5 text-center">
                    <h3 class="text-3xl font-black text-yellow-500">
                        <?php echo $setting['stats_member']; ?>+
                    </h3>

                    <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">
                        Active Members
                    </p>
                </div>

                <div class="glass rounded-3xl p-5 text-center">
                    <h3 class="text-3xl font-black text-yellow-500">
                        <?php echo $setting['stats_venue']; ?>+
                    </h3>

                    <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">
                        Homebase Venues
                    </p>
                </div>

            </div>

        </div>

        <div class="relative" data-aos="fade-left">

            <div class="absolute -inset-5 bg-yellow-500/10 blur-3xl rounded-full"></div>

            <img src="assets/6a021cab23106_logo nyenkzer.png"
                 class="relative w-full max-w-sm mx-auto">

        </div>

    </div>

</section>

<!-- PARTNERS -->
<section class="py-20 border-y border-white/5 bg-white/[0.01] overflow-hidden">

    <div class="text-center mb-14" data-aos="fade-up">
        <p class="text-yellow-500 uppercase tracking-[0.4em] text-xs font-black mb-2">
            Support System
        </p>
        <h2 class="text-4xl font-black uppercase italic tracking-tight">
            Our <span class="text-gradient">Partners</span>
        </h2>
    </div>

    <div class="marquee-container relative w-full overflow-hidden flex justify-center">
        
        <?php 
        $sql_marquee = mysqli_query($conn, "SELECT * FROM sponsors");
        
        $sponsors = [];
        if ($sql_marquee && mysqli_num_rows($sql_marquee) > 0) {
            while($row = mysqli_fetch_assoc($sql_marquee)) {
                $sponsors[] = $row;
            }
        }

        if (!empty($sponsors)):
            $jumlah_sponsor = count($sponsors);
            
            // JIKA SPONSOR SEDIKIT (kurang dari 4): Tampil statis di tengah, tidak usah loop marquee
            if ($jumlah_sponsor < 4): 
        ?>
                <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
                    <?php 
                    foreach ($sponsors as $s): 
                        // PERBAIKAN UTAMA: Karena data di DB berupa Base64, variabel langsung mengambil nilainya
                        $gambar_sponsor = $s['logo_icon']; 
                    ?>
                        <div class="sponsor-item flex flex-col items-center justify-center text-center">
                            <div class="w-[140px] h-[80px] md:w-[200px] md:h-[100px] bg-white rounded-xl md:rounded-[24px] flex items-center justify-center p-4 shadow-xl shadow-black/40 transition-all duration-300 hover:-translate-y-2">
                                <img src="<?php echo $gambar_sponsor; ?>" 
                                     class="max-w-full max-h-full object-contain block mx-auto" 
                                     alt="<?php echo $s['nama_sponsor']; ?>">
                            </div>
                            <span class="text-[10px] uppercase tracking-[0.3em] text-yellow-500/60 font-black mt-3 block">
                                <?php echo $s['nama_sponsor']; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

        <?php 
            // JIKA SPONSOR BANYAK (4 atau lebih): Baru jalankan animasi marquee meluncur
            else: 
                $display_sponsors = array_merge($sponsors, $sponsors); 
        ?>
                <div class="animate-scroll-fast flex items-center gap-8 md:gap-12 w-max">
                    <?php 
                    foreach ($display_sponsors as $s): 
                        $gambar_sponsor = $s['logo_icon']; 
                    ?>
                        <div class="sponsor-item flex-shrink-0 flex flex-col items-center justify-center text-center">
                            <div class="w-[140px] h-[80px] md:w-[200px] md:h-[100px] bg-white rounded-xl md:rounded-[24px] flex items-center justify-center p-4 shadow-xl shadow-black/40">
                                <img src="<?php echo $gambar_sponsor; ?>" 
                                     class="max-w-full max-h-full object-contain block mx-auto" 
                                     alt="<?php echo $s['nama_sponsor']; ?>">
                            </div>
                            <span class="text-[10px] uppercase tracking-[0.3em] text-yellow-500/60 font-black mt-3 block">
                                <?php echo $s['nama_sponsor']; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
        <?php 
            endif; 
        else: 
            // Tampilan cadangan jika database kosong
            for($i = 1; $i <= 3; $i++):
        ?>
            <div class="mx-4 flex flex-col items-center">
                <div class="w-[140px] h-[80px] md:w-[200px] md:h-[100px] bg-white/5 border border-white/10 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-handshake text-yellow-500/20 text-2xl"></i>
                </div>
                <span class="text-[10px] uppercase tracking-[0.3em] text-gray-600 font-black mt-3 block">Belum Ada Data</span>
            </div>
        <?php 
            endfor;
        endif; 
        ?>

    </div>

</section>

<!-- EVENTS -->
<section id="events" class="py-32 px-6">

    <div class="max-w-7xl mx-auto text-center mb-16" data-aos="fade-up">

        <h2 class="text-4xl font-black uppercase italic tracking-tight mb-5">
            Match <span class="text-gradient">Schedules</span>
        </h2>

        <div class="flex justify-center flex-wrap gap-3 mt-8">

            <button onclick="filterMatch('all', event)"
                    class="tab-btn bg-yellow-500 text-black px-6 py-3 rounded-xl text-xs uppercase tracking-widest font-black">
                All
            </button>

            <button onclick="filterMatch('Sepakbola', event)"
                    class="tab-btn bg-white/5 text-gray-400 px-6 py-3 rounded-xl text-xs uppercase tracking-widest font-black">
                Football
            </button>

            <button onclick="filterMatch('Futsal', event)"
                    class="tab-btn bg-white/5 text-gray-400 px-6 py-3 rounded-xl text-xs uppercase tracking-widest font-black">
                Futsal
            </button>

            <button onclick="filterMatch('Minisoccer', event)"
                    class="tab-btn bg-white/5 text-gray-400 px-6 py-3 rounded-xl text-xs uppercase tracking-widest font-black">
                Minisoccer
            </button>

        </div>

    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

        <?php
        $query = mysqli_query($conn,"SELECT * FROM events WHERE status_event='tersedia' ORDER BY tanggal ASC");

        while($row = mysqli_fetch_assoc($query)):
        ?>

        <div class="match-card glass rounded-[40px] p-8 shadow-2xl"
             data-category="<?php echo $row['kategori']; ?>"
             data-aos="zoom-in">

            <div class="flex justify-between items-start mb-8">

                <span class="bg-white/10 border border-yellow-500/20 text-yellow-500 px-4 py-2 rounded-full text-[10px] uppercase tracking-widest font-bold">
                    <?php echo $row['kategori']; ?>
                </span>

                <div class="text-right">

                    <p class="text-[9px] uppercase text-gray-500 font-black mb-1">
                        Entry Fee
                    </p>

                    <?php if($row['kategori']=='Sepakbola' || $row['kategori']=='Minisoccer'): ?>

                        <p class="text-lg font-black">
                            Pemain: <?php echo number_format($row['harga']/1000,0); ?>K
                        </p>

                        <p class="text-yellow-500 text-sm font-bold">
                            Kiper: <?php echo number_format(($row['harga']-10000)/1000,0); ?>K
                        </p>

                    <?php else: ?>

                        <p class="text-2xl font-black">
                            <?php echo number_format($row['harga']/1000,0); ?>K
                        </p>

                    <?php endif; ?>

                </div>

            </div>

            <h3 class="text-2xl font-black uppercase tracking-tight mb-2">
                <?php echo $row['judul_event']; ?>
            </h3>

            <p class="text-gray-500 uppercase tracking-widest text-xs mb-8">
                <i class="fa-solid fa-location-dot text-yellow-500 mr-2"></i>
                <?php echo $row['lokasi']; ?>
            </p>

            <a href="https://wa.me/<?php echo $setting['wa_admin']; ?>?text=Halo Admin, saya ingin Join match <?php echo $row['judul_event']; ?>"
               class="block text-center bg-yellow-500 text-black py-5 rounded-3xl uppercase tracking-[0.2em] font-black text-xs hover:scale-[1.02] transition">
                Join Now
            </a>

        </div>

        <?php endwhile; ?>

    </div>

</section>

<!-- VENUES -->
<section id="venues" class="py-28 px-6 bg-white/[0.02]">

    <div class="max-w-7xl mx-auto">

        <h2 class="text-4xl font-black uppercase italic tracking-tight mb-14">
            Our Homebase <span class="text-gradient">Venues</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <?php
            $q_venues = mysqli_query($conn,"SELECT * FROM venues ORDER BY id_venue DESC LIMIT 4");

            while($v = mysqli_fetch_assoc($q_venues)):
            ?>

            <div class="glass rounded-[35px] p-5 group">

                <div class="h-44 overflow-hidden rounded-3xl mb-6">

                    <img src="<?php echo $v['foto_venue']; ?>"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                </div>

                <h3 class="font-black uppercase text-sm tracking-widest mb-2">
                    <?php echo $v['nama_venue']; ?>
                </h3>

                <p class="text-[10px] uppercase tracking-widest text-gray-500 leading-relaxed font-bold">
                    <span class="text-yellow-500">
                        <?php echo $v['kategori_sport']; ?>
                    </span>
                    <br>
                    <?php echo $v['alamat_venue']; ?>
                </p>

                <?php if(!empty($v['maps_link'])): ?>

                <a href="<?php echo $v['maps_link']; ?>"
                   target="_blank"
                   class="mt-6 block text-center py-3 rounded-2xl bg-white/5 hover:bg-yellow-500 hover:text-black transition uppercase tracking-widest text-[10px] font-black">
                    Lihat Lokasi
                </a>

                <?php endif; ?>

            </div>

            <?php endwhile; ?>

        </div>

    </div>

</section>

<!-- KAS -->
<section id="kas" class="py-24 px-6 bg-yellow-500 text-black">

    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

        <div data-aos="fade-right">

            <h2 class="text-5xl font-black uppercase italic tracking-tight leading-none mb-4">
                Transparansi<br>Kas Online
            </h2>

            <p class="font-bold text-black/70 mb-8">
                Member dapat mengecek saldo komunitas secara realtime.
            </p>

            <div class="bg-black/10 rounded-3xl p-6 flex justify-between items-center">

                <span class="uppercase tracking-widest text-xs font-black">
                    Total Saldo Kas
                </span>

                <span class="text-3xl font-black italic">
                    Rp <?php echo number_format($saldo_akhir,0,',','.'); ?>
                </span>

            </div>

        </div>

        <div class="bg-white rounded-[35px] p-6 shadow-2xl" data-aos="fade-left">

            <h3 class="text-center uppercase tracking-[0.3em] text-xs font-black mb-5">
                History Terakhir
            </h3>

            <div class="space-y-4 max-h-52 overflow-y-auto">

                <?php
                $kas_h = mysqli_query($conn,"SELECT * FROM kas ORDER BY id_kas DESC LIMIT 5");

                while($rk = mysqli_fetch_assoc($kas_h)):
                ?>

                <div class="flex justify-between border-b border-gray-100 pb-3">

                    <div>

                        <p class="font-bold uppercase text-xs">
                            <?php echo $rk['keterangan']; ?>
                        </p>

                        <p class="text-[10px] text-gray-400 font-bold">
                            <?php echo date('d/m/Y',strtotime($rk['tanggal'])); ?>
                        </p>

                    </div>

                    <p class="<?php echo $rk['tipe']=='masuk' ? 'text-green-600' : 'text-red-500'; ?> font-black text-sm">
                        <?php echo $rk['tipe']=='masuk' ? '+' : '-'; ?>
                        <?php echo number_format($rk['jumlah'],0,',','.'); ?>
                    </p>

                </div>

                <?php endwhile; ?>

            </div>

        </div>

    </div>

</section>

<section id="gallery" class="py-32 px-6">

    <div class="max-w-7xl mx-auto text-center mb-16" data-aos="fade-up">
        <p class="text-yellow-500 uppercase tracking-[0.4em] text-xs font-bold mb-4">
            Moments
        </p>
        <h2 class="text-5xl font-black uppercase italic tracking-tight">
            Activity <span class="text-gradient">Gallery</span>
        </h2>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">

        <?php
        $gal = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC LIMIT 8");

        while($rg = mysqli_fetch_assoc($gal)):
            // Ambil data gambar (bisa berupa path file atau data Base64 dari TiDB Anda)
            $foto_gallery = $rg['foto']; 
        ?>

        <div class="gallery-item relative overflow-hidden rounded-3xl h-64 border border-white/5 group">

            <img src="<?php echo $foto_gallery; ?>"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5 cursor-pointer"
                 onclick="openModal('<?php echo $foto_gallery; ?>')">

                <p class="text-yellow-500 uppercase tracking-widest text-[10px] font-bold transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                    <?php echo $rg['caption']; ?>
                </p>

            </div>

        </div>

        <?php endwhile; ?>

    </div>

</section>

<!-- FOOTER -->
<footer class="pt-24 pb-12 px-6 border-t border-white/10 bg-[#010409]">

    <div class="max-w-7xl mx-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">

            <div>

                <div class="flex items-center gap-3 mb-6">
                    <img src="assets/<?php echo $setting['logo_website']; ?>" class="h-10">
                    <h2 class="text-2xl font-black uppercase text-gradient">
                        <?php echo $setting['nama_website']; ?>
                    </h2>
                </div>

                <p class="text-gray-500 italic mb-6">
                    "<?php echo $setting['deskripsi_website']; ?>"
                </p>

            </div>

            <div>

                <h4 class="uppercase tracking-[0.2em] text-xs font-black mb-6">
                    Navigation
                </h4>

                <ul class="space-y-4 text-gray-500 uppercase text-xs tracking-widest font-bold">

                    <li><a href="#" onclick="scrollToSection('about')" class="hover:text-yellow-500">About</a></li>
                    <li><a href="#" onclick="scrollToSection('events')" class="hover:text-yellow-500">Schedules</a></li>
                    <li><a href="#" onclick="scrollToSection('venues')" class="hover:text-yellow-500">Venues</a></li>
                    <li><a href="#" onclick="scrollToSection('gallery')" class="hover:text-yellow-500">Gallery</a></li>

                </ul>

            </div>

            <div>

                <h4 class="uppercase tracking-[0.2em] text-xs font-black mb-6">
                    Contact
                </h4>

                <p class="text-gray-400 mb-3">
                    +<?php echo $setting['wa_admin']; ?>
                </p>

                <p class="text-gray-400">
                    hi@<?php echo strtolower(str_replace(' ','',$setting['nama_website'])); ?>.com
                </p>

            </div>

            <div>

                <h4 class="uppercase tracking-[0.2em] text-xs font-black mb-6">
                    Admin
                </h4>

                <a href="admin/login.php"
                   class="inline-block bg-white/5 border border-white/5 px-5 py-3 rounded-full hover:text-yellow-500 transition uppercase tracking-widest text-xs font-black">
                    Login Admin
                </a>

            </div>

        </div>

        <div class="border-t border-white/5 pt-10 text-center text-gray-600 uppercase tracking-[0.3em] text-[10px]">
            © 2026 <?php echo $setting['nama_website']; ?> • ALL RIGHTS RESERVED
        </div>

    </div>

</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>

    function scrollToSection(sectionId){

        const element = document.getElementById(sectionId);

        if(element){

            const offset = 80;

            const bodyRect = document.body.getBoundingClientRect().top;

            const elementRect = element.getBoundingClientRect().top;

            const elementPosition = elementRect - bodyRect;

            const offsetPosition = elementPosition - offset;

            window.scrollTo({
                top:offsetPosition,
                behavior:'smooth'
            });

        }

    }

    if(window.location.hash){
        history.replaceState('',document.title,window.location.pathname + window.location.search);
    }

    if('scrollRestoration' in history){
        history.scrollRestoration = 'manual';
    }

    window.scrollTo(0,0);

    AOS.init({
        once:true,
        duration:800
    });

    window.onscroll = function(){

        const nav = document.getElementById('navbar');

        if(window.pageYOffset > 50){

            nav.classList.add('glass','shadow-2xl','py-4');

            nav.classList.remove('py-6');

        }else{

            nav.classList.remove('glass','shadow-2xl','py-4');

            nav.classList.add('py-6');

        }

    }

    function filterMatch(category,event){

        const cards = document.querySelectorAll('.match-card');

        const btns = document.querySelectorAll('.tab-btn');

        btns.forEach(btn=>{
            btn.classList.remove('bg-yellow-500','text-black');
            btn.classList.add('bg-white/5','text-gray-400');
        });

        event.currentTarget.classList.add('bg-yellow-500','text-black');

        cards.forEach(card=>{

            if(category === 'all' || card.dataset.category === category){

                card.style.display = 'block';

            }else{

                card.style.display = 'none';

            }

        });

    }

</script>

</body>
</html>