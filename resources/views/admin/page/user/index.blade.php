@extends('admin.layouts.admin-layout')

@section('title', 'Danh sách Tài Khoản')

@section('content')
    <h1 class="h3 mb-4">
        Danh sách Người Dùng
    </h1>

    <table class="table table-bordered table-hover align-middle mt-3">
        <thead class="table-light">
        <tr>
            <th>STT</th>
            <th>Tên User</th>
            <th>Email</th>
            <th>Password</th>
            <th>Phân quyền</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <div class="input-group">
                        <input type="password" class="form-control user-password" value="{{ $user->password }}" readonly>
                        <button class="btn btn-outline-secondary toggle-password" type="button">👁️</button>
                    </div>
                </td>
                <td>
                    @if($user->roles === "admin") {{-- Giả sử cột is_approved --}}
                        <span class="badge bg-dark text-white">{{ $user->roles }}</span>
                    @else
                        <span class="badge bg-primary text-white">{{ $user->roles }}</span>
                    @endif
                </td>
                <td>
                    @if($user->is_approved) {{-- Giả sử cột is_approved --}}
                    <span class="badge bg-success">Đã duyệt</span>
                    @else
                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})">Sửa</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">Xóa</button>
                    @if(!$user->is_approved)
                        <button class="btn btn-primary btn-sm" onclick="approveUser({{ $user->id }}, this)">Duyệt</button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Không có tài khoản nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- Modal sửa --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editUserForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa thông tin tài khoản</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" id="editUserName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" id="editUserEmail" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Mật khẩu (bỏ trống nếu không đổi)</label>
                            <input type="password" id="editUserPassword" name="password" class="form-control">
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

        // Toggle hiển thị mật khẩu trong bảng
        $(document).on('click', '.toggle-password', function () {
            const input = $(this).closest('.input-group').find('input');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).text('🙈');
            } else {
                input.attr('type', 'password');
                $(this).text('👁️');
            }
        });

        function editUser(id) {
            const route = "{{ route('user.show', ['id' => '___ID___']) }}".replace('___ID___', id);
            Swal.fire({title: 'Đang tải...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
            $.ajax({
                url: route,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(data) {
                    $('#editUserId').val(data.id);
                    $('#editUserName').val(data.name);
                    $('#editUserEmail').val(data.email);
                    $('#editUserPassword').val(''); // Không fill password
                    Swal.close();
                    $('#editUserModal').modal('show');
                },
                error: function() {
                    Swal.fire('Lỗi', 'Không thể tải dữ liệu.', 'error');
                }
            });
        }

        $('#editUserForm').on('submit', function (e) {
            e.preventDefault();
            const id = $('#editUserId').val();
            const route = "{{ route('user.update', ['id' => '___ID___']) }}".replace('___ID___', id);
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
                    Swal.fire('Thành công', 'Người dùng đã được cập nhật!', 'success').then(() => location.reload());
                },
                error: () => {
                    Swal.fire('Lỗi', 'Không thể cập nhật người dùng.', 'error');
                }
            });
        });

        function deleteUser(id) {
            const route = "{{ route('user.destroy', ['id' => '___ID___']) }}".replace('___ID___', id);
            Swal.fire({
                title: 'Bạn có chắc muốn xóa người dùng?',
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
                            Swal.fire('Lỗi', 'Không thể xóa người dùng.', 'error');
                        }
                    });
                }
            });
        }

        function approveUser(userId, btn) {
            const route = "{{ route('user.approve', ['id' => '___ID___']) }}".replace('___ID___', userId);

            Swal.fire({
                title: 'Xác nhận duyệt người dùng này?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Duyệt',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Đang xử lý...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: route,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                        },
                        success: function() {
                            Swal.fire('Thành công', 'Người dùng đã được duyệt.', 'success');
                            // Cập nhật UI: ẩn nút Duyệt, thay đổi trạng thái
                            $(btn).closest('tr').find('td:nth-child(5)').html('<span class="badge bg-success">Đã duyệt</span>');
                            $(btn).remove(); // Xóa nút Duyệt
                        },
                        error: function() {
                            Swal.fire('Lỗi', 'Không thể duyệt người dùng.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
