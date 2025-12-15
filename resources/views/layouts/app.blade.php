<!DOCTYPE html>
<html>
<head>
    <title>Handmade Shop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* Override Bootstrap Primary to Pastel Pink */
        :root {
            --bs-primary: #ffb3d9;
            --bs-primary-rgb: 255, 179, 217;
            --bs-primary-dark: #ff99cc;
            --bs-primary-light: #ffc0e6;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff99cc 0%, #ff80bf 100%);
            color: white;
        }

        .btn-outline-primary {
            border-color: #ffb3d9;
            color: #ff99cc;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%);
            border-color: #ffb3d9;
            color: white;
        }

        .bg-primary {
            background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%) !important;
        }

        .text-primary {
            color: #ff99cc !important;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%) !important;
        }

        .border-primary {
            border-color: #ffb3d9 !important;
        }
        body {
            background-color: #f8f5f2; /* màu kem */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #d9c7b8 0%, #c4a992 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
            color: #5a4636 !important;
            font-size: 1.3rem;
        }

        .navbar-brand:hover {
            color: #8b5e3c !important;
        }

        .nav-link {
            color: #5a4636 !important;
            font-weight: 500;
            transition: all 0.3s;
            padding: 8px 15px !important;
            border-radius: 8px;
            margin: 0 5px;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.3);
            color: #8b5e3c !important;
            transform: translateY(-2px);
        }

        footer {
            margin-top: 80px;
            background: linear-gradient(135deg, #d9c7b8 0%, #c4a992 100%);
            color: #5a4636;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }

        .footer-section {
            padding: 50px 0 30px;
        }

        .footer-title {
            color: #5a4636;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ffb3d9;
        }

        .footer-link {
            color: #6b5a4a;
            text-decoration: none;
            display: block;
            padding: 5px 0;
            transition: all 0.3s;
        }

        .footer-link:hover {
            color: #ff99cc;
            padding-left: 5px;
        }

        .footer-info {
            color: #6b5a4a;
            line-height: 1.8;
        }

        .footer-info i {
            color: #ff99cc;
            margin-right: 8px;
            width: 20px;
        }

        .footer-bottom {
            background: rgba(90, 70, 54, 0.2);
            padding: 20px 0;
            text-align: center;
            color: #5a4636;
        }

        .social-links a {
            color: #6b5a4a;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: all 0.3s;
        }

        .social-links a:hover {
            color: #ff99cc;
            transform: translateY(-3px);
        }

        .footer-map {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>

</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a href="/" class="navbar-brand">
            <i class="bi bi-shop"></i> Handmade Cozy Shop
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.home') }}">
                        <i class="bi bi-house"></i> Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart"></i> Giỏ hàng
                        @auth
                            @php
                                $cartCount = session('cart', []);
                                $totalItems = array_sum(array_column($cartCount, 'quantity'));
                            @endphp
                            @if($totalItems > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                    {{ $totalItems }}
                                </span>
                            @endif
                        @endauth
                    </a>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.orders.index') }}"><i class="bi bi-receipt"></i> Đơn hàng của tôi</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Hồ sơ</a></li>
                            @if(auth()->user()->is_admin)
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i> Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> Đăng ký
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<footer>
    <div class="footer-section">
        <div class="container">
            <div class="row g-4">
                <!-- Cột 1: Thông tin Shop -->
                <div class="col-md-6 col-lg-3">
                    <h5 class="footer-title">
                        <i class="bi bi-shop"></i> Về chúng tôi
                    </h5>
                    <div class="footer-info">
                        <p>
                            <i class="bi bi-shop"></i>
                            <strong>Handmade Cozy Shop</strong>
                        </p>
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            123 Đường ABC, Quận XYZ<br>
                            Thành phố Hồ Chí Minh, Việt Nam
                        </p>
                        <p>
                            <i class="bi bi-telephone"></i>
                            <a href="tel:0123456789" class="footer-link d-inline">0123 456 789</a>
                        </p>
                        <p>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:info@handmadecozy.com" class="footer-link d-inline">info@handmadecozy.com</a>
                        </p>
                        <div class="social-links mt-3">
                            <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
                            <a href="#" title="TikTok"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Cột 2: Danh mục -->
                <div class="col-md-6 col-lg-3">
                    <h5 class="footer-title">
                        <i class="bi bi-tags"></i> Danh mục
                    </h5>
                    <div>
                        @php
                            $footerCategories = \App\Models\Category::take(6)->get();
                        @endphp
                        @if($footerCategories->count() > 0)
                            @foreach($footerCategories as $category)
                                <a href="{{ route('shop.category', $category->slug) }}" class="footer-link">
                                    <i class="bi bi-chevron-right"></i> {{ $category->name }}
                                </a>
                            @endforeach
                        @else
                            <a href="{{ route('shop.home') }}" class="footer-link">
                                <i class="bi bi-chevron-right"></i> Tất cả sản phẩm
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Cột 3: Các trang thông tin -->
                <div class="col-md-6 col-lg-3">
                    <h5 class="footer-title">
                        <i class="bi bi-info-circle"></i> Thông tin
                    </h5>
                    <div>
                        <a href="{{ route('shop.home') }}" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Trang chủ
                        </a>
                        <a href="#" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Giới thiệu
                        </a>
                        <a href="#" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Chính sách bảo hành
                        </a>
                        <a href="#" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Chính sách đổi trả
                        </a>
                        <a href="#" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Chính sách vận chuyển
                        </a>
                        <a href="#" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Hướng dẫn mua hàng
                        </a>
                        <a href="#" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Câu hỏi thường gặp
                        </a>
                        <a href="#" class="footer-link">
                            <i class="bi bi-chevron-right"></i> Liên hệ
                        </a>
                    </div>
                </div>

                <!-- Cột 4: Google Maps -->
                <div class="col-md-6 col-lg-3">
                    <h5 class="footer-title">
                        <i class="bi bi-geo-alt-fill"></i> Bản đồ
                    </h5>
                    <div class="footer-map">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.630144355682!2d106.6296553152605!3d10.7629063923305!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752e4fd037f473%3A0x4a0a4d8b8d8d8d8d!2zVHLhuqduIGNobyBUaOG7iyBMw6Jt!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s" 
                            width="100%" 
                            height="200" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <p class="footer-info mt-3" style="font-size: 0.9rem;">
                        <i class="bi bi-clock"></i>
                        Giờ mở cửa: 8:00 - 22:00<br>
                        Tất cả các ngày trong tuần
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start text-center mb-2 mb-md-0">
                    <p class="mb-0">
                        <i class="bi bi-heart-fill text-danger"></i> 
                        © {{ date('Y') }} Handmade Cozy Shop. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <p class="mb-0">
                        Made with <i class="bi bi-heart-fill text-danger"></i> for handmade lovers
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
