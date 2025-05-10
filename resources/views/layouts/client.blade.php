<!DOCTYPE html>
<html lang="en">
@include('layouts.components.head')
<body>
    <div class="bg-white p-0">
        {{-- loading icon --}}
        @include('layouts.components.loading-spint')
        @include('layouts.components.header')
        <!-- Menu Start -->
        <div class="py-5" style="min-height: 600px">
            <div class="container">
                @yield('content')
            </div>
        </div>
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
</body>

</html>
