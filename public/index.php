<?php include __DIR__ . '/../src/db.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/Logo_bulat.png" type="image">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <meta name="google-site-verification" content="62E0emCsIASQYaSwutFJmVcXy-ateYCP3XvdkwjtL6E" />
    <title>Nasi Uduk 88 Brebes Berhias</title>
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
                    <a href="index.php" class="text-yellow-800 font-bold hover:text-orange-600">Beranda</a>
                    <a href="menu.php" class="text-yellow-800 hover:text-orange-600">Menu</a>
                    <a href="#kontak" class="text-yellow-800 hover:text-orange-600">Kontak</a>
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
            <a href="index.php" class="block px-4 py-2 text-yellow-800 font-bold hover:text-orange-600">Beranda</a>
            <a href="menu.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Menu</a>
            <a href="#kontak" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Kontak</a>
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

    <div class="relative h-screen parallax" style="background-image: url('img/nasiudukbanner.png');">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-4">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 ">Nasi Uduk
                    <bold style="color: red;">88</bold> Brebes
                    Berhias
                </h1>
                <p class="mt-4 text-lg md:text-xl max-w-2xl">Kelezatan dalam
                    Setiap Suapan, Gurihnya Menggoda, Nikmatnya
                    Bikin Ketagihan</p>
                <div class="mt-6 flex space-x-4">
                    <a href="#menu"
                        class="flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-full transition duration-300">
                        <i class='bx bx-fork-spoon mr-2 text-xl'></i> Lihat
                        Menu
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="p-16 bg-gray-100" id="about">

        <section
            class="flex flex-col md:flex-row items-center md:items-start justify-between gap-8 px-4 md:px-16 py-12">

            <div class="md:w-1/2">
                <h1 class="text-4xl font-bold text-gray-800 mb-6">Tentang
                    Kami</h1>
                <p class="text-lg text-gray-600 leading-relaxed">
                    ✨ <strong>Nasi Uduk 88 Brebes</strong> ✨
                    <br><br>
                    Selamat datang di Nasi Uduk 88 Brebes! 🌿🍚
                    Nikmati keharuman nasi uduk khas kami, lengkap dengan
                    aneka lauk pilihan seperti ayam goreng krispi,
                    tempe orek manis, dan sambal pedas yang menggugah
                    selera. 🔥😋
                    <br><br>
                </p>
                <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                    Yuk, rasakan kelezatan Nasi Uduk 88 Brebes – karena
                    setiap gigitan menyimpan cerita tentang
                    kehangatan dan kebersamaan! 🍽️✨
                    <br><br>
                </p>
            </div>

            <div class="md:w-1/3 flex justify-center">
                <img src="img/Logo.png" alt="Logo Nasi Uduk 88 Brebes"
                    class="w-2/3 h-auto rounded-full shadow-lg mt-12" />
            </div>

        </section>

    </div>

    <div class="relative h-screen parallax" style="background-image: url('img/ayam.png');" id="menu">
        <section class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center py-14 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto text-center w-full">
                <h2 class="text-3xl font-bold text-orange-600 mb-5">🌟 Menu Favorit Kami 🌟</h2>
                <p class="text-white mb-12">Menu paling laris yang wajib kamu coba!</p>

                <div class="flex space-x-4 overflow-x-auto pb-4 md:grid md:grid-cols-4 md:gap-6 md:space-x-0 md:overflow-visible">
                    <?php
                    $sql_favorite = "SELECT * FROM menus WHERE visibility = 1 ORDER BY id DESC LIMIT 4";
                    $result_favorite = mysqli_query($conn, $sql_favorite);

                    if (mysqli_num_rows($result_favorite) > 0) {
                        while ($row = mysqli_fetch_assoc($result_favorite)) {
                    ?>
                            <div class="bg-white rounded-2xl shadow-lg p-4 flex-shrink-0 w-64 md:w-auto transition hover:scale-105 hover:shadow-xl flex flex-col justify-between">
                                <div>
                                    <img src="<?php echo !empty($row['foto']) ? 'img/' . htmlspecialchars($row['foto']) : 'img/placeholder.png'; ?>" alt="<?php echo htmlspecialchars($row['nama_menu']); ?>" class="w-full h-40 object-cover rounded-xl mb-4">
                                    <h3 class="text-lg font-semibold text-orange-700"><?php echo htmlspecialchars($row['nama_menu']); ?></h3>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                                </div>
                                <div class="mt-4 flex justify-between items-center">
                                    <p class="text-lg font-bold text-yellow-500">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                    <button class="add-to-cart bg-yellow-500 text-white rounded-full p-2 hover:bg-yellow-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2 5m12-5l2 5m-6 0a2 2 0 100 4 2 2 0 000-4z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p class="text-white text-center col-span-4">Menu favorit akan segera hadir!</p>';
                    }
                    ?>
                </div>
                <div class="mt-12 flex justify-center">
                    <a href="menu.php" class="flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-full transition duration-300">
                        <i class='bx bx-fork-spoon mr-2 text-xl'></i> Lihat Semua Menu
                    </a>
                </div>
            </div>
        </section>
    </div>

    <div id="sidebar"
        class="fixed top-0 right-0 h-full w-full sm:w-80 lg:w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-[1100]">

        <div class="flex items-center justify-between p-4 border-b bg-white sticky top-0 z-10">
            <h2 class="text-xl font-semibold">Keranjang</h2>
            <button onclick="closeModal()" class="text-gray-600 hover:text-red-500 text-xl">✖</button>
        </div>

        <div class="overflow-y-auto p-4 h-[calc(100vh-180px)]">
            <div id="cartItems" class="flex flex-col space-y-4">
            </div>
        </div>

        <div class="absolute bottom-0 left-0 w-full bg-white border-t p-4">
            <div class="border border-orange-500 rounded-lg p-4 mb-3">
                <div class="flex justify-between mb-1">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp </span>
                </div>
                <div class="flex justify-between mb-1">
                    <span>Biaya layanan</span>
                    <span id="serviceFee">Rp 2.000 </span>
                </div>
                <div class="flex justify-between font-bold text-orange-500 text-xl mt-2">
                    <span>Total</span>
                    <span id="total">Rp </span>
                </div>
            </div>
            <button id="checkoutBtn"
                class="w-full bg-green-500 text-white py-3 rounded-lg text-lg font-semibold hover:bg-green-600 transition">
                Checkout
            </button>
        </div>
    </div>

    <section class="py-16 bg-orange-50 px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-orange-700 mb-10">Layanan Kami</h2>

            <div class="flex flex-wrap justify-center gap-8">
                <div
                    class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition w-full md:w-1/3 max-w-sm">
                    <img src="img/gofud.png" alt="Delivery" class="w-16 mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-orange-800 mb-2">Layanan Antar</h3>
                    <p>Kami menyediakan layanan antar langsung ke rumah atau kantor Anda dengan pesanan minimum.</p>
                </div>

                <div
                    class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition w-full md:w-1/3 max-w-sm">
                    <img src="img/meja makan.png" alt="Dine In" class="w-16 mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-orange-800 mb-2">Makan di Tempat</h3>
                    <p>Datang langsung ke lokasi kami dan nikmati hidangan dalam suasana hangat dan nyaman.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="w-full bg-gray-100 py-6 sm:py-12 px-4 sm:px-6 lg:px-8 min-h-screen flex items-center justify-center ">
        <div class="max-w-7xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div
                    class="p-8 md:p-12 lg:p-16 bg-gradient-to-br from-yellow-600 to-yellow-800 text-white flex items-center justify-center">
                    <div class="max-w-md w-full">
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold mb-4 text-center lg:text-left">Our
                                Location</h2>
                            <div class="w-16 h-1 bg-yellow-300 rounded-full mb-6 mx-auto lg:mx-0"></div>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-1">
                                        <svg class="w-6 h-6 text-yellow-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-yellow-100">Address</h3>
                                        <p class="mt-1 text-yellow-50 leading-relaxed">
                                            Jl. Abdul Rahman Saleh No.45,
                                            Husen Sastranegara,<br>
                                            Kec. Cicendo, Kota Bandung, Jawa
                                            Barat 40174
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-1">
                                        <svg class="w-6 h-6 text-yellow-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-yellow-100">Contact</h3>
                                        <p class="mt-1 text-yellow-50">+62
                                            819-1047-1665</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center lg:text-left">
                            <div class="flex justify-center lg:justify-start flex-wrap gap-4 mt-8">
                                <a
                                    href="https://www.google.com/maps/dir//Nasi+Uduk+88+Brebes+Berhias+Jl.+Abdul+Rahman+Saleh+No.45+Husen+Sastranegara+Cicendo,+Bandung+City,+West+Java+40174/@-6.9076596,107.5837545,12z/data=!4m5!4m4!1m0!1m2!1m1!1s0x2e68e70398fe3441:0x1b90bad08d1dad60">
                                    <button
                                        class="inline-flex items-center gap-2 px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-yellow-700 bg-white hover:bg-yellow-50 transition duration-150 ease-in-out">
                                        Get Directions
                                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative h-96 lg:h-auto">
                    <iframe class="absolute inset-0 w-full h-full"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63375.38356797214!2d107.55602821953124!3d-6.8952118844848895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e70398fe3441%3A0x1b90bad08d1dad60!2sNasi%20Uduk%2088%20Brebes%20Berhias!5e0!3m2!1sen!2sid!4v1735175898121!5m2!1sen!2sid"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen>
                    </iframe>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gradient-to-b from-red-900 to-orange-800 text-white w-full py-10 mt-10" id="kontak">

        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Tentang Kami</h3>
                    <p class="text-white leading-relaxed">
                        Kami berkomitmen untuk menyajikan makanan
                        berkualitas terbaik dan memberikan pengalaman makan
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
                        <li><span class="font-semibold">Alamat:</span> Jl.
                            Abdul Rahman Saleh No.45, Husen Sastranegara,
                            Kec. Cicendo, Kota Bandung, Jawa Barat
                            40174</li>
                        <li><span class="font-semibold">Telepon:</span> +62
                            819-1047-1665</li>
                        <li><span class="font-semibold">Email:</span>
                            nu88brebes@gmail.com</li>
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
                <p>© 2025 Nasi Uduk 88 Brebes. Seluruh hak cipta
                    dilindungi.</p>
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