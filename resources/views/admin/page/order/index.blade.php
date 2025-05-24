@extends('admin.layouts.admin-layout')

@section('title', 'Danh sách Đơn hàng')

@section('content')
    <h1 class="h3 mb-4">Danh sách Đơn hàng</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mt-3">
            <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Mã đơn</th>
                <th>Bàn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->table_id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                    <td>
                        <span class="badge bg-{{ $order->status === 'done' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="printReport({{ $order->id }})">Xuất hóa đơn</button>
                        <button class="btn btn-warning btn-sm" onclick="editOrder({{ $order->id }})">Sửa</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteOrder({{ $order->id }})">Xóa</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Không có đơn hàng nào.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
    <!-- Modal chỉnh sửa đơn hàng -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editOrderForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalLabel">Chỉnh sửa đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="editOrderId">
                        <div id="orderItemsContainer">
                            <!-- Danh sách món hiện tại sẽ được render bằng JS -->
                        </div>

                        <hr>
                        <h6>Thêm món mới</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <select class="form-select" id="newProductSelect">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" min="1" value="1" id="newProductQty" class="form-control" />
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" onclick="addNewProduct()">Thêm món</button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const token = localStorage.getItem('access_token');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + token
        }
    });

    function printReport(order_id) {
        Swal.fire({title: 'Đang tải nội dung in...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
        $.ajax({
            url: '/orders/print-content/' + order_id,
            method: 'GET',
            success: function (html) {
                Swal.close();
                const printWindow = window.open('', '', 'width=600,height=800');
                printWindow.document.write(html);
                printWindow.document.close();

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
                Swal.close();
                Swal.fire('Lỗi', 'Không thể tạo hóa đơn để in.', 'error');
            }
        });
    }

    function editOrder(orderId) {
        Swal.fire({title: 'Đang tải đơn hàng...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
        $('#editOrderId').val(orderId);
        $('#orderItemsContainer').html('Đang tải...');
        const baseShowUrl = "{{ route('order.show', ['id' => '___ID___']) }}";
        const route = baseShowUrl.replace('___ID___', orderId);

        $.get(route, function (order) {
            let html = '';
            order.items.forEach(item => {
                html += `
                <div class="row mb-2 align-items-center order-item-row" data-product-id="${item.product_id}">
                    <div class="col-md-6">${item.product_name}</div>
                    <div class="col-md-3">
                        <input type="number" name="quantities[${item.product_id}]" class="form-control" value="${item.quantity}" min="1" />
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(${item.product_id})">Xóa</button>
                    </div>
                </div>`;
            });
            $('#orderItemsContainer').html(html);
            Swal.close();
            $('#editOrderModal').modal('show');
        }).fail(function () {
            Swal.close();
            Swal.fire('Lỗi', 'Không thể tải dữ liệu đơn hàng.', 'error');
        });
    }

    function removeProduct(productId) {
        $(`.order-item-row[data-product-id="${productId}"]`).remove();
    }

    function addNewProduct() {
        const productId = $('#newProductSelect').val();
        const quantity = $('#newProductQty').val();
        const productName = $('#newProductSelect option:selected').text();

        if ($(`[data-product-id="${productId}"]`).length > 0) {
            alert('Món này đã tồn tại!');
            return;
        }

        const row = `
        <div class="row mb-2 align-items-center order-item-row" data-product-id="${productId}">
            <div class="col-md-6">${productName}</div>
            <div class="col-md-3">
                <input type="number" name="quantities[${productId}]" class="form-control" value="${quantity}" min="1" />
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(${productId})">Xóa</button>
            </div>
        </div>`;
        $('#orderItemsContainer').append(row);
    }

    $('#editOrderForm').on('submit', function (e) {
        e.preventDefault();
        const orderId = $('#editOrderId').val();
        const data = $(this).serialize();

        const baseUpdateUrl = "{{ route('order.update', ['id' => '___ID___']) }}";
        const route = baseUpdateUrl.replace('___ID___', orderId);

        Swal.fire({title: 'Đang cập nhật...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
        $.ajax({
            url: route,
            type: 'PUT',
            data: data,
            success: function () {
                Swal.close();
                Swal.fire('Thành công', 'Đơn hàng đã được cập nhật!', 'success').then(() => {
                    location.reload();
                });
            },
            error: function () {
                Swal.close();
                Swal.fire('Lỗi', 'Cập nhật đơn hàng thất bại!', 'error');
            }
        });
    });

    function deleteOrder(orderId) {
        Swal.fire({
            title: 'Bạn có chắc muốn xóa đơn hàng?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({title: 'Đang xóa...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                const baseDestroyUrl = "{{ route('order.destroy', ['id' => '___ID___']) }}";
                const route = baseDestroyUrl.replace('___ID___', orderId);
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    success: function () {
                        Swal.close();
                        Swal.fire('Đã xóa!', '', 'success').then(() => location.reload());
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Lỗi', 'Không thể xóa đơn hàng.', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
