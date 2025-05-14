import './bootstrap';
// resources/js/app.js
document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            sessionStorage.setItem('theme', newTheme);

            // Send to server to store in session
            fetch('/set-theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ theme: newTheme })
            });
        });
    }

    // Apply saved theme
    const savedTheme = sessionStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
});

// Check for low stock and high revenue notifications every 5 minutes
setInterval(() => {
    fetch('/check-notifications')
        .then(response => response.json())
        .then(data => {
            if (data.hasNewNotifications) {
                // Show notification badge or refresh the page
                const notificationBadge = document.querySelector('.nav-link .badge');
                if (notificationBadge) {
                    const currentCount = parseInt(notificationBadge.textContent) || 0;
                    notificationBadge.textContent = currentCount + 1;
                }
            }
        });
}, 300000); // 5 minutes


// resources/js/app.js
document.addEventListener('DOMContentLoaded', function() {
    // Validasi form pembelian
    const purchaseForms = document.querySelectorAll('form[action*="/purchase"]');

    purchaseForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const quantityInput = form.querySelector('input[name="quantity"]');
            const maxStock = parseInt(quantityInput.getAttribute('max'));
            const quantity = parseInt(quantityInput.value);

            if (quantity > maxStock) {
                e.preventDefault();
                alert(`Stok tidak mencukupi. Stok tersedia: ${maxStock}`);
                return false;
            }

            if (quantity < 1) {
                e.preventDefault();
                alert('Jumlah pembelian minimal 1');
                return false;
            }
        });
    });
});


// resources/js/app.js
// Di bagian akhir file
Echo.channel('stock-updates')
    .listen('StockUpdated', (data) => {
        const productElement = document.querySelector(`.product-card[data-product-id="${data.product.id}"]`);
        if (productElement) {
            const stockElement = productElement.querySelector('.stock-display');
            if (stockElement) {
                stockElement.textContent = data.product.stock;

                // Update max attribute pada input quantity
                const quantityInput = productElement.querySelector('input[name="quantity"]');
                if (quantityInput) {
                    quantityInput.setAttribute('max', data.product.stock);

                    // Jika stok habis, disable tombol beli
                    if (data.product.stock <= 0) {
                        productElement.querySelector('button[type="submit"]').disabled = true;
                    }
                }
            }
        }
    });
