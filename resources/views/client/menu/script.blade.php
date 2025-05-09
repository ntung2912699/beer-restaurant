<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                </div>
                            `;
                        });

                        $('#products-tab-content').html(productHtml);
                        showLoading(false);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: error,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    showLoading(false);
                }
            });
        }
    });

    let currentCartId = null;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        updateCartUI();

        const tableIdInit = $('.form-select').val();
        $.ajax({
                url: `/api/cart/${tableIdInit}`,
                method: 'GET',
                success: function (data) {
                    const cart = [];
                    currentCartId = data.cart_id;

                    data.items.forEach(item => {
                        cart.push({
                            id: item.product.id,
                            name: item.product.name,
                            price: item.product.price,
                            image: item.product.image,
                            quantity: item.quantity
                        });
                    });

                    saveCartToLocalStorage(cart);
                    if (cart.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thông báo',
                            text: `Bàn ${tableIdInit} chưa có đơn`,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                    }
                    updateCartUI();
                },
                error: function (tableIdInit) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: `Không thể tải giỏ hàng bàn ${tableIdInit}.`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                }
            });

        // Khi chọn bàn
        $('.form-select').on('change', function () {
            const tableId = $(this).val();

            $.ajax({
                url: `/api/cart/${tableId}`,
                method: 'GET',
                success: function (data) {
                    const cart = [];
                    currentCartId = data.cart_id;

                    data.items.forEach(item => {
                        cart.push({
                            id: item.product.id,
                            name: item.product.name,
                            price: item.product.price,
                            image: item.product.image,
                            quantity: item.quantity
                        });
                    });

                    saveCartToLocalStorage(cart);
                    updateCartUI();
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: `Không thể tải giỏ hàng bàn ${tableId}.`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                }
            });
        });

        let previousTableId = $('.form-select').val(); // lấy bàn đang được chọn ban đầu

        $('.form-select').on('change', function () {
            const newTableId = $(this).val(); // bàn mới
            const cart = getCartFromLocalStorage();

            if (cart.length > 0 && previousTableId) {
                const payload = {
                    table_id: previousTableId,
                    items: cart.map(item => ({
                        id: item.id, // hoặc product_id, tùy backend bạn validate
                        quantity: item.quantity
                    }))
                };

                const url = currentCartId ? `/api/cart/${currentCartId}` : '/api/cart';
                const method = currentCartId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    success: async function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã lưu!',
                            text: `Giỏ hàng bàn ${previousTableId} đã được lưu.`,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });

                        currentCartId = response.cart_id;
                        localStorage.removeItem('cart');

                        // Lấy giỏ hàng mới
                        await fetchCartByTableId(newTableId);
                        previousTableId = newTableId;
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: `Lỗi khi lưu giỏ hàng bàn ${previousTableId}.`,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                    }
                });
            } else {
                // Nếu không có cart thì chỉ đổi bàn thôi
                fetchCartByTableId(newTableId);
                previousTableId = newTableId;
            }
        });

        function fetchCartByTableId(tableId) {
            $.ajax({
                url: `/api/cart/${tableId}`,
                method: 'GET',
                success: function (data) {
                    const cart = [];

                    currentCartId = data.cart_id;

                    data.items.forEach(item => {
                        cart.push({
                            id: item.product.id,
                            name: item.product.name,
                            price: item.product.price,
                            image: item.product.image,
                            quantity: item.quantity
                        });
                    });

                    saveCartToLocalStorage(cart);
                    if (cart.length === 0) {
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Thông báo',
                                text: `Bàn ${tableId} chưa có đơn`,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            });
                        }, 200);
                    }
                    updateCartUI();
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: `Không thể tải giỏ hàng bàn ${tableId}.`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                }
            });
        }

    });

    // =================== Cart localStorage functions ===================

    function getCartFromLocalStorage() {
        const cart = localStorage.getItem('cart');
        return cart ? JSON.parse(cart) : [];
    }

    function saveCartToLocalStorage(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function addToCart(id, name, price, image) {
        let cart = getCartFromLocalStorage();
        let exists = false;

        cart.forEach(item => {
            if (item.id === id) {
                item.quantity += 1;
                exists = true;
            }
        });

        if (!exists) {
            cart.push({ id, name, price, image, quantity: 1 });
        }

        saveCartToLocalStorage(cart);
        updateCartUI();
    }

    function updateCartUI() {
        const cart = getCartFromLocalStorage();
        const cartItemsContainer = $('#cart-items');
        const cartTotal = $('#cart-total');
        cartItemsContainer.html('');
        let totalAmount = 0;

        cart.forEach(item => {
            totalAmount += item.price * item.quantity;
            cartItemsContainer.append(`
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
            `);
        });

        cartTotal.text(formatVND(totalAmount));
    }

    function updateQuantity(id, change) {
        let cart = getCartFromLocalStorage();
        cart.forEach(item => {
            if (item.id === id) {
                item.quantity += change;
                if (item.quantity < 1) item.quantity = 1;
            }
        });
        saveCartToLocalStorage(cart);
        updateCartUI();
    }

    function removeFromCart(id) {
        let cart = getCartFromLocalStorage();
        cart = cart.filter(item => item.id !== id);
        saveCartToLocalStorage(cart);
        updateCartUI();
    }

    $('#checkout-btn').on('click', function () {
        const tableId = $('.form-select').val();

        // Kiểm tra bàn đã chọn
        if (!tableId) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn bàn!',
                text: 'Vui lòng chọn bàn để thanh toán.'
            });
            return;
        }

        // Lấy giỏ hàng từ localStorage
        const cart = getCartFromLocalStorage();

        // Kiểm tra giỏ hàng có sản phẩm không
        if (cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Giỏ hàng trống!',
                text: 'Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.'
            });
            return;
        }

        // Chuẩn bị payload gửi lên server
        const payload = {
            table_id: tableId,
            items: cart.map(item => ({
                id: item.id, // Giữ id cho mỗi sản phẩm
                quantity: item.quantity
            }))
        };

        // Gửi dữ liệu lên API
        $.ajax({
            url: '/api/orders',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function (response) {
                // Xóa giỏ hàng localStorage sau khi thanh toán
                localStorage.removeItem('cart');
                updateCartUI();  // Cập nhật lại giao diện giỏ hàng
                Swal.fire({
                    icon: 'success',
                    title: 'Thanh toán hoàn tất!',
                    text: `Mã đơn hàng: #${response.order_id}`,
                    confirmButtonText: 'In hóa đơn',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Gọi API để lấy HTML hóa đơn và in
                        $.ajax({
                            url: `/orders/print-content/${response.order_id}`,
                            method: 'GET',
                            success: function (html) {
                                const printWindow = window.open('', '', 'width=600,height=800');
                                printWindow.document.write(html);
                                printWindow.document.close();

                                // Đợi ảnh QR (hoặc toàn bộ DOM) load xong rồi mới in
                                printWindow.onload = function () {
                                    const qrImg = printWindow.document.querySelector('img#qr-image');
                                    if (qrImg && !qrImg.complete) {
                                        qrImg.onload = function () {
                                            printWindow.focus();
                                            printWindow.print();
                                            printWindow.close();
                                        };
                                    } else {
                                        printWindow.focus();
                                        printWindow.print();
                                        printWindow.close();
                                    }
                                };
                            },
                            error: function () {
                                Swal.fire('Lỗi', 'Không thể tạo hóa đơn để in.', 'error');
                            }
                        });
                    }
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: xhr.responseJSON?.message || 'Không thể tạo đơn hàng, vui lòng thử lại.'
                });
            }
        });
    });

</script>


