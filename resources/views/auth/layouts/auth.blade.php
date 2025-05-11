<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ứng dụng Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Roboto', sans-serif;
        }

        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .auth-card {
            width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .auth-card-header {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #007bff;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            font-size: 16px;
        }

        .btn-google, .btn-facebook {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-google {
            background-color: #db4437;
            color: white;
        }

        .btn-facebook {
            background-color: #4267B2;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .text-danger {
            font-size: 12px;
        }

        .auth-card {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .auth-card-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 4px;
            box-shadow: none;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-danger {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .is-invalid {
            border-color: #e74c3c;
        }

        .text-danger {
            font-size: 0.875rem;
            color: #e74c3c;
        }

        #emailError, #passwordError {
            margin-top: 5px;
        }

        /* Thêm hiệu ứng cho button khi hover */
        .btn-primary:hover, .btn-danger:hover {
            opacity: 0.8;
        }

    </style>
</head>
<body>

<div class="auth-container">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
