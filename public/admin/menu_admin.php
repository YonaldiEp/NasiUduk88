<?php
// Selalu mulai session di awal jika Anda berencana menggunakannya
session_start();

include __DIR__ . '/../../src/db.php';
$message = '';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

// Menangkap status dari URL setelah redirect untuk menampilkan notifikasi
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'add_success') {
        $message = 'Menu berhasil ditambah!';
    }
    if ($_GET['status'] == 'update_success') {
        $message = 'Menu berhasil diperbarui!';
    }
    if ($_GET['status'] == 'delete_success') {
        $message = 'Menu berhasil dihapus!';
    }
}


// Gunakan prepared statements untuk mencegah SQL injection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn->set_charset("utf8mb4");

// Ambil semua kategori untuk dropdown
$categories_result = $conn->query("SELECT * FROM categories ORDER BY nama_kategori ASC");

// Proses tambah menu
if (isset($_POST['add_menu'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $visibility = intval($_POST['visibility']);

    $photo_name_final = '';
    $upload_error = false;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $tmp = $_FILES['photo']['tmp_name'];
        $original_name = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            $photo_name_final = 'menu_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $destination = __DIR__ . '/../img/' . $photo_name_final;

            if (!move_uploaded_file($tmp, $destination)) {
                $message = 'Gagal memindahkan file yang diunggah!';
                $upload_error = true;
            }
        } else {
            $message = 'Format gambar tidak valid!';
            $upload_error = true;
        }
    }

    if (!$upload_error) {
        if ($name && $description && $category_id > 0 && $price > 0) {
            $stmt = $conn->prepare("INSERT INTO menus (nama_menu, deskripsi, category_id, harga, stock, visibility, foto, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiiissi", $name, $description, $category_id, $price, $stock, $visibility, $photo_name_final, $current_user_id);

            if ($stmt->execute()) {
                header("Location: menu_admin.php?status=add_success");
                exit();
            } else {
                $message = 'Gagal menambah menu ke database! ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Proses hapus menu
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM menus WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: menu_admin.php?status=delete_success");
        exit();
    } else {
        $message = 'Gagal menghapus menu!';
    }
    $stmt->close();
}

// Proses tampil data untuk edit
$edit_mode = false;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM menus WHERE id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_data = $result->fetch_assoc();
    $stmt->close();
}

// Proses update menu
if (isset($_POST['update_menu'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $visibility = intval($_POST['visibility']);

    $sql = "UPDATE menus SET nama_menu=?, deskripsi=?, category_id=?, harga=?, stock=?, visibility=?, user_id=? WHERE id=?";
    $types = "ssiiisii";
    $params = [$name, $description, $category_id, $price, $stock, $visibility, $current_user_id, $id];

    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] == 0) {
        $tmp = $_FILES['photo']['tmp_name'];
        $original_name = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            $new_photo_name = 'menu_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $destination = __DIR__ . '/../img/' . $new_photo_name;

            if (move_uploaded_file($tmp, $destination)) {
                $sql = "UPDATE menus SET nama_menu=?, deskripsi=?, category_id=?, harga=?, stock=?, visibility=?, foto=?, user_id=? WHERE id=?";
                $types = "ssiiisisi";
                $params = [$name, $description, $category_id, $price, $stock, $visibility, $new_photo_name, $current_user_id, $id];
            } else {
                $message = 'Gagal memindahkan file baru!';
            }
        } else {
            $message = 'Format gambar baru tidak valid! Hanya data teks yang diupdate.';
        }
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header("Location: menu_admin.php?status=update_success");
        exit();
    } else {
        $message = 'Gagal update menu! ' . $stmt->error;
    }
    $stmt->close();
}

