<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
{{--            <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action bg-dark text-white">Products</a>--}}
{{--            <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action bg-dark text-white">Users</a>--}}
{{--            <a href="{{ route('admin.settings') }}" class="list-group-item list-group-item-action bg-dark text-white">Settings</a>--}}
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">Categories</a>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">Products</a>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">Users</a>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">Settings</a>
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-dark" id="menu-toggle">☰</button>
                <span class="navbar-brand ms-3">Admin Dashboard</span>
            </div>
        </nav>

        <div class="container-fluid mt-4">
            @yield('content')
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>

@stack('scripts')
</body>
</html>
