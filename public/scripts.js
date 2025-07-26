document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
});


// TABS: Ganti tab menu (FUNGSI BARU YANG SUDAH DIPERBAIKI)
window.switchTab = function (tabId) {
    // 1. Sembunyikan semua panel konten
    const contentPanels = document.querySelectorAll('.tab-content');
    contentPanels.forEach(panel => {
        panel.classList.add('hidden');
    });

    // 2. Tampilkan panel yang dituju
    const targetPanel = document.getElementById(tabId);
    if (targetPanel) {
        targetPanel.classList.remove('hidden');
    }

    // 3. Atur style untuk semua tab (tombol)
    const tabItems = document.querySelectorAll('.tab-item');
    tabItems.forEach(tab => {
        tab.classList.remove('text-green-500', 'border-green-500', 'border-b-2');
        tab.classList.add('text-gray-500', 'border-transparent');
    });
    
    // 4. Atur style untuk tab yang aktif
    const activeTab = document.querySelector(`[onclick="switchTab('${tabId}')"]`);
    if(activeTab) {
        activeTab.classList.add("text-green-500", "border-b-2", "border-green-500");
        activeTab.classList.remove('text-gray-500', 'border-transparent');
    }
};


// Ambil data cart dari localStorage jika ada
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function saveCartToLocalStorage() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function openModal() {
    document.getElementById('sidebar').classList.remove('translate-x-full');
}

function closeModal() {
    document.getElementById('sidebar').classList.add('translate-x-full');
}

function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}

function updateCartUI() {
    const cartItemsContainer = document.getElementById('cartItems');
    cartItemsContainer.innerHTML = '';

    let subtotal = 0;

    cart.forEach((item, index) => {
        subtotal += item.price * item.qty;
        const html = `
            <div class="p-4 bg-white flex items-center w-full border rounded-lg">
                <img src="${item.image}" alt="${item.name}" class="h-20 w-20 mr-5 rounded object-cover">
                <div class="flex-1">
                    <h2 class="font-semibold">${item.name}</h2>
                    <div class="text-gray-500">Harga: <span class="font-medium">Rp ${item.price}</span></div>
                    <div class="flex items-center mt-2">
                        <button onclick="decrementValue(${index})" class="border text-black px-2 py-1 rounded hover:bg-gray-100">-</button>
                        <input type="number" value="${item.qty}" min="1" class="w-12 p-1 text-center border mx-1" onchange="updateQtyFromInput(${index}, this.value)">
                        <button onclick="incrementValue(${index})" class="border text-black px-2 py-1 rounded hover:bg-gray-100">+</button>
                    </div>
                </div>
                <button onclick="removeItem(${index})" class="bg-red-500 text-white py-2 px-2 rounded-lg font-semibold hover:bg-red-600 transition text-xl">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        `;
        cartItemsContainer.innerHTML += html;
    });

    const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
    document.getElementById('cartCount').textContent = totalItems;
    document.getElementById('cartCountMobile').textContent = totalItems;

    const serviceFee = 2000;
    document.getElementById('subtotal').textContent = formatRupiah(subtotal);
    document.getElementById('total').textContent = formatRupiah(subtotal + serviceFee);

    saveCartToLocalStorage();
}


function addToCart(productCard) {
    // Cek jika produk out of stock dari class
    if (productCard.classList.contains('cursor-not-allowed')) {
        showToast('Stok menu ini telah habis!');
        return;
    }

    const name = productCard.querySelector('h2, h3').textContent.trim();
    const priceElement = productCard.querySelector('.text-yellow-500');
    const priceText = priceElement ? priceElement.textContent.replace('Rp', '').replace(/\./g, '').replace(',', '').trim() : "0";
    const image = productCard.querySelector('img').getAttribute('src');
    const price = parseInt(priceText);

    const existing = cart.find(item => item.name === name);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ name, price, image, qty: 1 });
    }

    updateCartUI();
    showToast(`${name} berhasil ditambahkan ke keranjang!`);
}

function incrementValue(index) {
    cart[index].qty++;
    updateCartUI();
}

function decrementValue(index) {
    if (cart[index].qty > 1) {
        cart[index].qty--;
    } else {
        // Jika kuantitas 1 dan dikurangi, hapus item dari keranjang
        removeItem(index);
    }
    updateCartUI();
}

function updateQtyFromInput(index, newQty) {
    const qty = parseInt(newQty);
    if (qty > 0) {
        cart[index].qty = qty;
    } else {
        // Jika input 0 atau kurang, hapus item
        removeItem(index);
    }
    updateCartUI();
}


function removeItem(index) {
    cart.splice(index, 1);
    updateCartUI();
}

function showToast(message) {
    let toast = document.createElement("div");
    toast.className = "fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] animate-bounce";
    
    // Ganti warna jika pesan error
    if (message.toLowerCase().includes('habis') || message.toLowerCase().includes('kosong') || message.toLowerCase().includes('gagal') || message.toLowerCase().includes('cukup')) {
        toast.classList.remove('bg-green-500');
        toast.classList.add('bg-red-500');
    }

    toast.innerText = message;

    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add("opacity-0", "transition-opacity", "duration-500");
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}


document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.add-to-cart');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.product-card');
            addToCart(card);
        });
    });

    // Inisialisasi keranjang dari local storage
    updateCartUI();

    const checkoutBtn = document.querySelector('.bg-green-500');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if (cart.length === 0) {
                showToast("Keranjang masih kosong!");
                return;
            }

            // Kirim data ke server untuk mengurangi stok
            fetch('api/process_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cart)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Jika sukses, arahkan ke WhatsApp
                    window.open(data.whatsapp_url, '_blank');
                    
                    // Kosongkan keranjang setelah berhasil
                    cart = [];
                    saveCartToLocalStorage();
                    updateCartUI();
                    closeModal(); // Tutup sidebar keranjang

                } else {
                    // Jika gagal, tampilkan pesan error
                    showToast(data.message || 'Gagal memproses pesanan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
    }

    document.getElementById("contactForm")?.addEventListener("submit", function (e) {
        e.preventDefault(); // Mencegah submit form default

        const nama = document.getElementById("nama").value.trim();
        const email = document.getElementById("email").value.trim();
        const pesan = document.getElementById("pesan").value.trim();

        if (!nama || !email || !pesan) {
            alert("Semua field harus diisi!");
            return;
        }

        // Format pesan email
        const subject = encodeURIComponent("Pesan dari " + nama);
        const body = encodeURIComponent(
            `Nama: ${nama}\nEmail: ${email}\n\nPesan:\n${pesan}`
        );

        // Ganti alamat email tujuan di bawah ini
        const tujuan = "yonaldiernandaputro@gmail.com";

        // Buka Gmail via mailto
        window.location.href = `mailto:${tujuan}?subject=${subject}&body=${body}`;
    });
});