// Judul Halaman
$page_title = 'Kelola Menu';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin Nasi Uduk 88</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <aside id="sidebar" class="sidebar w-64 bg-blue-900 text-white fixed top-0 left-0 h-full flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-20">
            <div class="p-5 text-lg font-bold border-b border-blue-700">Admin Nasi Uduk 88</div>
            <nav class="mt-4 flex-grow space-y-1">
                <a href="admin.php" class="flex items-center py-2 px-4 hover:bg-blue-700"><i class='bx bx-home-alt text-2xl mr-3'></i><span class="text-base">Beranda</span></a>
                <a href="menu_admin.php" class="flex items-center py-2 px-4 bg-blue-700 rounded"><i class='bx bx-food-menu text-2xl mr-3'></i><span class="text-base">Menu</span></a>
                <a href="profile.php" class="flex items-center py-2 px-4 hover:bg-blue-700"><i class='bx bx-user text-2xl mr-3'></i><span class="text-base">Pengguna</span></a>
            </nav>
            <a href="logout.php" class="flex items-center py-2 px-4 hover:bg-blue-700 mt-auto"><i class='bx bx-log-out text-2xl mr-3'></i><span class="text-base">Logout</span></a>
        </aside>
        <div id="main-content" class="flex-1 md:ml-64 flex flex-col transition-all duration-300 ease-in-out">
            <header class="bg-white shadow fixed top-0 left-0 right-0 z-10 flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                    <button id="menu-toggle" class="md:hidden text-gray-600 hover:text-gray-800">
                        <i class='bx bx-menu text-3xl'></i>
                    </button>
                    <div class="hidden md:block text-gray-700 text-lg font-semibold ml-4"><?php echo $page_title; ?></div>
                </div>
                <div class="flex-grow flex justify-center">
                    <form method="get" class="w-full max-w-md flex">
                        <input type="text" name="search" placeholder="Search.." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <button type="submit" class="bg-blue-600 text-white px-4 rounded-r-lg hover:bg-blue-700"> <i class='bx bx-search'></i> </button>
                    </form>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="relative text-gray-500 hover:text-blue-600 text-3xl">
                        <i class='bx bx-bell'></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                    </button>
                    <a href="profile.php" class="text-gray-500 hover:text-blue-600 text-3xl">
                        <i class='bx bx-user'></i>
                    </a>
                </div>
            </header>
            <main class="flex-1 mt-20 p-6 overflow-y-auto">
                <?php if ($message): ?>
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <div class="bg-white p-6 rounded-lg shadow mb-8">
                    <h2 class="text-lg font-semibold mb-4"><?php echo $edit_mode ? 'Edit Menu' : 'Tambah Menu Baru'; ?></h2>
                    <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <?php if ($edit_mode): ?>
                            <div>
                                <label class="block mb-1">User Id</label>
                                <input type="text" name="id" required placeholder="Masukkan Id" value="<?php echo $edit_mode ? htmlspecialchars($edit_data['id']) : ''; ?>" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500">
                            </div>
                        <?php endif; ?>

                        <div class="md:col-span-2">
                            <label class="block mb-1">Upload Foto Menu</label>
                            <input type="file" name="photo" class="w-full border px-3 py-2 rounded">
                            <?php if ($edit_mode && !empty($edit_data['foto'])) {
                                echo '<img src="../img/' . htmlspecialchars($edit_data['foto']) . '" width="60" class="mt-2 rounded">';
                            } ?>
                        </div>
                        <div>
                            <label class="block mb-1">Nama Menu</label>
                            <input type="text" name="name" required placeholder="Masukkan nama menu" value="<?php echo $edit_mode ? htmlspecialchars($edit_data['nama_menu']) : ''; ?>" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block mb-1">Deskripsi</label>
                            <input type="text" name="description" required placeholder="Deskripsi menu" value="<?php echo $edit_mode ? htmlspecialchars($edit_data['deskripsi']) : ''; ?>" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block mb-1">Harga</label>
                            <input type="number" name="price" required placeholder="Harga" value="<?php echo $edit_mode ? $edit_data['harga'] : ''; ?>" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block mb-1">Stok</label>
                            <input type="number" name="stock" required placeholder="Jumlah Stok" value="<?php echo $edit_mode ? $edit_data['stock'] : '0'; ?>" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block mb-1">Kategori</label>
                            <select name="category_id" required class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                <?php mysqli_data_seek($categories_result, 0); ?>
                                <?php while ($category = $categories_result->fetch_assoc()): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php if ($edit_mode && $edit_data['category_id'] == $category['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($category['nama_kategori']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1">Pengaturan Visibilitas</label>
                            <select name="visibility" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-500">
                                <option value="0" <?php if ($edit_mode && $edit_data['visibility'] == 0) echo 'selected'; ?>>Menu Biasa</option>
                                <option value="1" <?php if ($edit_mode && $edit_data['visibility'] == 1) echo 'selected'; ?>>Best Seller</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 flex gap-2">
                            <button type="submit" name="<?php echo $edit_mode ? 'update_menu' : 'add_menu'; ?>" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700"><?php echo $edit_mode ? 'Update Menu' : 'Simpan Menu'; ?></button>
                            <button type="reset" class="bg-gray-400 text-white px-5 py-2 rounded hover:bg-gray-500">Reset Form</button>
                            <?php if ($edit_mode) {
                                echo '<a href="menu_admin.php" class="bg-gray-400 text-white px-5 py-2 rounded hover:bg-gray-500">Batal Edit</a>';
                            } ?>
                        </div>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow mb-8">
                    <h3 class="text-lg font-semibold mb-4">Daftar Menu Best Seller</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2">Id Menu</th>
                                    <th class="border border-gray-300 px-4 py-2">Nama Menu</th>
                                    <th class="border border-gray-300 px-4 py-2">Harga</th>
                                    <th class="border border-gray-300 px-4 py-2">Stok</th>
                                    <th class="border border-gray-300 px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_bestseller = "SELECT * FROM menus WHERE visibility = 1 ORDER BY id DESC";
                                $result_bestseller = mysqli_query($conn, $sql_bestseller);

                                if (mysqli_num_rows($result_bestseller) > 0) {
                                    while ($row = mysqli_fetch_assoc($result_bestseller)) {
                                        echo '<tr>';
                                        echo '<td class="border border-gray-300 px-4 py-2">' . htmlspecialchars($row['id']) . '</td>';
                                        echo '<td class="border border-gray-300 px-4 py-2">' . htmlspecialchars($row['nama_menu']) . '</td>';
                                        echo '<td class="border border-gray-300 px-4 py-2">Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>';
                                        echo '<td class="border border-gray-300 px-4 py-2">' . htmlspecialchars($row['stock']) . '</td>';
                                        echo '<td class="border border-gray-300 px-4 py-2 text-center">';
                                        echo '<a href="?edit=' . $row['id'] . '" class="bg-blue-500 text-white px-3 py-1 rounded mr-2 mb-2 inline-block">Edit</a>';
                                        echo '<a href="?delete=' . $row['id'] . '" onclick="return confirm(\'Yakin hapus?\')" class="bg-red-500 text-white px-3 py-1 rounded inline-block">Hapus</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center py-4">Tidak ada menu best seller.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow mb-8">
                    <h3 class="text-lg font-semibold mb-4">Daftar Menu Biasa</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2">Nama Menu</th>
                                    <th class="border border-gray-300 px-4 py-2">Harga</th>
                                    <th class="border border-gray-300 px-4 py-2">Stok</th>
                                    <th class="border border-gray-300 px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_regular = "SELECT * FROM menus WHERE visibility = 0 ORDER BY id DESC";
                                $result_regular = mysqli_query($conn, $sql_regular);

                                if (mysqli_num_rows($result_regular) > 0) {
                                    while ($row = mysqli_fetch_assoc($result_regular)) {
                                        echo '<tr>';
                                        echo '<td class="border border-gray-300 px-4 py-2">' . htmlspecialchars($row['nama_menu']) . '</td>';
                                        echo '<td class="border border-gray-300 px-4 py-2">Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>';
                                        echo '<td class="border border-gray-300 px-4 py-2">' . htmlspecialchars($row['stock']) . '</td>';
                                        echo '<td class="border border-gray-300 px-4 py-2 text-center">';
                                        echo '<a href="?edit=' . $row['id'] . '" class="bg-blue-500 text-white px-3 py-1 rounded mr-2 mb-2 inline-block">Edit</a>';
                                        echo '<a href="?delete=' . $row['id'] . '" onclick="return confirm(\'Yakin hapus?\')" class="bg-red-500 text-white px-3 py-1 rounded inline-block">Hapus</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center py-4">Tidak ada menu biasa.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../scripts.js"></script>
</body>

</html>