<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        #wrapper {
            min-height: 100vh;
            display: flex;
        }

        #sidebar-wrapper {
            width: 250px;
            min-height: 100vh;
        }

        #page-content-wrapper {
            flex: 1;
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" id="sidebar-wrapper">
        <h4 class="text-center">Admin Panel</h4>
        <div class="list-group list-group-flush mt-4">
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
            <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action bg-dark text-white">Orders</a>
            <a href="{{ route('admin.categories') }}" class="list-group-item list-group-item-action bg-dark text-white">Categories</a>
            <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action bg-dark text-white">Products</a>
            <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action bg-dark text-white">Users</a>
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <a class="" id="menu-toggle"></a>
                @if(auth()->user())
                    <span class="navbar-brand ms-3">
                        {{ auth()->user()->roles }} - {{ auth()->user()->name }}
                        <i id="logoutBtn" class="fas fa-sign-out-alt"></i>
                    </span>
                @else
                    <span class="navbar-brand ms-3">Đăng Nhập</span>
                @endif
            </div>
        </nav>

        <div class="container-fluid mt-4">
            @yield('content')
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>

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

@stack('scripts')
</body>
</html>
