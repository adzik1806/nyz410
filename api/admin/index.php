<?php
// 1. AKTIFKAN COOKIE SESSION BAWAAN DI VERCEL
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// 2. PROTEKSI AKSES & LOGIN CHECK
if (!isset($_SESSION['admin']) && !isset($_COOKIE['admin_login'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['admin']) && isset($_COOKIE['admin_login'])) {
    $_SESSION['admin'] = true;
}

// 3. AUTO-LOGOUT (3 Jam)
$timeout = 10800; 
if (isset($_COOKIE['last_activity'])) {
    $elapsed_time = time() - $_COOKIE['last_activity'];
    if ($elapsed_time > $timeout) {
        session_unset(); 
        session_destroy();
        setcookie('admin_login', '', time() - 3600, '/');
        setcookie('last_activity', '', time() - 3600, '/');
        header("Location: login.php?pesan=expired");
        exit;
    }
}
setcookie('last_activity', time(), time() + $timeout, '/');

// 4. FILE KONEKSI
include __DIR__ . '/../koneksi/koneksi.php';

// AMBIL DATA SETTING WEBSITE
$setting = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings WHERE id_setting = 1"));

// LOGIKA DATABASE (STATISTIK DASHBOARD)
$total_football = mysqli_num_rows(mysqli_query($conn, "SELECT id_event FROM events WHERE kategori='Sepakbola'"));
$total_futsal   = mysqli_num_rows(mysqli_query($conn, "SELECT id_event FROM events WHERE kategori='Futsal'"));
$total_minisoccer = mysqli_num_rows(mysqli_query($conn, "SELECT id_event FROM events WHERE kategori='Minisoccer'"));
$total_venues   = mysqli_num_rows(mysqli_query($conn, "SELECT id_venue FROM venues"));
$total_sponsors = mysqli_num_rows(mysqli_query($conn, "SELECT id_sponsor FROM sponsors"));

// --- PROSES CRUD (REDIRECT DENGAN HASH AGAR STAY MENU) ---
if (isset($_POST['tambah_event'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = $_POST['kategori'];
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $harga = ($kategori == 'Futsal') ? $_POST['harga_flat'] : $_POST['harga_pemain'];
    
    $id_event_otomatis = time();
    mysqli_query($conn, "INSERT INTO events (id_event, judul_event, kategori, lokasi, tanggal, jam, harga, status_event) VALUES ('$id_event_otomatis', '$judul', '$kategori', '$lokasi', '$tanggal', '$jam', '$harga', 'tersedia')");
    header("Location: index.php#schedules"); exit;
}

if (isset($_POST['tambah_sponsor'])) {
    $nama_sponsor = mysqli_real_escape_string($conn, $_POST['nama_sponsor']);
    $link_website = mysqli_real_escape_string($conn, $_POST['link_website'] ?? '#');
    
    $sponsor_data = "";
    // Menangkap input file dari form
    if (isset($_FILES['logo_icon']) && $_FILES['logo_icon']['tmp_name'] != "") {
        $path = $_FILES['logo_icon']['tmp_name'];
        $type = pathinfo($_FILES['logo_icon']['name'], PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $sponsor_data = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    // Pastikan data tidak kosong sebelum insert
    if (!empty($sponsor_data)) {
        $query = "INSERT INTO sponsors (nama_sponsor, link_website, logo_icon) 
                  VALUES ('$nama_sponsor', '$link_website', '$sponsor_data')";
        mysqli_query($conn, $query);
    }
    
    header("Location: index.php#sponsors");
    exit;
}

if (isset($_POST['tambah_gallery'])) {
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    
    $gallery_data = "";
    if (isset($_FILES['foto_galeri']) && $_FILES['foto_galeri']['tmp_name'] != "") {
        $path = $_FILES['foto_galeri']['tmp_name'];
        $type = pathinfo($_FILES['foto_galeri']['name'], PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $gallery_data = 'data:image/' . $type . ';base64,' . base64_encode($data);
    } elseif (isset($_FILES['foto']) && $_FILES['foto']['tmp_name'] != "") {
        $path = $_FILES['foto']['tmp_name'];
        $type = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $gallery_data = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    if (!empty($gallery_data)) {
        $query = "INSERT INTO gallery (foto, caption) VALUES ('$gallery_data', '$caption')";
        mysqli_query($conn, $query);
    }
    header("Location: index.php#gallery");
    exit;
}

if (isset($_POST['tambah_kas'])) {
    $ket = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $jml = $_POST['jumlah'];
    $tgl = $_POST['tanggal'];
    $tipe = $_POST['tipe'];
    
    $id_kas_otomatis = time();
    mysqli_query($conn, "INSERT INTO kas (id_kas, keterangan, tanggal, jumlah, tipe) VALUES ('$id_kas_otomatis', '$ket', '$tgl', '$jml', '$tipe')");
    header("Location: index.php#kas"); exit;
}

if (isset($_POST['tambah_venue'])) {
    $nama_v = mysqli_real_escape_string($conn, $_POST['nama_venue']);
    
    // Solusi anti-error untuk menangkap kategori dari HTML form kamu
    $kat_v = 'Sepakbola'; 
    if (isset($_POST['kategori_sport']) && !empty(trim($_POST['kategori_sport']))) {
        $kat_v = mysqli_real_escape_string($conn, $_POST['kategori_sport']);
    } elseif (isset($_POST['kategori_venue']) && !empty(trim($_POST['kategori_venue']))) {
        $kat_v = mysqli_real_escape_string($conn, $_POST['kategori_venue']);
    } elseif (isset($_POST['kategori']) && !empty(trim($_POST['kategori']))) {
        $kat_v = mysqli_real_escape_string($conn, $_POST['kategori']);
    }
    
    $alamat_v = mysqli_real_escape_string($conn, $_POST['alamat']);
    $maps = mysqli_real_escape_string($conn, $_POST['maps_link']);
    
    // Default jika tidak upload foto
    $foto_data = ""; 
    if (isset($_FILES['foto_venue']) && $_FILES['foto_venue']['tmp_name'] != "") {
        $path = $_FILES['foto_venue']['tmp_name'];
        $type = pathinfo($_FILES['foto_venue']['name'], PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $foto_data = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    // Insert tanpa menyertakan ID manual (menggunakan AUTO_INCREMENT database)
    $query = "INSERT INTO venues (nama_venue, kategori_sport, alamat_venue, maps_link, foto_venue) 
              VALUES ('$nama_v', '$kat_v', '$alamat_v', '$maps', '$foto_data')";
              
    mysqli_query($conn, $query);
    header("Location: index.php#venues"); 
    exit;
}

if (isset($_POST['update_settings'])) {
    $web_name = mysqli_real_escape_string($conn, $_POST['nama_website']);
    $web_desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $web_wa   = mysqli_real_escape_string($conn, $_POST['wa_admin']);
    mysqli_query($conn, "UPDATE settings SET nama_website='$web_name', deskripsi_website='$web_desc', wa_admin='$web_wa', stats_member='".$_POST['stats_member']."', stats_venue='".$_POST['stats_venue']."' WHERE id_setting=1");
    
    if($_FILES['logo_web']['name'] != "") {
        $logo_name = uniqid() . "_" . $_FILES['logo_web']['name'];
        mysqli_query($conn, "UPDATE settings SET logo_website='$logo_name' WHERE id_setting=1");
    }
    header("Location: index.php#settings"); exit;
}

// LOGIKA HAPUS (DENGAN REDIRECT HASH)
if (isset($_GET['hapus'])) { mysqli_query($conn, "DELETE FROM events WHERE id_event = ".intval($_GET['hapus'])); header("Location: index.php#schedules"); exit; }
if (isset($_GET['hapus_venue'])) { mysqli_query($conn, "DELETE FROM venues WHERE id_venue = ".intval($_GET['hapus_venue'])); header("Location: index.php#venues"); exit; }
if (isset($_GET['hapus_sponsor'])) { mysqli_query($conn, "DELETE FROM sponsors WHERE id_sponsor = ".intval($_GET['hapus_sponsor'])); header("Location: index.php#sponsors"); exit; }
if (isset($_GET['hapus_kas'])) { mysqli_query($conn, "DELETE FROM kas WHERE id_kas = ".intval($_GET['hapus_kas'])); header("Location: index.php#kas"); exit; }
if (isset($_GET['hapus_gallery'])) { mysqli_query($conn, "DELETE FROM gallery WHERE id_gallery = ".intval($_GET['hapus_gallery'])); header("Location: index.php#gallery"); exit; }

// SALDO KAS
$m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='masuk'"));
$k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='keluar'"));
$saldo_akhir = ($m['total'] ?? 0) - ($k['total'] ?? 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | <?php echo $setting['nama_website']; ?></title>
    <link class="favicon" rel="icon" type="image/png" href="../assets/<?php echo $setting['logo_website']; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: #e2e8f0; overflow: hidden; }
        .glass { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .text-gradient { background: linear-gradient(to right, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .spa-section { display: none; }
        .spa-section.active { display: block; animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-60 border-r border-white/5 bg-[#010409] hidden md:flex flex-col fixed h-full z-50">
        <div class="p-6">
            <h1 class="text-lg font-black text-gradient uppercase tracking-tighter italic"><?php echo $setting['nama_website']; ?></h1>
            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-[0.2em]">Management</p>
        </div>
        <nav class="flex-1 px-3 space-y-1">
            <button onclick="showSection('dashboard')" id="btn-dashboard" class="nav-link w-full flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:bg-white/5 transition">
                <i class="fa-solid fa-house w-4"></i> Dashboard
            </button>
            <button onclick="showSection('schedules')" id="btn-schedules" class="nav-link w-full flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:bg-white/5 transition">
                <i class="fa-solid fa-calendar w-4"></i> Schedules
            </button>
            <button onclick="showSection('venues')" id="btn-venues" class="nav-link w-full flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:bg-white/5 transition">
                <i class="fa-solid fa-map-pin w-4"></i> Venues
            </button>
            <button onclick="showSection('kas')" id="btn-kas" class="nav-link w-full flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:bg-white/5 transition">
                <i class="fa-solid fa-wallet w-4"></i> Kas Online
            </button>
            <button onclick="showSection('gallery')" id="btn-gallery" class="nav-link w-full flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:bg-white/5 transition">
                <i class="fa-solid fa-image w-4"></i> Gallery
            </button>
            <button onclick="showSection('sponsors')" id="btn-sponsors" class="nav-link w-full flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:bg-white/5 transition">
                <i class="fa-solid fa-handshake w-4"></i> Sponsors
            </button>
            <button onclick="showSection('settings')" id="btn-settings" class="nav-link w-full flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:bg-white/5 transition">
                <i class="fa-solid fa-cog w-4"></i> Settings
            </button>
            <div class="h-px bg-white/5 my-4 mx-3"></div>
            <a href="../index.php" target="_blank" class="flex items-center gap-3 p-3 rounded-xl text-gray-400 font-semibold text-[13px] hover:text-yellow-500">
                <i class="fa-solid fa-arrow-up-right-from-square w-4"></i> Live Site
            </a>
        </nav>
        <div class="p-4 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 p-3 rounded-xl text-red-500 font-bold text-[13px] hover:bg-red-500/10 transition">
                <i class="fa-solid fa-power-off w-4"></i> Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 md:ml-60 p-6 md:p-8 overflow-y-auto h-screen">
        <header class="flex justify-between items-center mb-8">
            <div id="page-title"><h2 class="text-xl font-bold uppercase tracking-tight text-white">Dashboard</h2></div>
            <p id="realtime-clock" class="text-xs font-medium text-yellow-500 italic"></p>
        </header>

        <div id="section-dashboard" class="spa-section active">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="glass p-4 rounded-2xl border-l-4 border-yellow-500">
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Football</p>
                    <h3 class="text-xl font-black text-white"><?php echo $total_football; ?></h3>
                </div>
                <div class="glass p-4 rounded-2xl border-l-4 border-blue-500">
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Futsal</p>
                    <h3 class="text-xl font-black text-white"><?php echo $total_futsal; ?></h3>
                </div>
                <div class="glass p-4 rounded-2xl border-l-4 border-orange-500">
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Minisoccer</p>
                    <h3 class="text-xl font-black text-white"><?php echo $total_minisoccer; ?></h3>
                </div>
                <div class="glass p-4 rounded-2xl border-l-4 border-green-500">
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Venues</p>
                    <h3 class="text-xl font-black text-white"><?php echo $total_venues; ?></h3>
                </div>
                <div class="glass p-4 rounded-2xl border-l-4 border-purple-500">
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Sponsors</p>
                    <h3 class="text-xl font-black text-white"><?php echo $total_sponsors; ?></h3>
                </div>
            </div>

            <div class="glass p-6 rounded-3xl border-l-4 border-emerald-500 mb-8 flex justify-between items-center">
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Kas Komunitas</p>
                    <h3 class="text-2xl font-black text-white italic">Rp <?php echo number_format($saldo_akhir, 0, ',', '.'); ?></h3>
                </div>
                <i class="fa-solid fa-chart-line text-3xl text-emerald-500/20"></i>
            </div>

            <div class="glass p-10 rounded-[35px] relative overflow-hidden shadow-xl border border-white/5">
                <div class="relative z-10">
                    <h4 class="text-yellow-500 font-bold text-xs uppercase tracking-[0.3em] mb-2">Authenticated System</h4>
                    <h3 class="text-3xl font-black mb-3 uppercase">Welcome Back, <span class="text-gradient">Admin!</span></h3>
                    <p class="text-gray-400 max-w-lg text-sm leading-relaxed">Kelola seluruh konten website Nyenkzer 410 secara terpusat di sini. Dashboard akan logout otomatis jika tidak ada aktivitas selama 3 jam untuk menjaga keamanan data.</p>
                    <div class="flex gap-3 mt-6">
                        <button onclick="showSection('schedules')" class="bg-yellow-500 text-black px-5 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-yellow-400 transition">Manage Match</button>
                        <button onclick="showSection('kas')" class="bg-white/5 border border-white/10 text-white px-5 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition">Finance Log</button>
                    </div>
                </div>
                <i class="fa-solid fa-shield-halved absolute -right-10 -bottom-10 text-[200px] text-white/[0.02] rotate-12"></i>
            </div>
        </div>

        <div id="section-kas" class="spa-section">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <form action="" method="POST" class="glass p-6 rounded-3xl h-fit">
                    <h3 class="font-bold mb-4 text-sm uppercase text-yellow-500 italic">Transaksi Baru</h3>
                    <div class="space-y-3">
                        <input type="text" name="keterangan" placeholder="Keterangan" class="w-full bg-white/5 p-3 rounded-xl outline-none border border-white/5 text-white" required>
                        <input type="number" name="jumlah" placeholder="Jumlah" class="w-full bg-white/5 p-3 rounded-xl outline-none border border-white/5 text-white" required>
                        <input type="date" name="tanggal" class="w-full bg-white/5 p-3 rounded-xl outline-none border border-white/5 text-gray-400" required>
                        <select name="tipe" class="w-full bg-white/5 p-3 rounded-xl outline-none border border-white/5 text-gray-400">
                            <option value="masuk">Masuk (+)</option>
                            <option value="keluar">Keluar (-)</option>
                        </select>
                        <button type="submit" name="tambah_kas" class="w-full bg-yellow-500 text-black font-black py-3 rounded-xl uppercase text-[11px] tracking-widest mt-2 hover:bg-yellow-400 transition">Simpan</button>
                    </div>
                </form>

                <div class="glass p-6 rounded-3xl lg:col-span-2">
                    <div class="grid grid-cols-3 gap-3 mb-6">
                        <?php
                        $total_m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='masuk'"));
                        $total_k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM kas WHERE tipe='keluar'"));
                        $current_saldo = ($total_m['total'] ?? 0) - ($total_k['total'] ?? 0);
                        ?>
                        <div class="bg-white/[0.02] border border-white/5 p-3 rounded-2xl">
                            <p class="text-[8px] text-gray-500 uppercase font-bold">Total Masuk</p>
                            <p class="text-xs font-black text-green-500">Rp <?php echo number_format($total_m['total'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                        <div class="bg-white/[0.02] border border-white/5 p-3 rounded-2xl">
                            <p class="text-[8px] text-gray-500 uppercase font-bold">Total Keluar</p>
                            <p class="text-xs font-black text-red-500">Rp <?php echo number_format($total_k['total'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                        <div class="bg-yellow-500/10 border border-yellow-500/20 p-3 rounded-2xl">
                            <p class="text-[8px] text-yellow-600 uppercase font-bold">Sisa Saldo</p>
                            <p class="text-xs font-black text-yellow-500">Rp <?php echo number_format($current_saldo, 0, ',', '.'); ?></p>
                        </div>
                    </div>

                    <h3 class="font-bold mb-4 text-sm uppercase text-gray-400 italic">History Log</h3>
                    <div class="max-h-[300px] overflow-y-auto space-y-2 pr-2">
                        <?php $k_res = mysqli_query($conn, "SELECT * FROM kas ORDER BY id_kas DESC");
                        while($rk = mysqli_fetch_assoc($k_res)){ ?>
                        <div class="flex justify-between items-center p-3 border-b border-white/5 hover:bg-white/[0.01]">
                            <div>
                                <p class="text-[11px] font-bold text-white uppercase"><?php echo $rk['keterangan']; ?></p>
                                <p class="text-[9px] text-gray-500"><?php echo $rk['tanggal']; ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black <?php echo $rk['tipe']=='masuk' ? 'text-green-500' : 'text-red-500'; ?>">
                                    <?php echo ($rk['tipe']=='masuk'?'+':'-').number_format($rk['jumlah'],0,',','.'); ?>
                                </p>
                                <a href="?hapus_kas=<?php echo $rk['id_kas']; ?>" onclick="return confirm('Hapus transaksi ini?')" class="text-[8px] text-gray-700 hover:text-red-500 uppercase font-black transition">Hapus</a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="section-schedules" class="spa-section">
             <form action="" method="POST" class="glass p-6 rounded-3xl mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="judul" required placeholder="Judul Match" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-sm text-white">
                <select name="kategori" id="kategori_event" onchange="updatePriceInput()" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-gray-400 text-sm">
                    <option value="Sepakbola">Sepakbola</option><option value="Futsal">Futsal</option><option value="Minisoccer">Minisoccer</option>
                </select>
                <input type="text" name="lokasi" required placeholder="Lokasi" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-sm text-white">
                <input type="date" name="tanggal" required class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-gray-400">
                <input type="time" name="jam" required class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-gray-400">
                <div class="flex gap-2">
                    <input type="number" name="harga_flat" id="input_flat" placeholder="Harga" class="hidden w-full bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-sm text-white">
                    <input type="number" name="harga_pemain" id="input_pemain" placeholder="Hrg Pemain" class="w-full bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-sm text-white">
                    <input type="number" name="harga_kiper" id="input_kiper" placeholder="Hrg Kiper" class="w-full bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-sm text-white">
                </div>
                <button type="submit" name="tambah_event" class="md:col-span-3 bg-yellow-500 text-black font-black py-3 rounded-xl uppercase text-[11px] tracking-widest hover:bg-yellow-400 transition">Publish Match</button>
            </form>
            <div class="glass rounded-2xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead><tr class="bg-white/5 text-[9px] uppercase text-gray-500 font-bold"><th class="px-6 py-3">Match</th><th class="px-6 py-3 text-center">Fees</th><th class="px-6 py-3 text-right">Aksi</th></tr></thead>
                    <tbody>
                    <?php $res = mysqli_query($conn, "SELECT * FROM events ORDER BY id_event DESC"); while($row = mysqli_fetch_assoc($res)) { ?>
                    <tr class="border-t border-white/5 hover:bg-white/[0.01]">
                        <td class="px-6 py-4"><span class="font-bold text-white uppercase text-[11px]"><?php echo $row['judul_event']; ?></span><br><span class="text-[9px] text-gray-500 italic"><?php echo $row['kategori']; ?> @ <?php echo $row['lokasi']; ?></span></td>
                        <td class="px-6 py-4 text-center font-bold text-[11px]">
                            <?php echo ($row['kategori'] == 'Futsal') ? number_format($row['harga']/1000, 0).'K' : 'P:'.number_format($row['harga']/1000, 0).'K | K:'.number_format(($row['harga']-10000)/1000, 0).'K'; ?>
                        </td>
                        <td class="px-6 py-4 text-right"><a href="?hapus=<?php echo $row['id_event']; ?>" onclick="return confirm('Hapus match?')" class="text-red-500 transition px-2"><i class="fa-solid fa-trash-can"></i></a></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="section-venues" class="spa-section">
            <form action="" method="POST" enctype="multipart/form-data" class="glass p-5 rounded-2xl mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="nama_venue" required placeholder="Nama Venue" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-white">
                <select name="kategori_sport" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-gray-400">
                    <option value="Sepakbola">Sepakbola</option>
                    <option value="Futsal">Futsal</option>
                    <option value="Minisoccer">Minisoccer</option>
                </select>
                <input type="text" name="alamat" required placeholder="Alamat" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-white">
                <input type="text" name="maps_link" placeholder="Link Maps" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-white">
                <input type="file" name="foto_venue" required class="bg-white/5 border border-white/10 p-2 rounded-xl text-[10px] text-gray-400">
                <button type="submit" name="tambah_venue" class="md:col-span-5 bg-yellow-500 text-black font-black py-2.5 rounded-xl uppercase text-[11px] tracking-widest mt-2 transition-all">Save Venue</button>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <?php $qv = mysqli_query($conn, "SELECT * FROM venues ORDER BY id_venue DESC"); while($v = mysqli_fetch_assoc($qv)) { ?>
                <div class="glass p-3 rounded-2xl flex items-center gap-3 border-white/5 group">
                    <img src="<?php echo $v['foto_venue']; ?>" alt="Foto Venue" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                    <div class="flex-1"><h4 class="font-bold uppercase text-[11px] text-white"><?php echo $v['nama_venue']; ?></h4><p class="text-[9px] text-gray-500 italic"><?php echo $v['alamat_venue']; ?></p></div>
                    <a href="?hapus_venue=<?php echo $v['id_venue']; ?>" onclick="return confirm('Hapus venue?')" class="text-red-500/50 hover:text-red-500 transition-colors px-2"><i class="fa-solid fa-trash-can text-sm"></i></a>
                </div>
                <?php } ?>
            </div>
        </div>

        <div id="section-gallery" class="spa-section">
            <form action="" method="POST" enctype="multipart/form-data" class="glass p-4 rounded-2xl mb-6 flex gap-4 items-center">
                <input type="file" name="foto" class="text-[10px]" required>
                <input type="text" name="caption" placeholder="Caption Foto..." class="flex-1 bg-white/5 p-2 rounded-lg border border-white/5 outline-none text-sm text-white" required>
                <button type="submit" name="tambah_gallery" class="bg-yellow-500 text-black font-black px-4 py-2 rounded-lg uppercase text-[10px]">Upload</button>
            </form>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                <?php $gl = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC");
                while($rg = mysqli_fetch_assoc($gl)){ ?>
                <div class="relative group aspect-square overflow-hidden rounded-xl border border-white/5">
                    <img src="<?php echo $rg['foto']; ?>" alt="Foto Gallery" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    <a href="?hapus_gallery=<?php echo $rg['id_gallery']; ?>" onclick="return confirm('Hapus foto?')" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center"><i class="fa-solid fa-trash text-white text-xs"></i></a>
                </div>
                <?php } ?>
            </div>
        </div>

        <div id="section-sponsors" class="spa-section">
            <form action="" method="POST" enctype="multipart/form-data" class="glass p-6 rounded-2xl mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="nama_sponsor" required placeholder="Nama Brand" class="bg-white/5 border border-white/10 p-3 rounded-xl outline-none text-sm text-white">
                <input type="file" name="logo_sponsor" required class="bg-white/5 border border-white/10 p-2 rounded-xl text-[10px]">
                <button type="submit" name="tambah_sponsor" class="bg-yellow-500 text-black font-black py-2 rounded-xl uppercase text-[11px]">Add</button>
            </form>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                <?php $qs = mysqli_query($conn, "SELECT * FROM sponsors ORDER BY id_sponsor DESC"); while($s = mysqli_fetch_assoc($qs)) { ?>
                <div class="glass p-4 rounded-2xl border-white/5 flex flex-col items-center relative group">
                    <a href="?hapus_sponsor=<?php echo $s['id_sponsor']; ?>" onclick="return confirm('Hapus partner?')" class="absolute top-1 right-1 text-red-500 opacity-0 group-hover:opacity-100 transition"><i class="fa-solid fa-circle-xmark text-xs"></i></a>
                    <img src="<?php echo $s['logo_icon']; ?>" alt="Logo Sponsor" style="width: 50px; height: 50px; object-fit: contain;">
                    <p class="text-[9px] font-bold uppercase text-gray-400 mt-2"><?php echo $s['nama_sponsor']; ?></p>
                </div>
                <?php } ?>
            </div>
        </div>

        <div id="section-settings" class="spa-section">
            <form action="" method="POST" enctype="multipart/form-data" class="glass p-8 rounded-3xl max-w-2xl border border-white/5 shadow-2xl mx-auto">
                <div class="space-y-4 text-white">
                    <input type="text" name="nama_website" value="<?php echo $setting['nama_website']; ?>" class="w-full bg-white/5 border border-white/10 p-3.5 rounded-xl outline-none font-bold text-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" name="stats_member" value="<?php echo $setting['stats_member']; ?>" class="w-full bg-white/5 p-3 rounded-xl outline-none border border-white/10">
                        <input type="number" name="stats_venue" value="<?php echo $setting['stats_venue']; ?>" class="w-full bg-white/5 p-3 rounded-xl outline-none border border-white/10">
                    </div>
                    <textarea name="deskripsi" class="w-full bg-white/5 p-4 rounded-xl outline-none border border-white/10 text-sm h-24"><?php echo $setting['deskripsi_website']; ?></textarea>
                    <button type="submit" name="update_settings" class="w-full bg-yellow-500 text-black font-black py-3 rounded-xl uppercase text-[11px] tracking-widest mt-2 transition-all">Update Settings</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function showSection(sectionId) {
            window.location.hash = sectionId;

            document.querySelectorAll('.spa-section').forEach(section => {
                section.classList.remove('active');
            });
            const targetSection = document.getElementById('section-' + sectionId);
            if (targetSection) {
                targetSection.classList.add('active');
            }
            
            document.querySelectorAll('.nav-link').forEach(btn => {
                btn.classList.remove('bg-white/10', 'text-white');
                btn.classList.add('text-gray-400');
            });
            const activeBtn = document.getElementById('btn-' + sectionId);
            if (activeBtn) {
                activeBtn.classList.add('bg-white/10', 'text-white');
                activeBtn.classList.remove('text-gray-400');
            }

            const titleMap = {
                'dashboard': 'Dashboard',
                'schedules': 'Schedules',
                'venues': 'Venues',
                'kas': 'Kas Online',
                'gallery': 'Gallery',
                'sponsors': 'Sponsors',
                'settings': 'Settings'
            };
            document.getElementById('page-title').innerHTML = `<h2 class="text-xl font-bold uppercase tracking-tight text-white">${titleMap[sectionId] || 'Dashboard'}</h2>`;
        }

        function updatePriceInput() {
            const kat = document.getElementById('kategori_event').value;
            if (kat === 'Futsal') {
                document.getElementById('input_flat').classList.remove('hidden');
                document.getElementById('input_pemain').classList.add('hidden');
                document.getElementById('input_kiper').classList.add('hidden');
            } else {
                document.getElementById('input_flat').classList.add('hidden');
                document.getElementById('input_pemain').classList.remove('hidden');
                document.getElementById('input_kiper').classList.remove('hidden');
            }
        }

        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            document.getElementById('realtime-clock').textContent = `${dateStr} • ${timeStr}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        window.addEventListener('DOMContentLoaded', () => {
            const currentHash = window.location.hash.substring(1);
            if (currentHash) {
                showSection(currentHash);
            } else {
                showSection('dashboard');
            }
        });
    </script>
</body>
</html>