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

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" class="form-control" name="password" >
                <span id="passwordError" class="text-danger"></span>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" >
                <span id="passwordConfirmationError" class="text-danger"></span>
            </div>

            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </form>

        <div class="mt-3">
            <button class="btn btn-google" id="googleLoginBtn">Đăng ký với Google</button>
        </div>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#registerForm').submit(function(e) {
                e.preventDefault();
                $('.text-danger').text(''); // Xóa thông báo lỗi trước khi validate lại
                $('input').removeClass('is-invalid'); // Xóa lớp is-invalid trên các input

                // Validate form fields
                var isValid = true;
                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var password_confirmation = $('#password_confirmation').val();

                // Kiểm tra tên không rỗng
                if (!name) {
                    $('#nameError').text('Vui lòng nhập họ và tên.');
                    $('#name').addClass('is-invalid'); // Thêm lớp is-invalid cho input
                    isValid = false;
                }

                // Kiểm tra email hợp lệ
                if (!email || !validateEmail(email)) {
                    $('#emailError').text('Vui lòng nhập email hợp lệ.');
                    $('#email').addClass('is-invalid'); // Thêm lớp is-invalid cho input
                    isValid = false;
                }

                // Kiểm tra mật khẩu không rỗng
                if (!password) {
                    $('#passwordError').text('Vui lòng nhập mật khẩu.');
                    $('#password').addClass('is-invalid'); // Thêm lớp is-invalid cho input
                    isValid = false;
                }

                // Kiểm tra xác nhận mật khẩu
                if (password !== password_confirmation) {
                    $('#passwordConfirmationError').text('Mật khẩu xác nhận không khớp.');
                    $('#password_confirmation').addClass('is-invalid');
                    isValid = false;
                }

                // Nếu form hợp lệ, gửi AJAX request
                if (isValid) {
                    $.ajax({
                        url: '{{ route("register.submit") }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đăng ký thành công!',
                                text: response.message || 'Chào mừng bạn đến với hệ thống!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(function() {
                                window.location.href = '{{ route("login") }}';
                            });
                        },
                        error: function(response) {
                            if (response.status == 422) {
                                let errors = response.responseJSON.errors;
                                if (errors.name) $('#nameError').text(errors.name[0]);
                                if (errors.email) $('#emailError').text(errors.email[0]);
                                if (errors.password) $('#passwordError').text(errors.password[0]);
                                if (errors.password_confirmation) $('#passwordConfirmationError').text(errors.password_confirmation[0]);
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
