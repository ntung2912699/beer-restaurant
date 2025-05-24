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
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">👁️</button>
                </div>
                <div class="text-danger" id="passwordError"></div>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="{{ route('register') }}" id="register-page" class="btn btn-danger mt-3">Đăng Ký</a>
        </form>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function () {
                const passwordInput = $('#password');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).text(type === 'password' ? '👁️' : '🙈');
            });

            $('#loginForm').submit(function(e) {
                e.preventDefault();
                $('.text-danger').text('');
                $('input').removeClass('is-invalid');

                var isValid = true;
                var email = $('#email').val();
                var password = $('#password').val();

                if (!email || !validateEmail(email)) {
                    $('#emailError').text('Vui lòng nhập email hợp lệ.');
                    $('#email').addClass('is-invalid');
                    isValid = false;
                }

                if (!password) {
                    $('#passwordError').text('Vui lòng nhập mật khẩu.');
                    $('#password').addClass('is-invalid');
                    isValid = false;
                }

                if (isValid) {
                    // Bắt đầu loading
                    Swal.fire({
                        title: 'Đang đăng nhập...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '{{ route("login.submit") }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.close(); // Tắt loading
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
                            Swal.close(); // Tắt loading

                            if (response.status == 401) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi đăng nhập',
                                    text: response.responseJSON.message || 'Thông tin đăng nhập không chính xác.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2500,
                                    timerProgressBar: true
                                });
                            } else if (response.status == 403) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Chưa được phê duyệt',
                                    text: response.responseJSON.message || 'Tài khoản chưa được quản trị viên phê duyệt.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2500,
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
                                    timer: 2500,
                                    timerProgressBar: true
                                });
                            }
                        }
                    });
                }
            });

            $('#googleLoginBtn').click(function() {
                window.location.href = '{{ route("auth.google.redirect") }}';
            });
        });

        function validateEmail(email) {
            var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(email);
        }
    </script>
@endsection
