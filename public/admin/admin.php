<?php
session_start();
include __DIR__ . '/../../src/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth.php");
    exit();
}

if (isset($_GET['delete_user'])) {
    $user_id_to_delete = intval($_GET['delete_user']);
    if ($user_id_to_delete != $_SESSION['user_id']) {
        $stmt_delete = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt_delete->bind_param("i", $user_id_to_delete);
        if ($stmt_delete->execute()) {
            header("Location: admin.php?delete_success=1");
            exit();
        }
        $stmt_delete->close();
    }
}

// --- Mengambil Data Statistik ---
$result_users = $conn->query("SELECT COUNT(id) as total_users FROM users");
$total_users = $result_users->fetch_assoc()['total_users'];

$result_menus = $conn->query("SELECT COUNT(id) as total_menus FROM menus");
$total_menus = $result_menus->fetch_assoc()['total_menus'];

$result_last_user = $conn->query("SELECT full_name FROM users ORDER BY id DESC LIMIT 1");
$last_activity_user = "Belum ada";
if ($result_last_user->num_rows > 0) {
    $last_activity_user = $result_last_user->fetch_assoc()['full_name'];
}

$all_users_result = $conn->query("SELECT id, full_name, email, role, username FROM users ORDER BY id DESC");

// Judul Halaman
$page_title = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="flex h-screen">
    <aside class="sidebar w-64 bg-blue-900 text-white fixed top-0 left-0 h-full flex flex-col">
        <div class="p-5 text-lg font-bold border-b border-blue-700">Admin Nasi Uduk 88</div>
        <nav class="mt-4 flex-grow space-y-1">
            <a href="admin.php" class="flex items-center py-2 px-4 bg-blue-700 rounded"><i class='bx bx-home-alt text-2xl mr-3'></i><span class="text-base">Beranda</span></a>
            <a href="menu_admin.php" class="flex items-center py-2 px-4 hover:bg-blue-700"><i class='bx bx-food-menu text-2xl mr-3'></i><span class="text-base">Menu</span></a>
            <a href="profile.php" class="flex items-center py-2 px-4 hover:bg-blue-700"><i class='bx bx-user text-2xl mr-3'></i><span class="text-base">Pengguna</span></a>
        </nav>
        <a href="logout.php" class="flex items-center py-2 px-4 hover:bg-blue-700 mt-auto"><i class='bx bx-log-out text-2xl mr-3'></i><span class="text-base">Logout</span></a>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-600">Total Pengguna</h4>
                    <p class="text-2xl font-bold"><?php echo $total_users; ?></p>
                </div>
                <div class="bg-white shadow p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-600">Jumlah Menu</h4>
                    <p class="text-2xl font-bold"><?php echo $total_menus; ?></p>
                </div>
                <div class="bg-white shadow p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-600">Pengguna Terbaru</h4>
                    <p class="text-xl font-bold truncate" title="<?php echo htmlspecialchars($last_activity_user); ?>"><?php echo htmlspecialchars($last_activity_user); ?></p>
                </div>
            </div>

            <div class="bg-white shadow p-6 rounded-lg mb-6">
                <h4 class="text-lg font-semibold mb-4">Manajemen Pengguna</h4>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b-2 p-3 text-left">Nama</th>
                                <th class="border-b-2 p-3 text-left">Email</th>
                                <th class="border-b-2 p-3 text-left">Username</th>
                                <th class="border-b-2 p-3 text-left">Role</th>
                                <th class="border-b-2 p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($all_users_result->num_rows > 0): ?>
                                <?php while($user = $all_users_result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border-b p-3"><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td class="border-b p-3"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="border-b p-3"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="border-b p-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $user['role'] === 'admin' ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                        </span>
                                    </td>
                                    <td class="border-b p-3 text-center">
                                        <a href="profile.php?edit_user=<?php echo $user['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                        <?php if ($_SESSION['user_id'] != $user['id']): ?>
                                            <a href="?delete_user=<?php echo $user['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')" class="text-red-600 hover:text-red-800">Hapus</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center p-4 text-gray-500">Tidak ada data pengguna.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
  </div>
</body>
</html>