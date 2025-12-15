# Handmade Cozy Shop

Hệ thống bán hàng handmade online được xây dựng bằng Laravel 12.

## Yêu cầu hệ thống

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.x và npm
-   MySQL 5.7+
-   Extension PHP: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON

## Cài đặt

### Bước 1: Clone repository

```bash
git clone <repository-url>
cd handmade-shop
```

### Bước 2: Cài đặt PHP dependencies

```bash
composer install
```

### Bước 3: Cài đặt Node dependencies

```bash
npm install
```

### Bước 4: Cấu hình môi trường

```bash
# Copy file .env.example thành .env
copy .env.example .env

# Hoặc trên Linux/Mac:
# cp .env.example .env
```

Mở file `.env` và cấu hình:

```env
APP_NAME="Handmade Cozy Shop"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=sqlite
# Hoặc nếu dùng MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=handmade_shop
# DB_USERNAME=root
# DB_PASSWORD=
```

### Bước 5: Tạo Application Key

```bash
php artisan key:generate
```

### Bước 6: Tạo database (nếu dùng SQLite)

```bash
# Tạo file database.sqlite nếu chưa có
touch database/database.sqlite
```

### Bước 7: Chạy migrations

```bash
php artisan migrate
```

### Bước 8: Seed dữ liệu mẫu (tùy chọn)

```bash
php artisan db:seed
```

Sau khi seed, sẽ có 2 tài khoản:

-   **Admin**: `admin@handmade.com` / `admin123`
-   **User**: `test@example.com` / `password`

### Bước 9: Tạo storage link

```bash
php artisan storage:link
```

### Bước 10: Build assets

```bash
npm run build
```

## Chạy ứng dụng

### Development mode

**Cách 1: Chạy riêng lẻ**

Terminal 1 - Laravel server:

```bash
php artisan serve
```

Terminal 2 - Vite dev server (nếu cần hot reload):

```bash
npm run dev
```

**Cách 2: Chạy cùng lúc (recommended)**

```bash
composer run dev
```

Ứng dụng sẽ chạy tại: **http://127.0.0.1:8000**

### Production mode

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Chạy server
php artisan serve
```

## Tài khoản mặc định

Sau khi chạy `php artisan db:seed`:

### Admin

-   Email: `admin@handmade.com`
-   Password: `admin123`
-   Quyền: Admin (có thể truy cập `/admin`)

### User thường

-   Email: `test@example.com`
-   Password: `password`
-   Quyền: User thường

## Tính năng chính

-   **Quản lý sản phẩm**
    -   Thêm / sửa / xóa sản phẩm
    -   Ảnh đại diện, mô tả, giá, tồn kho, trạng thái ẩn/hiện
-   **Quản lý danh mục**
    -   Tạo / sửa / xóa danh mục
    -   Phân loại sản phẩm theo danh mục (mẫu thiết kế)
-   **Giỏ hàng & đặt hàng**
    -   Thêm / xóa sản phẩm trong giỏ (lưu trong session)
    -   Đặt hàng với thông tin khách, địa chỉ giao hàng
-   **Thanh toán**
    -   Thanh toán giả lập bằng thẻ (nhập thông tin thẻ) hoặc COD
-   **Quản lý kho**
    -   Theo dõi tồn kho (`stock`)
    -   Khi đặt hàng tự trừ tồn và chặn đặt vượt quá tồn kho
    -   Cảnh báo sắp hết hàng trong admin (dashboard + danh sách sản phẩm)
-   **Quản lý khách hàng**
    -   Thông tin tài khoản (Laravel Breeze)
    -   Lịch sử đơn hàng của khách tại trang “Đơn hàng của tôi”
    -   Màn hình admin xem danh sách khách hàng + tổng số đơn + tổng chi tiêu
-   **Quản lý đơn hàng**
    -   Luồng trạng thái chi tiết: `pending` → `confirmed` → `producing` → `shipping` → `completed` / `cancelled`
    -   Admin có thể cập nhật trạng thái, xem chi tiết đơn và sản phẩm trong đơn
    -   Khách chỉ xem được các đơn của chính mình
-   **Khuyến mãi / Coupon**
    -   Bảng `coupons` với kiểu giảm giá theo % hoặc số tiền cố định
    -   Nhập mã giảm giá ở trang checkout, hệ thống tự validate và trừ tiền
-   **Báo cáo & dashboard**
    -   Admin dashboard hiển thị tổng số sản phẩm, đơn hàng, danh mục, doanh thu hoàn thành
    -   Bảng đơn hàng gần đây, danh sách sản phẩm sắp hết hàng
-   **Quản lý nhân viên / phân quyền**
    -   Trường `is_admin` trên user
    -   Middleware `admin` bảo vệ toàn bộ khu vực `/admin`

## Cấu trúc thư mục chính

```
handmade-shop/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controllers
│   │   │   ├── Admin/         # Admin controllers
│   │   │   ├── CartController.php
│   │   │   ├── OrderController.php
│   │   │   └── ShopController.php
│   │   └── Middleware/         # Middleware
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/             # Admin views
│   │   ├── shop/              # Shop views
│   │   ├── layouts/           # Layout templates
│   │   └── ...
│   ├── css/                   # CSS files
│   └── js/                    # JavaScript files
├── routes/
│   └── web.php                # Web routes
└── public/                    # Public assets
```

## Các lệnh hữu ích

### Clear cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Database

```bash
# Reset database
php artisan migrate:fresh

# Reset và seed
php artisan migrate:fresh --seed

# Xem trạng thái migrations
php artisan migrate:status
```

### Tạo tài khoản admin mới

```bash
php artisan tinker
>>> App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'is_admin' => true, 'email_verified_at' => now()]);
```

## Troubleshooting

### Lỗi: "SQLSTATE[HY000] [1049] Unknown database"

-   Kiểm tra file `.env` đã cấu hình đúng database chưa
-   Đảm bảo database đã được tạo (nếu dùng MySQL)

### Lỗi: "The stream or file could not be opened"

-   Chạy: `php artisan storage:link`
-   Kiểm tra quyền ghi thư mục `storage/`

### Lỗi: "Class not found"

-   Chạy: `composer dump-autoload`

### Assets không load

-   Chạy: `npm run build`
-   Kiểm tra file `public/build/manifest.json` có tồn tại

## Môi trường Production

1. Đặt `APP_DEBUG=false` trong `.env`
2. Đặt `APP_ENV=production` trong `.env`
3. Chạy `php artisan config:cache`
4. Chạy `php artisan route:cache`
5. Chạy `php artisan view:cache`
6. Build assets: `npm run build`

## Hỗ trợ

Nếu gặp vấn đề, kiểm tra:

-   File log: `storage/logs/laravel.log`
-   Laravel documentation: https://laravel.com/docs
    "# shop_laravel"
