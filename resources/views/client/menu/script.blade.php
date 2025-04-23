<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showLoading(flag) {
        $('#loading-icon').attr('hidden', !flag);
    }

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    $(document).ready(function() {
        var loadingIcon = $('#loading-icon');
        var tabInitActive = $('#tab-active-1');
        if (tabInitActive.length) {
            tabInitActive.addClass('active');
        }

        var categoryId = tabInitActive.data('category-id');
        loadProductsByCategory(categoryId);
        $('.tab-link').on('click', function() {
            $('.tab-link').removeClass('active');
            $(this).addClass('active');

            var categoryId = $(this).data('category-id');
            loadProductsByCategory(categoryId);
        });

        function loadProductsByCategory(categoryId) {
            $('#products-tab-content').html('');
            showLoading(true);
            $.ajax({
                url: '/api/products-by-category',
                method: 'GET',
                data: {
                    categoryId: categoryId
                },
                success: function(response) {
                    if (response && response.length > 0) {
                        var productHtml = '';
                        
                        $.each(response, function(index, product) {
                            productHtml += `
                                <div class="col-lg-6">
                                    <a href="#">
                                        <div class="d-flex align-items-center">
                                            <img class="flex-shrink-0 img-fluid rounded" src="${product.image}" alt="" style="width: 80px;">
                                            <div class="w-100 d-flex flex-column text-start ps-4">
                                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                    <span>${product.name}</span>
                                                    <span class="text-primary">${formatVND(product.price)}</span>
                                                </h5>
                                                <i class="fa fa-plus-square text-success" style="cursor: pointer;"
                                                onclick="addToCart(${product.id}, '${product.name}', ${product.price}, '${product.image}')"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                        });

                        $('#products-tab-content').html(productHtml);
                        showLoading(false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    showLoading(false);
                }
            });
        }


        $.ajax({
        url: '/api/tables-all',
        method: 'GET',
        data: {},
        success: function(response) {
            if (response && response.length > 0) {
                var tableHtml = '';
                
                $.each(response, function(index, table) {
                // Tạo HTML cho từng bàn
                tableHtml += `
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 tab-link" 
                            id="tab-active-${index}" 
                            data-bs-toggle="pill" 
                            href="#tab-${index}" 
                            data-category-id="${table.id}">
                            <i class="fa fa-table"></i>
                            <div class="ps-3">
                                <small class="text-body">Bàn</small>
                                <h6 class="mt-n1 mb-0">${table.name}</h6>
                            </div>
                        </a>
                    </li>
                `;
            });

            // Cập nhật nội dung của #table-tab-content
            $('#table-tab-content').html(tableHtml);
                showLoading(false);
            }
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
            showLoading(false);
        }
    });
    });

// Thêm sản phẩm vào giỏ
function addToCart(id, name, price, image) {
    let cart = getCartFromLocalStorage();
    let productExists = false;

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    cart.forEach(item => {
        if (item.id === id) {
            item.quantity += 1; // Nếu có thì tăng số lượng
            productExists = true;
        }
    });

    // Nếu sản phẩm chưa có trong giỏ, thêm mới
    if (!productExists) {
        const newProduct = {
            id: id,
            name: name,
            price: price,
            image: image,
            quantity: 1
        };
        cart.push(newProduct);
    }

        saveCartToLocalStorage(cart);
        updateCartUI();
    }

    // Lấy giỏ hàng từ localStorage
    function getCartFromLocalStorage() {
        const cart = localStorage.getItem('cart');
        return cart ? JSON.parse(cart) : [];
    }

    // Lưu giỏ hàng vào localStorage
    function saveCartToLocalStorage(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Cập nhật giao diện giỏ hàng
    function updateCartUI() {
        const cart = getCartFromLocalStorage();
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');
        cartItemsContainer.innerHTML = '';
        let totalAmount = 0;

        // Hiển thị các sản phẩm trong giỏ
        cart.forEach(item => {
            totalAmount += item.price * item.quantity;
            cartItemsContainer.innerHTML += `
                <div class="cart-item d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <img class="img-fluid rounded" src="${item.image}" alt="${item.name}" style="width: 50px;">
                        <span class="ms-2">${item.name}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary me-2" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="updateQuantity(${item.id}, 1)">+</button>
                        <button class="btn btn-sm btn-danger ms-2" onclick="removeFromCart(${item.id})">X</button>
                    </div>
                </div>
            `;
        });

        // Cập nhật tổng số tiền
        cartTotal.textContent = formatVND(totalAmount);
    }

    // Cập nhật số lượng sản phẩm trong giỏ
    function updateQuantity(id, change) {
        let cart = getCartFromLocalStorage();
        cart.forEach(item => {
            if (item.id === id) {
                item.quantity += change;
                if (item.quantity <= 0) item.quantity = 1; // Không giảm dưới 1
            }
        });
        saveCartToLocalStorage(cart);
        updateCartUI();
    }

    // Xóa sản phẩm khỏi giỏ
    function removeFromCart(id) {
        let cart = getCartFromLocalStorage();
        cart = cart.filter(item => item.id !== id);
        saveCartToLocalStorage(cart);
        updateCartUI();
    }

    // Thanh toán giỏ hàng
    document.getElementById('checkout-btn').addEventListener('click', function () {
        const cart = getCartFromLocalStorage();
        if (cart.length === 0) {
            alert('Giỏ hàng của bạn trống! Hãy thêm sản phẩm vào giỏ.');
            return;
        }

        // Xử lý thanh toán ở đây
        alert('Thanh toán thành công!');
        localStorage.removeItem('cart'); // Xóa giỏ hàng sau khi thanh toán
        updateCartUI(); // Cập nhật lại giao diện giỏ hàng
    });

    // Khởi tạo khi tải trang
    document.addEventListener('DOMContentLoaded', function () {
        updateCartUI(); // Cập nhật giao diện giỏ hàng khi trang được tải
    });

</script>


