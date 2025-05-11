<!-- Navbar & Hero Start -->
<div class="position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i>Restoran</h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0 pe-4">
                        <a href="{{ route('menu') }}" class="nav-item nav-link active">Menu</a>
                        <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link active">Trang Quản Lý</a>
                    </div>
                    @if(auth()->user())
                        <a class="nav-item nav-link active">
                            {{ auth()->user()->name }}
                            <i id="logoutBtn" class="fas fa-sign-out-alt"></i>
                        </a>
                    @else
                    <a href="" class="btn btn-primary py-2 px-4">Đăng Nhập</a>
                    @endif
                </div>
            </nav>

            <div class="py-5 bg-dark hero-header mb-5">
                {{-- <div class="container text-center my-5 pt-5 pb-4">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Food Menu</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Menu</li>
                        </ol>
                    </nav>
                </div> --}}
            </div>
        </div>
        <!-- Navbar & Hero End -->

<script>
    $(document).ready(function() {
        $('#logoutBtn').click(function() {
            Swal.fire({
                title: 'Bạn có chắc chắn muốn đăng xuất?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đăng xuất',
                cancelButtonText: 'Hủy',
                reverseButtons: true,
                toast: true,
                position: 'top-end',
                // timer: 1500,
                // timerProgressBar: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = localStorage.getItem('access_token');

                    if (!token) {
                        window.location.href = '{{ route("login") }}';
                        return;
                    }

                    $.ajax({
                        url: '{{ route("logout") }}',
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            localStorage.removeItem('access_token');

                            Swal.fire({
                                icon: 'success',
                                title: 'Đăng xuất thành công!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(function() {
                                window.location.href = '{{ route("login") }}';
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Đăng xuất thất bại!',
                                text: 'Vui lòng thử lại.',
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
        });
    });
</script>
