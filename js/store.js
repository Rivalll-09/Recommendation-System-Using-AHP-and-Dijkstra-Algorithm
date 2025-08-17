        // Ambil elemen tombol dan input
        const searchButton = document.getElementById('search-button');
        const searchInput = document.getElementById('search-input');
        const productCards = document.querySelectorAll('.product-card');

        // Fungsi pencarian
        function searchProducts() {
            const query = searchInput.value.toLowerCase().trim();

            // Loop melalui setiap produk
            productCards.forEach(card => {
                const productName = card.querySelector('h3').textContent.toLowerCase();
                const productPrice = card.querySelector('p').textContent.toLowerCase();

                // Periksa apakah nama produk atau harga mengandung query
                if (productName.includes(query) || productPrice.includes(query)) {
                    card.style.display = ''; // Tampilkan produk
                } else {
                    card.style.display = 'none'; // Sembunyikan produk
                }
            });
        }

        // Tambahkan event listener ke tombol Cari
        searchButton.addEventListener('click', searchProducts);

        // Tambahkan event listener untuk pencarian ketika menekan Enter
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                searchProducts();
            }
        });