<?php
session_start();
// PERBAIKAN: Menggunakan path absolut untuk menemukan db.php
include __DIR__ . '/../src/db.php';

$error_message = '';
$success_message = '';

// Proses Sign Up
if (isset($_POST['signup'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        $error_message = 'Semua kolom wajib diisi!';
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt_check->bind_param("ss", $email, $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_message = 'Email atau Username sudah terdaftar!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO users (full_name, email, username, password, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt_insert->bind_param("ssss", $full_name, $email, $username, $hashed_password);
            
            if ($stmt_insert->execute()) {
                $success_message = 'Pendaftaran berhasil! Silakan Sign In.';
            } else {
                $error_message = 'Gagal mendaftar. Silakan coba lagi.';
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}

// Proses Sign In
if (isset($_POST['signin'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember_me = isset($_POST['remember_me']);

    if (empty($email) || empty($password)) {
        $error_message = 'Email dan password wajib diisi!';
    } else {
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($remember_me) {
                    setcookie('remember_email', $email, time() + (86400 * 30), "/");
                } else {
                    if(isset($_COOKIE['remember_email'])) {
                        setcookie('remember_email', '', time() - 3600, "/");
                    }
                }

                if ($user['role'] === 'admin') {
                    header("Location: admin/admin.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $error_message = 'Password salah!';
            }
        } else {
            $error_message = 'Email tidak ditemukan!';
        }
        $stmt->close();
    }
}

$remembered_email = isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/Logo_bulat.png" type="image">
    <link rel="stylesheet" href="css/style.css"> 
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login & Register</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="relative w-full md:w-1/2 p-8">
            <a href="index.php" class="absolute top-4 left-4 text-orange-700 hover:text-orange-900">&larr; Kembali ke Beranda</a>
            <div class="flex justify-center space-x-4 mb-6 mt-8">
                <button id="btn-signin" class="px-4 py-2 tab-active focus:outline-none">Sign In</button>
                <button id="btn-signup" class="px-4 py-2 tab-inactive focus:outline-none">Sign Up</button>
            </div>
            
            <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <?php endif; ?>
            <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <?php endif; ?>

            <form id="form-signin" method="POST" action="auth.php" class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-700">Welcome Back!</h2>
                <div>
                    <label class="block text-gray-700">Email address</label>
                    <input type="email" name="email" placeholder="you@example.com" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-orange-700" value="<?php echo $remembered_email; ?>"/>
                </div>
                <div>
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-orange-700" />
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900"> Remember me </label>
                    </div>
                </div>
                <button type="submit" name="signin" class="w-full px-4 py-2 text-white bg-orange-700 rounded-md hover:bg-orange-800 focus:outline-none">Sign In</button>
            </form>

            <form id="form-signup" method="POST" action="auth.php" class="space-y-4 form-hidden">
                <h2 class="text-2xl font-semibold text-gray-700">Create Account</h2>
                <div>
                    <label class="block text-gray-700">Full Name</label>
                    <input type="text" name="full_name" placeholder="Nama Lengkap Anda" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-orange-700" />
                </div>
                <div>
                    <label class="block text-gray-700">Email address</label>
                    <input type="email" name="email" placeholder="you@example.com" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-orange-700" />
                </div>
                <div>
                    <label class="block text-gray-700">Username</label>
                    <input type="text" name="username" placeholder="Buat username unik" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-orange-700" />
                </div>
                <div>
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-orange-700" />
                </div>
                <button type="submit" name="signup" class="w-full px-4 py-2 text-white bg-orange-700 rounded-md hover:bg-orange-800 focus:outline-none">Sign Up</button>
            </form>
        </div>
        <div class="hidden md:block md:w-1/2 bg-orange-700">
            <img src="img/ayam.png" alt="Illustration" class="object-cover w-full h-full" />
        </div>
    </div>
    <script src="js/scripts.js"></script> 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rememberMeCheckbox = document.getElementById('remember_me');
            const emailInput = document.querySelector('#form-signin input[name="email"]');
            const passwordInput = document.querySelector('#form-signin input[name="password"]');

            if (emailInput.value) {
                rememberMeCheckbox.checked = true;
            }

            const form = document.getElementById('form-signin');
            form.addEventListener('submit', function() {
                if (!rememberMeCheckbox.checked) {
                    emailInput.value = '';
                    passwordInput.value = '';
                }
            });
        });
    </script>
</body>
</html>