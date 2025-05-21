@extends('admin.layouts.admin-layout')

@section('title', 'Danh sách Sản phẩm')

@section('content')
    <h1 class="h3 mb-4">Danh sách Sản phẩm <button class="btn btn-primary mb-3" onclick="showCreateProductModal()">+ Thêm sản phẩm</button>
    </h1>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mt-3">
            <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Mô tả</th>
                <th>Ảnh</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                    <td>{{ $product->category->name ?? 'Chưa có' }}</td>
                    <td>{{ $product->description }}</td>
                    <td>
                        @if ($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="60">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editProduct({{ $product->id }})">Sửa</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct({{ $product->id }})">Xóa</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Không có sản phẩm nào.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Thêm sản phẩm -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createProductForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductModalLabel">Thêm sản phẩm mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tên sản phẩm</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Giá</label>
                            <input type="text" class="form-control" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label>Danh mục</label>
                            <select class="form-select" name="category_id">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Ảnh</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa sản phẩm -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editProductForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editProductId" name="id">

                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Giá bán (VNĐ)</label>
                            <input type="text" class="form-control" id="editProductPrice" name="price" required>
                        </div>

                        <div class="mb-3">
                            <label for="editProductCategory" class="form-label">Danh mục</label>
                            <select class="form-select" id="editProductCategory" name="category_id">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editProductDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="editProductDescription" name="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="editProductImage" class="form-label">Ảnh mới (nếu muốn thay)</label>
                            <input type="file" class="form-control" id="editProductImage" name="image">
                        </div>

                        <div class="mb-3" id="currentImagePreview" style="display: none;">
                            <label>Ảnh hiện tại:</label><br>
                            <img src="" id="currentImage" width="100" alt="Ảnh hiện tại">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Thư viện cần thiết -->
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

        const assetBase = "{{ asset('/') }}";

        function formatPriceToVND(value) {
            if (value === null || value === undefined || value === '') return '';
            // Chuyển value thành number (float)
            const number = parseFloat(value);
            if (isNaN(number)) return '';
            // Làm tròn số (nếu muốn bỏ phần thập phân)
            const rounded = Math.round(number);
            // Format số theo định dạng VN
            return rounded.toLocaleString('vi-VN') + ' đ';
        }

        function unformatPrice(value) {
            // Bỏ hết dấu chấm, đ, khoảng trắng
            return value.replace(/[.\sđ]/g, '');
        }

        function formatVND(value) {
            if (!value) return '';
            // Loại bỏ hết ký tự không phải số
            let number = value.replace(/[^\d]/g, '');
            if (number === '') return '';

            // Chuyển thành số và format dạng 1.000.000
            let result = parseInt(number).toLocaleString('vi-VN');

            // Thêm đuôi " đ"
            return result + ' đ';
        }

        function unformatVND(value) {
            // Bỏ hết dấu chấm và bỏ " đ"
            return value.replace(/\./g, '').replace(/\s?đ/g, '');
        }

        // Áp dụng format cho input (vừa nhập vừa format)
        function setupVNDFormat(input) {
            input.addEventListener('input', function(e) {
                let caretPos = this.selectionStart; // vị trí con trỏ
                let originalLength = this.value.length;

                let unformatted = unformatVND(this.value);
                if (unformatted === '') {
                    this.value = '';
                    return;
                }

                this.value = formatVND(unformatted);

                // Tính toán lại vị trí con trỏ để tránh nhảy lung tung
                let newLength = this.value.length;
                caretPos = caretPos + (newLength - originalLength);
                this.setSelectionRange(caretPos, caretPos);
            });
        }

        // Bỏ format trước khi submit
        function unformatInputOnSubmit(form, selector) {
            form.addEventListener('submit', function(e) {
                let input = form.querySelector(selector);
                if (input) {
                    input.value = unformatVND(input.value);
                }
            });
        }

        // Áp dụng cho modal tạo sản phẩm
        setupVNDFormat(document.querySelector('input[name="price"]'));
        unformatInputOnSubmit(document.getElementById('createProductForm'), 'input[name="price"]');

        // Áp dụng cho modal sửa sản phẩm
        setupVNDFormat(document.getElementById('editProductPrice'));
        unformatInputOnSubmit(document.getElementById('editProductForm'), '#editProductPrice');

        function showCreateProductModal() {
            $('#createProductForm')[0].reset();
            $('#createProductModal').modal('show');
        }

        $('#createProductForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            Swal.fire({title: 'Đang thêm...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
            $.ajax({
                url: "{{ route('product.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    Swal.close();
                    Swal.fire('Thành công', 'Sản phẩm đã được thêm!', 'success').then(() => location.reload());
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Lỗi', 'Không thể thêm sản phẩm.', 'error');
                }
            });
        });

        function editProduct(productId) {
            Swal.fire({title: 'Đang tải sản phẩm...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
            const baseShowUrl = "{{ route('product.show', ['id' => '___ID___']) }}";
            const route = baseShowUrl.replace('___ID___', productId);

            $.get(route, function (product) {
                $('#editProductId').val(product.id);
                $('#editProductName').val(product.name);
                $('#editProductPrice').val(product.price);
                $('#editProductCategory').val(product.category_id);
                $('#editProductDescription').val(product.description);

                if (product.image) {
                    const urlImg = assetBase + product.image;
                    $('#currentImage').attr('src', urlImg);
                    $('#currentImagePreview').show();
                } else {
                    $('#currentImagePreview').hide();
                }

                Swal.close();
                $('#editProductModal').modal('show');
                $('#editProductPrice').val(formatPriceToVND($('#editProductPrice').val()));
                $('#editProductPrice').on('input', function() {
                    const caretPosition = this.selectionStart;
                    const rawValue = unformatPrice(this.value);
                    if (rawValue === '') {
                        $(this).val('');
                        return;
                    }
                    const formatted = formatPriceToVND(rawValue);
                    $(this).val(formatted);

                    // Cố gắng giữ caret đúng vị trí (đơn giản, có thể cần nâng cấp)
                    this.selectionStart = this.selectionEnd = caretPosition;
                });
            }).fail(function () {
                Swal.close();
                Swal.fire('Lỗi', 'Không thể tải dữ liệu sản phẩm.', 'error');
            });
        }

        $('#editProductForm').on('submit', function (e) {
            e.preventDefault();
            const rawPrice = unformatPrice($('#editProductPrice').val());
            $('#editProductPrice').val(rawPrice);
            const productId = $('#editProductId').val();
            const formData = new FormData(this);

            Swal.fire({title: 'Đang cập nhật...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
            const baseShowUrl = "{{ route('product.update', ['id' => '___ID___']) }}";
            const route = baseShowUrl.replace('___ID___', productId);
            $.ajax({
                url: route,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {'X-HTTP-Method-Override': 'PUT'},
                success: function () {
                    Swal.close();
                    Swal.fire('Thành công', 'Sản phẩm đã được cập nhật!', 'success').then(() => location.reload());
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Lỗi', 'Cập nhật sản phẩm thất bại!', 'error');
                }
            });
        });

        function deleteProduct(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn xóa sản phẩm?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({title: 'Đang xóa...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                    const baseShowUrl = "{{ route('product.destroy', ['id' => '___ID___']) }}";
                    const route = baseShowUrl.replace('___ID___', id);
                    $.ajax({
                        url: route,
                        type: 'DELETE',
                        success: function () {
                            Swal.close();
                            Swal.fire('Đã xóa!', '', 'success').then(() => location.reload());
                        },
                        error: function () {
                            Swal.close();
                            Swal.fire('Lỗi', 'Không thể xóa sản phẩm.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
