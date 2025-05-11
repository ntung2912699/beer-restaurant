@extends('auth.layouts.auth')

@section('content')
    <div class="auth-card">
        <div class="auth-card-header">Đăng nhập</div>

        <form id="loginForm">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
                <div class="text-danger" id="emailError"></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="text-danger" id="passwordError"></div>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <!-- Google Login Button -->
            <button id="googleLoginBtn" class="btn btn-danger mt-3">Login with Google</button>
        </form>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                $('.text-danger').text(''); // Xóa thông báo lỗi trước khi validate lại
                $('input').removeClass('is-invalid'); // Xóa lớp is-invalid trên các input

                // Validate form fields
                var isValid = true;
                var email = $('#email').val();
                var password = $('#password').val();

                // Kiểm tra email hợp lệ
                if (!email || !validateEmail(email)) {
                    $('#emailError').text('Vui lòng nhập email hợp lệ.');
                    $('#email').addClass('is-invalid'); // Thêm lớp is-invalid cho input
                    isValid = false;
                }

                // Kiểm tra password không rỗng
                if (!password) {
                    $('#passwordError').text('Vui lòng nhập mật khẩu.');
                    $('#password').addClass('is-invalid'); // Thêm lớp is-invalid cho input
                    isValid = false;
                }

                // Nếu form hợp lệ, gửi AJAX request
                if (isValid) {
                    $.ajax({
                        url: '{{ route("login.submit") }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            // Lưu token vào localStorage
                            localStorage.setItem('access_token', response.access_token);

                            Swal.fire({
                                icon: 'success',
                                title: 'Đăng nhập thành công!',
                                text: response.message || 'Chào mừng bạn đến với hệ thống!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(function() {
                                window.location.href = '{{ route("menu") }}';
                            });
                        },
                        error: function(response) {
                            if (response.status == 401) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi đăng nhập',
                                    text: response.responseJSON.message || 'Thông tin đăng nhập không chính xác.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Có lỗi xảy ra!',
                                    text: 'Vui lòng thử lại sau.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true
                                });
                            }
                        }
                    });
                }
            });

            // Google Login - API Call
            $('#googleLoginBtn').click(function() {
                window.location.href = '{{ route("auth.google.redirect") }}';
            });

        });

        // Hàm kiểm tra định dạng email hợp lệ
        function validateEmail(email) {
            var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(email);
        }
    </script>
@endsection
