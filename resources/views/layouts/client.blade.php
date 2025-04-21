<!DOCTYPE html>
<html lang="en">
@include('layouts.components.head')
<body>
    <div class="container-xxl bg-white p-0">
        {{-- loading icon --}}
        @include('layouts.components.loading-spint')
        @include('layouts.components.header')
        <!-- Menu Start -->
        <div class="container-xxl py-5">
            <div class="container">
                @yield('content')
            </div>
        </div>
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
</body>

</html>