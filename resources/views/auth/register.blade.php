@extends('auth.layouts.auth')

@section('content')
    <div class="auth-card">
        <div class="auth-card-header">Đăng ký</div>

        <form id="registerForm">
            @csrf

            <div class="form-group">
                <label for="name">Họ và Tên</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" >
                <span id="nameError" class="text-danger"></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >
                <span id="emailError" class="text-danger"></span>
            </div>

            <div class="form-group position-relative">
                <label for="password">Mật khẩu</label>
                <div class="input-group">
                    <input id="password" type="password" class="form-control" name="password">
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password">👁️</button>
                </div>
                <span id="passwordError" class="text-danger"></span>
            </div>

            <div class="form-group position-relative">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <div class="input-group">
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation">👁️</button>
                </div>
                <span id="passwordConfirmationError" class="text-danger"></span>
            </div>

            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <a href="{{ route('login') }}" id="register-page" class="btn btn-danger mt-3">Đăng Nhập</a>
        </form>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.toggle-password').on('click', function () {
                const targetInput = $($(this).data('target'));
                const currentType = targetInput.attr('type');
                const newType = currentType === 'password' ? 'text' : 'password';
                targetInput.attr('type', newType);
                $(this).text(newType === 'password' ? '👁️' : '🙈');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#registerForm').submit(function (e) {
                e.preventDefault();
                $('.text-danger').text('');
                $('input').removeClass('is-invalid');

                let isValid = true;
                let name = $('#name').val();
                let email = $('#email').val();
                let password = $('#password').val();
                let password_confirmation = $('#password_confirmation').val();

                if (!name) {
                    $('#nameError').text('Vui lòng nhập họ và tên.');
                    $('#name').addClass('is-invalid');
                    isValid = false;
                }
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
                if (password !== password_confirmation) {
                    $('#passwordConfirmationError').text('Mật khẩu xác nhận không khớp.');
                    $('#password_confirmation').addClass('is-invalid');
                    isValid = false;
                }

                if (isValid) {
                    // Hiện loading khi gửi yêu cầu
                    Swal.fire({
                        title: 'Đang gửi mã xác thực...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '{{ route("register.request") }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function (res) {
                            Swal.close(); // Tắt loading

                            Swal.fire({
                                title: 'Nhập mã xác thực',
                                input: 'text',
                                inputLabel: 'Mã OTP đã gửi đến email của bạn',
                                inputPlaceholder: 'Nhập mã 6 số',
                                showCancelButton: true,
                                confirmButtonText: 'Xác nhận',
                                preConfirm: (otp) => {
                                    Swal.showLoading(); // Loading khi xác thực OTP
                                    return $.post({
                                        url: '{{ route("register.verify") }}',
                                        data: {
                                            email: $('#email').val(),
                                            otp: otp,
                                            _token: '{{ csrf_token() }}'
                                        }
                                    }).then(response => {
                                        Swal.fire('Thành công', response.message, 'success')
                                            .then(() => window.location.href = '{{ route("login") }}');
                                    }).catch(err => {
                                        Swal.hideLoading();
                                        Swal.showValidationMessage(
                                            err.responseJSON?.message || 'Mã xác thực không đúng hoặc đã hết hạn.'
                                        );
                                    });
                                }
                            });
                        },
                        error: function (res) {
                            Swal.close(); // Tắt loading
                            if (res.status === 422) {
                                const errors = res.responseJSON.errors;
                                if (errors.name) {
                                    $('#nameError').text(errors.name[0]);
                                    $('#name').addClass('is-invalid');
                                }
                                if (errors.email) {
                                    $('#emailError').text(errors.email[0]);
                                    $('#email').addClass('is-invalid');
                                }
                                if (errors.password) {
                                    $('#passwordError').text(errors.password[0]);
                                    $('#password').addClass('is-invalid');
                                }
                                if (errors.password_confirmation) {
                                    $('#passwordConfirmationError').text(errors.password_confirmation[0]);
                                    $('#password_confirmation').addClass('is-invalid');
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: res.responseJSON?.message || 'Không thể gửi mã xác thực. Vui lòng thử lại.',
                                    toast: true,
                                    position: 'top-end',
                                    timer: 2000,
                                    showConfirmButton: false
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
