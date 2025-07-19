<?php include __DIR__ . '/../src/db.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/Logo_bulat.png" type="image">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <title>Menu - Nasi Uduk 88 Brebes Berhias</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-100">
    <nav
        class="fixed top-[30px] left-1/2 transform -translate-x-1/2 w-[calc(100%-20px)] bg-white bg-opacity-80 backdrop-blur-md shadow-lg rounded-lg z-50 p-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="index.php">
                        <img src="img/Logo.png" alt="Logo" class="rounded-full w-14 h-14 object-cover" />
                    </a>
                </div>

                <div class="hidden md:flex space-x-7 items-center">
                    <a href="index.php" class="text-yellow-800  hover:text-orange-600">Beranda</a>
                    <a href="menu.php" class="text-yellow-800 font-bold hover:text-orange-600">Menu</a>
                    <a href="#kontak" class="text-yellow-800  hover:text-orange-600">Kontak</a>
                    <button onclick="openModal()"
                        class="hidden md:inline-flex text-yellow-700 hover:text-orange-600 relative">
                        <i class='bx bx-cart text-2xl'></i>
                        <span id="cartCount"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">0</span>
                    </button>
                    <a href="auth.php"
                        class="bg-orange-700 hover:bg-orange-900 px-4 py-2 rounded-lg shadow text-white font-medium">Sign
                        In</a>
                </div>

                <div class="md:hidden">
                    <button id="menu-button" class="text-gray-800 hover:text-orange-600 focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu"
            class="hidden md:hidden border-t border-gray-200 rounded-lg shadow-md transition duration-200 ease-in-out">
            <a href="index.php" class="block px-4 py-2 text-yellow-800 hover:text-orange-600">Beranda</a>
            <a href="menu.php" class="block px-4 py-2 text-yellow-800 font-bold hover:text-orange-600">Menu</a>
            <a href="#kontak" class="block px-4 py-2 text-yellow-800  hover:text-orange-600">Kontak</a>
            <a href="auth.php"
                class="block px-4 py-2 mt-3 text-center bg-orange-700 hover:bg-orange-900 text-white rounded-lg font-medium shadow">Sign
                In</a>
        </div>
    </nav>

    <button onclick="openModal()" id="cartBtn"
        class="md:hidden fixed bottom-5 right-5 z-50 bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-full shadow-lg transition">
        <i class='bx bx-cart text-2xl'></i>
        <span id="cartCountMobile"
            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">0</span>
    </button>

    <section class="relative h-[650px] bg-fixed bg-center bg-cover"
        style="background-image: url('img/meja makan.png');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 flex flex-col justify-center items-center text-center text-white h-full px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-red-60 0">Menu Nasi Uduk</h1>
            <p class="text-lg md:text-2xl mb-6 max-w-xl">Cita rasa khas Brebes yang menggugah selera, langsung ke meja
                Anda.</p>

        </div>
    </section>

    <nav class="mt-6" id="menu">
        <ul class="flex justify-center space-x-8 border-b-2 border-gray-200 overflow-x-auto pb-2">
            <li class="tab-item text-green-500 border-b-2 border-green-500 pb-2 px-1 whitespace-nowrap cursor-pointer" onclick="switchTab('minuman', this)">Minuman</li>
            <li class="tab-item text-gray-500 pb-2 px-1 whitespace-nowrap cursor-pointer" onclick="switchTab('makanan', this)">Makanan</li>
            <li class="tab-item text-gray-500 pb-2 px-1 whitespace-nowrap cursor-pointer" onclick="switchTab('paket', this)">Menu Paket</li>
        </ul>
    </nav>

    <div class="container mx-auto max-w-7xl py-12 px-4">

        <div id="minuman" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM menus WHERE category='minuman' ORDER BY id DESC");
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="flex items-center justify-between bg-white shadow-lg p-6 rounded-lg gap-4">';
                    echo '<div class="flex items-center">';
                    echo '<img src="img/' . htmlspecialchars($row['foto']) . '" alt="' . htmlspecialchars($row['nama_menu']) . '" class="w-12 h-12 rounded-full mr-4 object-cover" />';
                    echo '<div>';
                    echo '<h2 class="text-lg font-semibold text-gray-800">' . htmlspecialchars($row['nama_menu']) . '</h2>';
                    echo '<p class="text-sm text-gray-600">' . htmlspecialchars($row['deskripsi']) . '</p>';
                    echo '<p class="text-yellow-500 font-bold mt-1">Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
                    echo '</div></div>';
                    echo '<button class="add-to-cart bg-yellow-500 text-white rounded-full p-2 hover:bg-yellow-600 transition">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2 5m12-5l2 5m-6 0a2 2 0 100 4 2 2 0 000-4z" /></svg>';
                    echo '</button>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-span-3 text-center text-gray-500">Data menu minuman tidak ditemukan.</div>';
            }
            ?>
        </div>
        <div id="makanan" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM menus WHERE category='makanan' ORDER BY id DESC");
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="flex items-center justify-between bg-white shadow-lg p-6 rounded-lg gap-4">';
                    echo '<div class="flex items-center">';
                    echo '<img src="img/' . htmlspecialchars($row['foto']) . '" alt="' . htmlspecialchars($row['nama_menu']) . '" class="w-12 h-12 rounded-full mr-4 object-cover" />';
                    echo '<div>';
                    echo '<h2 class="text-lg font-semibold text-gray-800">' . htmlspecialchars($row['nama_menu']) . '</h2>';
                    echo '<p class="text-sm text-gray-600">' . htmlspecialchars($row['deskripsi']) . '</p>';
                    echo '<p class="text-yellow-500 font-bold mt-1">Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
                    echo '</div></div>';
                    echo '<button class="add-to-cart bg-yellow-500 text-white rounded-full p-2 hover:bg-yellow-600 transition">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2 5m12-5l2 5m-6 0a2 2 0 100 4 2 2 0 000-4z" /></svg>';
                    echo '</button>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-span-3 text-center text-gray-500">Data menu makanan tidak ditemukan.</div>';
            }
            ?>
        </div>
        <div id="paket" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM menus WHERE category='paket' ORDER BY id DESC");
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="flex items-center justify-between bg-white shadow-lg p-6 rounded-lg gap-4">';
                    echo '<div class="flex items-center">';
                    echo '<img src="img/' . htmlspecialchars($row['foto']) . '" alt="' . htmlspecialchars($row['nama_menu']) . '" class="w-12 h-12 rounded-full mr-4 object-cover" />';
                    echo '<div>';
                    echo '<h2 class="text-lg font-semibold text-gray-800">' . htmlspecialchars($row['nama_menu']) . '</h2>';
                    echo '<p class="text-sm text-gray-600">' . htmlspecialchars($row['deskripsi']) . '</p>';
                    echo '<p class="text-yellow-500 font-bold mt-1">Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
                    echo '</div></div>';
                    echo '<button class="add-to-cart bg-yellow-500 text-white rounded-full p-2 hover:bg-yellow-600 transition">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2 5m12-5l2 5m-6 0a2 2 0 100 4 2 2 0 000-4z" /></svg>';
                    echo '</button>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-span-3 text-center text-gray-500">Data menu paket tidak ditemukan.</div>';
            }
            ?>
        </div>
    </div>

    <div id="sidebar"
        class="fixed top-0 right-0 h-full w-full sm:w-80 lg:w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-[1100] flex flex-col">

        <div class="flex-shrink-0 flex items-center justify-between p-4 border-b bg-white">
            <h2 class="text-xl font-semibold">Keranjang</h2>
            <button onclick="closeModal()" class="text-gray-600 hover:text-red-500 text-xl">✖</button>
        </div>

        <div class="flex-grow overflow-y-auto p-4">
            <div id="cartItems" class="flex flex-col space-y-4">
            </div>
        </div>

        <div class="flex-shrink-0 bg-white border-t p-4">
            <div class="border border-orange-500 rounded-lg p-4 mb-3">
                <div class="flex justify-between mb-1">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between mb-1">
                    <span>Biaya layanan</span>
                    <span id="serviceFee">Rp 2.000</span>
                </div>
                <div class="flex justify-between font-bold text-orange-500 text-xl mt-2">
                    <span>Total</span>
                    <span id="total">Rp 2.000</span>
                </div>
            </div>
            <button
                class="w-full bg-green-500 text-white py-3 rounded-lg text-lg font-semibold hover:bg-green-600 transition">
                Checkout
            </button>
        </div>
    </div>


    <footer class="bg-gradient-to-b from-red-900 to-orange-800 text-white w-full py-10" id="kontak">

        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Tentang Kami</h3>
                    <p class="text-white leading-relaxed">
                        Kami berkomitmen untuk menyajikan makanan berkualitas terbaik dan memberikan
                        pengalaman makan
                        yang luar biasa.
                        <br><br>
                        <strong>Jam Operasional:</strong> 10:30 - 23:59
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="text-white space-y-2">
                        <li><a href="index.php" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="index.php#about" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="menu.php" class="hover:text-white transition">Menu</a></li>
                        <li><a href="#kontak" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-4">Kontak Kami</h3>
                    <ul class="text-white space-y-2 text-sm">
                        <li><span class="font-semibold">Alamat:</span> Jl. Abdul Rahman Saleh No.45, Husen
                            Sastranegara,
                            Kec. Cicendo, Kota Bandung, Jawa Barat 40174</li>
                        <li><span class="font-semibold">Telepon:</span> +62 819-1047-1665</li>
                        <li><span class="font-semibold">Email:</span> nu88brebes@gmail.com</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-4">Kirim Pesan kepada Kami</h3>
                    <form id="contactForm" class="bg-white/30 backdrop-blur-md p-4 rounded-lg space-y-3">
                        <input type="text" id="nama" placeholder="Nama Anda"
                            class="w-full p-2 border border-orange-500 rounded bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-green-500 focus:outline-none" required>

                        <input type="email" id="email" placeholder="Email Anda"
                            class="w-full p-2 border border-orange-500 rounded bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-green-500 focus:outline-none" required>

                        <textarea rows="3" id="pesan" placeholder="Pesan Anda"
                            class="w-full p-2 border border-orange-500 rounded bg-white/30 text-white placeholder-white focus:ring-2 focus:ring-green-500 focus:outline-none" required></textarea>

                        <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition">
                            Kirim
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <div class="border-t border-white    px-6 py-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-sm text-white">
                <p>&copy; 2025 Nasi Uduk 88 Brebes. Seluruh hak cipta dilindungi.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="hover:text-white transition"><i class='bx bxl-twitter'></i></a>
                    <a href="#" class="hover:text-white transition"><i class='bx bxl-instagram'></i></a>
                    <a href="#" class="hover:text-white transition"><i class='bx bxl-linkedin'></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script src="scripts.js"></script>
</body>

</html>