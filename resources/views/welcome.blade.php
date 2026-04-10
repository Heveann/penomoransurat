<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penomoran Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <h1 class="text-xl font-bold text-green-600">📄 Penomoran Surat</h1>

        <!-- Menu + Login -->
        <div class="flex items-center gap-6">
            <ul class="hidden md:flex gap-6 font-medium text-gray-600">
                <li><a href="#fitur" class="hover:text-green-600">Fitur</a></li>
                <li><a href="#statistik" class="hover:text-green-600">Statistik</a></li>
                <li><a href="#about" class="hover:text-green-600">Tentang</a></li>
            </ul>
            <a href="/login" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition">
                Login
            </a>
        </div>
    </div>
</nav>

    <!-- Hero -->
    <section class="container mx-auto px-6 py-15 flex flex-col md:flex-row items-center gap-10">
        <div class="flex-1">
            <h2 class="text-4xl font-bold leading-tight mb-6">
                Penomoran <span class="text-green-600">Surat Instansi</span> Jadi Lebih Mudah
            </h2>
            <p class="text-gray-600 mb-6 text-lg">
                Kelola surat masuk dan keluar secara otomatis, cepat, dan terorganisir dalam satu sistem.
            </p>
            <a href="/login" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold shadow hover:bg-green-700 transition">
                Mulai Sekarang
            </a>
        </div>
        <div class="flex-1">
            <img src="https://cdn-icons-png.flaticon.com/512/4781/4781517.png" 
                 alt="Ilustrasi Surat" 
                 class="w-full max-w-md mx-auto">
        </div>
    </section>

    <!-- Fitur -->
    <section id="fitur" class="container mx-auto px-6 py-20">
        <h3 class="text-3xl font-bold text-center mb-14">Fitur Utama</h3>
        <div class="grid md:grid-cols-3 gap-10">
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-lg transition text-center">
                <div class="text-green-600 text-5xl mb-4">📌</div>
                <h4 class="font-semibold text-lg mb-2">Nomor Otomatis</h4>
                <p class="text-gray-600">Nomor surat dibuat otomatis sesuai format instansi.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-lg transition text-center">
                <div class="text-green-600 text-5xl mb-4">🔍</div>
                <h4 class="font-semibold text-lg mb-2">Pencarian Cepat</h4>
                <p class="text-gray-600">Temukan surat dengan filter jenis, klasifikasi, atau tanggal.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow hover:shadow-lg transition text-center">
                <div class="text-green-600 text-5xl mb-4">📂</div>
                <h4 class="font-semibold text-lg mb-2">Cetak & Arsip</h4>
                <p class="text-gray-600">Unduh nomor surat dalam format PDF dan simpan arsip digital.</p>
            </div>
        </div>
    </section>

    <!-- Statistik -->
    <section id="statistik" class="bg-green-50 py-20">
        <div class="container mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold mb-10">Statistik Sistem</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <p class="text-4xl font-bold text-green-600">12.340+</p>
                    <p class="text-gray-600">Surat Tercatat</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-green-600">150+</p>
                    <p class="text-gray-600">Unit Kerja</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-green-600">500+</p>
                    <p class="text-gray-600">Pengguna Aktif</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id ="about" class="bg-gray-900 text-gray-300 py-10">
    <div class="container text-center mx-auto px-6">
        <h4 class="text-lg font-bold text-white mb-4">📄 Sistem Penomoran Surat</h4>
        <p class="text-gray-400 mb-6">
            Membantu instansi mengelola surat masuk dan keluar dengan cepat, terstruktur, dan aman.
        </p>
        <div class="flex justify-center gap-6 mb-6">
            <a href="#" class="hover:text-white">🌐 Website</a>
            <a href="#" class="hover:text-white">📘 LinkedIn</a>
            <a href="#" class="hover:text-white">📷 Instagram</a>
        </div>
        <p class="text-center text-gray-500">&copy; 2025 Sistem Penomoran Surat. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
