<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="text-center">
    <h1 class="text-9xl font-bold text-gray-800">404</h1>
    <p class="text-2xl mt-4 text-gray-600">Xin lỗi, trang bạn tìm không tồn tại.</p>
    <a href="{{ url('/') }}" class="mt-6 inline-block px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 transition">
        Quay về Trang chủ
    </a>
</div>
</body>
</html>
