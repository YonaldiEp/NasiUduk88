<?php
session_start();
include __DIR__ . '/../../src/db.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth.php");
    exit();
}

$message = '';
$is_editing_other_user = false;
$user_id_to_edit = $_SESSION['user_id']; // Default: admin mengedit profilnya sendiri

// Cek apakah admin sedang mengedit pengguna lain dari parameter URL
if (isset($_GET['edit_user']) && is_numeric($_GET['edit_user'])) {
    $is_editing_other_user = true;
    $user_id_to_edit = intval($_GET['edit_user']);
}

// Proses update profil jika form disubmit
if (isset($_POST['update_profile'])) {
    $user_id_from_form = intval($_POST['user_id']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $new_password = trim($_POST['new_password']);
    $role = isset($_POST['role']) ? $_POST['role'] : null;

    $new_photo_name = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $photo_name = $_FILES['profile_photo']['name'];
        $tmp = $_FILES['profile_photo']['tmp_name'];
        $ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $new_photo_name = 'profile_' . $user_id_from_form . '_' . time() . '.' . $ext;
            move_uploaded_file($tmp, "../img/" . $new_photo_name);
        } else {
            $message = 'Format foto tidak valid! Hanya data teks yang diperbarui.';
        }
    }

    $sql_parts = ["full_name=?", "email=?", "username=?", "phone=?"];
    $params = [$full_name, $email, $username, $phone];
    $types = "ssss";

    if ($new_photo_name) {
        $sql_parts[] = "profile_photo=?";
        $params[] = $new_photo_name;
        $types .= "s";
    }
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_parts[] = "password=?";
        $params[] = $hashed_password;
        $types .= "s";
    }
    if ($is_editing_other_user && !empty($role) && $user_id_from_form != $_SESSION['user_id']) {
        $sql_parts[] = "role=?";
        $params[] = $role;
        $types .= "s";
    }

    $sql = "UPDATE users SET " . implode(", ", $sql_parts) . " WHERE id=?";
    $params[] = $user_id_from_form;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $message = 'Profil berhasil diperbarui!';
    } else {
        $message = 'Gagal memperbarui profil: ' . $stmt->error;
    }
    $stmt->close();
}

// Ambil data terbaru pengguna
$stmt_fetch = $conn->prepare("SELECT id, full_name, email, username, phone, profile_photo, role FROM users WHERE id = ?");
$stmt_fetch->bind_param("i", $user_id_to_edit);
$stmt_fetch->execute();
$result = $stmt_fetch->get_result();

if ($result->num_rows === 0) {
    die("Pengguna tidak ditemukan.");
}
$user_data = $result->fetch_assoc();
$stmt_fetch->close();

// Judul Halaman
$page_title = $is_editing_other_user ? 'Edit Profil Pengguna' : 'Profil Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex h-screen">
        <aside class="sidebar w-64 bg-blue-900 text-white fixed top-0 left-0 h-full flex flex-col">
            <div class="p-5 text-lg font-bold border-b border-blue-700">Admin Nasi Uduk 88</div>
            <nav class="mt-4 flex-grow space-y-1">
                <a href="admin.php" class="flex items-center py-2 px-4 hover:bg-blue-700"><i class='bx bx-home-alt text-2xl mr-3'></i><span>Beranda</span></a>
                <a href="menu_admin.php" class="flex items-center py-2 px-4 hover:bg-blue-700"><i class='bx bx-food-menu text-2xl mr-3'></i><span>Menu</span></a>
                <a href="profile.php" class="flex items-center py-2 px-4 <?php echo !$is_editing_other_user ? 'bg-blue-700 rounded' : 'hover:bg-blue-700'; ?>"><i class='bx bx-user text-2xl mr-3'></i><span>Pengguna</span></a>
            </nav>
            <a href="logout.php" class="flex items-center py-2 px-4 hover:bg-blue-700 mt-auto"><i class='bx bx-log-out text-2xl mr-3'></i><span>Logout</span></a>
        </aside>

        <div class="flex-1 ml-64 flex flex-col">
            <header class="bg-white shadow fixed top-0 left-64 right-0 z-10 flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                    <i class='bx bx-menu text-3xl mr-4 text-gray-600'></i>
                    <div class="text-gray-700 text-lg font-semibold"><?php echo $page_title; ?></div>
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
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold mb-6"><?php echo $page_title; ?></h2>

                    <?php if ($message) : ?>
                        <div class="mb-4 p-3 rounded <?php echo strpos($message, 'Gagal') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                            <div class="flex items-center">
                                <img src="<?php echo !empty($user_data['profile_photo']) ? '../img/' . htmlspecialchars($user_data['profile_photo']) : 'https://via.placeholder.com/100'; ?>" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover mr-4 border">
                                <input type="file" name="profile_photo" class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="full_name" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                            <input type="text" name="phone" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>">
                        </div>

                        <?php if ($is_editing_other_user && $user_data['id'] != $_SESSION['user_id']): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <select name="role" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                    <option value="user" <?php if ($user_data['role'] == 'user') echo 'selected'; ?>>User</option>
                                    <option value="admin" <?php if ($user_data['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="new_password" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Kosongkan jika tidak ingin diubah">
                        </div>

                        <div class="mt-6">
                            <button type="submit" name="update_profile" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 focus:outline-none">
                                Simpan Perubahan
                            </button>
                            <?php if ($is_editing_other_user): ?>
                                <a href="admin.php" class="ml-4 text-gray-600 hover:text-gray-800">Kembali ke Dashboard</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>