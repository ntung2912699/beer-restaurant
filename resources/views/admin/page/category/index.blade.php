@extends('admin.layouts.admin-layout')

@section('title', 'Danh sách Danh mục')

@section('content')
    <h1 class="h3 mb-4">
        Danh sách Danh mục
        <button class="btn btn-primary mb-3" onclick="showCreateCategoryModal()">+ Thêm danh mục</button>
    </h1>

    <table class="table table-bordered table-hover align-middle mt-3">
        <thead class="table-light">
        <tr>
            <th>STT</th>
            <th>Tên danh mục</th>
            <th>Icon</th>
            <th>Mô tả</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @forelse($categories as $index => $category)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>{!! $category->icon !!}</td> {{-- hiển thị icon (html) --}}
                <td>{{ $category->description }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editCategory({{ $category->id }})">Sửa</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCategory({{ $category->id }})">Xóa</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Không có danh mục nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Modal Thêm danh mục -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="createCategoryForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tên danh mục</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Icon (HTML hoặc class)</label>
                            <input type="text" name="icon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sửa danh mục -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editCategoryForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editCategoryId" name="id">
                        <div class="mb-3">
                            <label>Tên danh mục</label>
                            <input type="text" id="editCategoryName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Icon (HTML hoặc class)</label>
                            <input type="text" id="editCategoryIcon" name="icon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea id="editCategoryDescription" name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Thư viện -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showCreateCategoryModal() {
            $('#createCategoryForm')[0].reset();
            $('#createCategoryModal').modal('show');
        }

        $('#createCategoryForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            Swal.fire({title: 'Đang thêm...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
            $.post("{{ route('category.store') }}", formData)
                .done(() => {
                    Swal.fire('Thành công', 'Danh mục đã được thêm!', 'success').then(() => location.reload());
                })
                .fail(() => {
                    Swal.fire('Lỗi', 'Không thể thêm danh mục.', 'error');
                });
        });

        function editCategory(id) {
            const route = "{{ route('category.show', ['id' => '___ID___']) }}".replace('___ID___', id);
            Swal.fire({
                title: 'Đang tải...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: route,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(data) {
                    $('#editCategoryId').val(data.id);
                    $('#editCategoryName').val(data.name);
                    $('#editCategoryIcon').val(data.icon);
                    $('#editCategoryDescription').val(data.description);
                    Swal.close();
                    $('#editCategoryModal').modal('show');
                },
                error: function() {
                    Swal.fire('Lỗi', 'Không thể tải dữ liệu.', 'error');
                }
            });
        }

        $('#editCategoryForm').on('submit', function (e) {
            e.preventDefault();
            const id = $('#editCategoryId').val();
            const route = "{{ route('category.update', ['id' => '___ID___']) }}".replace('___ID___', id);
            const formData = $(this).serialize();

            Swal.fire({title: 'Đang cập nhật...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
            $.ajax({
                url: route,
                type: 'POST',
                data: formData,
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: () => {
                    Swal.fire('Thành công', 'Danh mục đã được cập nhật!', 'success').then(() => location.reload());
                },
                error: () => {
                    Swal.fire('Lỗi', 'Không thể cập nhật danh mục.', 'error');
                }
            });
        });

        function deleteCategory(id) {
            const route = "{{ route('category.destroy', ['id' => '___ID___']) }}".replace('___ID___', id);
            Swal.fire({
                title: 'Bạn có chắc muốn xóa danh mục?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({title: 'Đang xóa...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                    $.ajax({
                        url: route,
                        type: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                        },
                        success: () => {
                            Swal.fire('Đã xóa!', '', 'success').then(() => location.reload());
                        },
                        error: () => {
                            Swal.fire('Lỗi', 'Không thể xóa danh mục.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
