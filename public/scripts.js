document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
});



// TABS: Ganti tab menu
window.switchTab = function (tabId, event) {
    const sections = ['minuman', 'makanan', 'paket'];

    sections.forEach(id => {
        const section = document.getElementById(id);
        if (section) {
            section.classList.add('hidden');
        }
    });

    const targetSection = document.getElementById(tabId);
    if (targetSection) {
        targetSection.classList.remove('hidden');
    }

    document.querySelectorAll('.tab-item').forEach(el => {
        el.classList.remove('text-green-500', 'border-green-500', 'border-b-2');
        el.classList.add('text-gray-500', 'border-transparent');
    });

    if (event?.target) {
        event.target.classList.add("text-green-500", "border-b-2", "border-green-500");
    }
};
// tab-control.js

document.addEventListener('DOMContentLoaded', () => {
    const tabItems = document.querySelectorAll('.tab-item');
    const sections = ['minuman', 'makanan', 'paket'];

    tabItems.forEach(tab => {
        tab.addEventListener('click', () => {
            // Hapus aktif dari semua tab
            tabItems.forEach(item => {
                item.classList.remove('text-green-500', 'border-b-2', 'border-green-500');
                item.classList.add('text-gray-500');
            });

            // Aktifkan tab yang diklik
            tab.classList.remove('text-gray-500');
            tab.classList.add('text-green-500', 'border-b-2', 'border-green-500');

            // Sembunyikan semua section
            sections.forEach(id => {
                const section = document.getElementById(id);
                if (section) section.classList.add('hidden');
            });

            // Tampilkan section sesuai tab
            const targetId = tab.getAttribute('onclick').match(/'([^']+)'/)[1];
            const targetSection = document.getElementById(targetId);
            if (targetSection) targetSection.classList.remove('hidden');
        });
    });
});

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
    const cartContainer = document.querySelector('#sidebar .flex.flex-col');
    cartContainer.innerHTML = '';

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
                        <button onclick="decrementValue(this)" class="border text-black px-2 py-1 rounded hover:bg-gray-100">-</button>
                        <input type="number" value="${item.qty}" min="1" class="w-12 p-1 text-center border mx-1">
                        <button onclick="incrementValue(this)" class="border text-black px-2 py-1 rounded hover:bg-gray-100">+</button>
                    </div>
                </div>
                <button onclick="removeItem(${index})" class="bg-red-500 text-white py-2 px-2 rounded-lg font-semibold hover:bg-red-600 transition text-xl">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        `;
        cartContainer.innerHTML += html;
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

function incrementValue(btn) {
    const input = btn.previousElementSibling;
    input.value = parseInt(input.value) + 1;
    updateQtyFromDOM();
}

function decrementValue(btn) {
    const input = btn.nextElementSibling;
    const val = parseInt(input.value);
    if (val > 1) {
        input.value = val - 1;
        updateQtyFromDOM();
    }
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCartUI();
}

function updateQtyFromDOM() {
    const cards = document.querySelectorAll('#sidebar .flex.flex-col > div');
    cards.forEach((card, i) => {
        const input = card.querySelector('input[type="number"]');
        cart[i].qty = parseInt(input.value);
    });
    saveCartToLocalStorage();
    updateCartUI();
}

function showToast(message) {
    let toast = document.createElement("div");
    toast.className = "fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] animate-bounce";
    toast.innerText = message;

    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add("opacity-0");
        setTimeout(() => toast.remove(), 500);
    }, 2000);
}

document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.add-to-cart');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.bg-white');
            addToCart(card);
        });
    });

    const storedCart = localStorage.getItem('cart');
    if (storedCart) {
        cart = JSON.parse(storedCart);
        updateCartUI();
    }

    const checkoutBtn = document.querySelector('.bg-green-500');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if (cart.length === 0) {
                showToast("Keranjang masih kosong!");
                return;
            }

            let message = `Halo, saya ingin memesan:\n`;
            cart.forEach(item => {
                message += `- ${item.name} x${item.qty} = Rp ${item.price * item.qty}\n`;
            });

            const subtotal = cart.reduce((total, item) => total + item.price * item.qty, 0);
            const total = subtotal + 2000;

            message += `\nSubtotal: Rp ${subtotal.toLocaleString('id-ID')}`;
            message += `\nBiaya layanan: Rp 2.000`;
            message += `\nTotal: Rp ${total.toLocaleString('id-ID')}`;

            const encodedMessage = encodeURIComponent(message);
            const whatsappURL = `https://wa.me/6281252012576?text=${encodedMessage}`;
            window.open(whatsappURL, '_blank');
        });
    }
});