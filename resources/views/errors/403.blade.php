<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Không có quyền truy cập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }
        .error-icon {
            font-size: 80px;
            color: #f5576c;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-shield-exclamation"></i>
        </div>
        <h1 class="error-code">403</h1>
        <h2 class="mb-4">Không có quyền truy cập</h2>
        <p class="text-muted mb-4">
            Bạn không có quyền truy cập trang này. Chỉ quản trị viên mới có thể truy cập khu vực quản trị.
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('shop.home') }}" class="btn btn-primary">
                <i class="bi bi-house"></i> Về trang chủ
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                </a>
            @endauth
        </div>
    </div>
</body>
</html>

