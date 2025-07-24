<?php
session_start();
include __DIR__ . '/../../src/db.php';

// Mengatur header untuk respons JSON
header('Content-Type: application/json');

// Mengambil data JSON yang dikirim dari frontend
$json_data = file_get_contents('php://input');
$cart_items = json_decode($json_data, true);

if (empty($cart_items)) {
    echo json_encode(['success' => false, 'message' => 'Keranjang kosong.']);
    exit;
}

// Mulai transaksi database
$conn->begin_transaction();

try {
    foreach ($cart_items as $item) {
        $nama_menu = $item['name'];
        $qty = (int)$item['qty'];

        // Kurangi stok di database
        $stmt = $conn->prepare("UPDATE menus SET stock = stock - ? WHERE nama_menu = ? AND stock >= ?");
        $stmt->bind_param("isi", $qty, $nama_menu, $qty);
        $stmt->execute();

        // Periksa apakah pembaruan berhasil. Jika tidak ada baris yang terpengaruh, berarti stok tidak cukup.
        if ($stmt->affected_rows === 0) {
            throw new Exception("Stok untuk " . htmlspecialchars($nama_menu) . " tidak mencukupi.");
        }
    }

    // Jika semua berhasil, commit transaksi
    $conn->commit();

    // Buat pesan WhatsApp setelah stok berhasil dikurangi
    $phoneNumber = "6281312844675"; // Ganti dengan nomor Anda
    $message = "Halo, saya ingin memesan:\n\n";
    $subtotal = 0;

    foreach ($cart_items as $item) {
        $item_total = $item['price'] * $item['qty'];
        $subtotal += $item_total;
        $message .= "- " . htmlspecialchars($item['name']) . " x" . $item['qty'] . " = Rp " . number_format($item_total, 0, ',', '.') . "\n";
    }

    $serviceFee = 2000;
    $total = $subtotal + $serviceFee;

    $message .= "\nSubtotal: Rp " . number_format($subtotal, 0, ',', '.');
    $message .= "\nBiaya layanan: Rp " . number_format($serviceFee, 0, ',', '.');
    $message .= "\n\nTotal: Rp " . number_format($total, 0, ',', '.');

    $encodedMessage = urlencode($message);
    $whatsappURL = "https://wa.me/{$phoneNumber}?text={$encodedMessage}";

    // Kirim respons sukses beserta URL WhatsApp
    echo json_encode(['success' => true, 'whatsapp_url' => $whatsappURL]);

} catch (Exception $e) {
    // Jika ada kesalahan, batalkan semua perubahan (rollback)
    $conn->rollback();
    // Kirim respons error
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>