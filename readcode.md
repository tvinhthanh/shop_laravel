# Hướng dẫn Code - Handmade Cozy Shop

Tài liệu này mô tả cấu trúc code và cách hoạt động của hệ thống.

## Tổng quan

Handmade Cozy Shop là ứng dụng e-commerce được xây dựng bằng:
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Bootstrap 5 + Vite
- **Database**: SQLite (mặc định) hoặc MySQL
- **Authentication**: Laravel Breeze

## Cấu trúc Models

### User Model (`app/Models/User.php`)
- **Nguồn**: Laravel Breeze default, đã customize
- **Fillable**: `name`, `email`, `password`, `is_admin`
- **Casts**: `is_admin` → boolean, `email_verified_at` → datetime
- **Methods**:
  - `isAdmin()`: Kiểm tra user có phải admin không
- **Relationships**:
  - `orders()`: hasMany Order

### Product Model (`app/Models/Product.php`)
- **Fillable**: `category_id`, `name`, `slug`, `description`, `price`, `stock`, `thumbnail`, `is_active`
- **Relationships**:
  - `category()`: belongsTo Category
- **Lưu ý**: `is_active` dùng để ẩn/hiện sản phẩm trên frontend

### Category Model (`app/Models/Category.php`)
- **Fillable**: `name`, `slug`
- **Relationships**:
  - `products()`: hasMany Product
- **Lưu ý**: Slug tự động tạo từ name trong controller

### Order Model (`app/Models/Order.php`)
- **Fillable**: `user_id`, `customer_name`, `customer_phone`, `customer_email`, `customer_address`, `total_price`, `status`, `payment_method`
- **Relationships**:
  - `items()`: hasMany OrderItem
- **Status values**: `pending`, `processing`, `shipping`, `completed`, `cancelled`
- **Payment methods**: `card`, `cod`

### OrderItem Model (`app/Models/OrderItem.php`)
- **Fillable**: `order_id`, `product_id`, `quantity`, `price`
- **Relationships**:
  - `order()`: belongsTo Order
  - `product()`: belongsTo Product
- **Lưu ý**: `price` lưu giá tại thời điểm đặt hàng (không phải giá hiện tại)

## Controllers

### Frontend Controllers

#### ShopController (`app/Http/Controllers/ShopController.php`)
**Chức năng**: Xử lý trang shop (frontend)

**Methods**:
- `index()`: 
  - Route: `GET /`
  - Hiển thị tất cả sản phẩm active với pagination (12 items/page)
  - Load categories để hiển thị trong sidebar/footer
  - Return view: `shop.index`

- `category($slug)`: 
  - Route: `GET /category/{slug}`
  - Hiển thị sản phẩm theo category
  - Load tất cả categories và category hiện tại
  - Return view: `shop.category`

- `show($slug)`: 
  - Route: `GET /product/{slug}`
  - Chi tiết sản phẩm
  - Chỉ hiển thị sản phẩm active
  - Return view: `shop.product`

#### CartController (`app/Http/Controllers/CartController.php`)
**Chức năng**: Quản lý giỏ hàng (session-based)

**Methods**:
- `index()`: 
  - Route: `GET /cart`
  - Hiển thị giỏ hàng từ session
  - Return view: `cart.index`

- `add($id)`: 
  - Route: `POST /cart/add/{id}`
  - Thêm sản phẩm vào giỏ hàng
  - Lưu trong session với format: `[product_id => [name, price, quantity, thumbnail]]`
  - Redirect back với success message

- `remove($id)`: 
  - Route: `DELETE /cart/{id}`
  - Xóa sản phẩm khỏi giỏ hàng
  - Redirect back với success message

**Lưu ý**: Giỏ hàng lưu trong session, không lưu database. Mất khi logout hoặc session expire.

#### OrderController (`app/Http/Controllers/OrderController.php`)
**Chức năng**: Xử lý checkout và đơn hàng

**Methods**:
- `checkout()`: 
  - Route: `GET /checkout` (auth required)
  - Kiểm tra giỏ hàng có sản phẩm
  - Return view: `checkout.index`

- `place(Request $request)`: 
  - Route: `POST /checkout` (auth required)
  - Validate: customer info, payment method, card info (nếu chọn card)
  - Tính tổng tiền (subtotal + shipping 30,000đ)
  - Tạo Order và OrderItems trong transaction
  - Xóa giỏ hàng sau khi thành công
  - Redirect về trang chủ với success message

- `show($id)`: 
  - Route: `GET /orders/{id}` (auth required)
  - Chỉ user sở hữu đơn hàng mới xem được
  - Load order với items và products
  - Return view: `orders.show`

#### ProfileController (`app/Http/Controllers/ProfileController.php`)
**Chức năng**: Quản lý profile user
- **Nguồn**: Laravel Breeze default
- Methods: `edit()`, `update()`, `destroy()`

### Auth Controllers (Laravel Breeze)

Tất cả trong `app/Http/Controllers/Auth/`:
- `AuthenticatedSessionController.php`: Login/Logout
- `RegisteredUserController.php`: Register
- `PasswordResetLinkController.php`: Forgot password
- `NewPasswordController.php`: Reset password
- `EmailVerificationPromptController.php`: Verify email
- `EmailVerificationNotificationController.php`: Resend verification
- `ConfirmablePasswordController.php`: Confirm password
- `PasswordController.php`: Update password
- `VerifyEmailController.php`: Verify email handler

### Admin Controllers

#### CategoryController (`app/Http/Controllers/Admin/CategoryController.php`)
**Chức năng**: CRUD đầy đủ cho Categories

**Methods**:
- `index()`: Danh sách categories với số lượng sản phẩm
- `create()`: Form tạo mới
- `store(Request $request)`: 
  - Validate: name (required, unique)
  - Tự động tạo slug từ name
  - Lưu category
- `show(Category $category)`: Chi tiết category và danh sách sản phẩm
- `edit(Category $category)`: Form sửa
- `update(Request $request, Category $category)`: 
  - Validate: name (required, unique except current)
  - Cập nhật name và slug
- `destroy(Category $category)`: 
  - Kiểm tra có sản phẩm không
  - Không cho xóa nếu còn sản phẩm
  - Xóa category

#### ProductController (`app/Http/Controllers/Admin/ProductController.php`)
**Chức năng**: CRUD đầy đủ cho Products

**Methods**:
- `index()`: Danh sách products với category
- `create()`: Form tạo mới với danh sách categories
- `store(Request $request)`: 
  - Validate: category_id, name, price, stock, thumbnail (image, max 2MB, mimes: jpeg,png,jpg,gif,webp)
  - Tự động tạo slug từ name
  - Upload ảnh vào `storage/app/public/products/`
  - Lưu product
- `show(Product $product)`: Chi tiết product
- `edit(Product $product)`: Form sửa với categories
- `update(Request $request, Product $product)`: 
  - Validate tương tự store
  - Xóa ảnh cũ nếu có ảnh mới
  - Cập nhật product
- `destroy(Product $product)`: 
  - Xóa ảnh thumbnail
  - Xóa product

#### OrderController (`app/Http/Controllers/Admin/OrderController.php`)
**Chức năng**: Quản lý đơn hàng cho admin

**Methods**:
- `index()`: Danh sách tất cả đơn hàng với items
- `show(Order $order)`: Chi tiết đơn hàng với form cập nhật status
- `updateStatus(Request $request, Order $order)`: 
  - Validate: status (pending, processing, completed, cancelled)
  - Cập nhật trạng thái đơn hàng

## Routes

### Routes File (`routes/web.php`)

File này định nghĩa tất cả web routes của ứng dụng.

### Public Routes

**Không cần đăng nhập**:
- `GET /` → `ShopController@index` (name: `shop.home`)
- `GET /product/{slug}` → `ShopController@show` (name: `shop.product`)
- `GET /category/{slug}` → `ShopController@category` (name: `shop.category`)
- `GET /cart` → `CartController@index` (name: `cart.index`)
- `POST /cart/add/{id}` → `CartController@add` (name: `cart.add`)
- `DELETE /cart/{id}` → `CartController@remove` (name: `cart.remove`)

### Auth Required Routes

**Cần đăng nhập** (`middleware('auth')`):
- `GET /checkout` → `OrderController@checkout` (name: `checkout`)
- `POST /checkout` → `OrderController@place` (name: `checkout.place`)
- `GET /dashboard` → Dashboard view (name: `dashboard`, middleware: `['auth', 'verified']`)
- `GET /orders/{order}` → `OrderController@show` (name: `user.orders.show`)
- `GET /profile` → `ProfileController@edit` (name: `profile.edit`)
- `PATCH /profile` → `ProfileController@update` (name: `profile.update`)
- `DELETE /profile` → `ProfileController@destroy` (name: `profile.destroy`)

### Admin Routes

**Cần đăng nhập + admin** (`middleware(['auth', 'admin'])`, prefix: `admin`):
- `GET /admin` → Admin dashboard (name: `admin.dashboard`)
- `GET /admin/categories` → `CategoryController@index` (name: `categories.index`)
- `GET /admin/categories/create` → `CategoryController@create` (name: `categories.create`)
- `POST /admin/categories` → `CategoryController@store` (name: `categories.store`)
- `GET /admin/categories/{category}` → `CategoryController@show` (name: `categories.show`)
- `GET /admin/categories/{category}/edit` → `CategoryController@edit` (name: `categories.edit`)
- `PUT/PATCH /admin/categories/{category}` → `CategoryController@update` (name: `categories.update`)
- `DELETE /admin/categories/{category}` → `CategoryController@destroy` (name: `categories.destroy`)
- `GET /admin/products` → `ProductController@index` (name: `products.index`)
- `GET /admin/products/create` → `ProductController@create` (name: `products.create`)
- `POST /admin/products` → `ProductController@store` (name: `products.store`)
- `GET /admin/products/{product}` → `ProductController@show` (name: `products.show`)
- `GET /admin/products/{product}/edit` → `ProductController@edit` (name: `products.edit`)
- `PUT/PATCH /admin/products/{product}` → `ProductController@update` (name: `products.update`)
- `DELETE /admin/products/{product}` → `ProductController@destroy` (name: `products.destroy`)
- `GET /admin/orders` → `AdminOrderController@index` (name: `orders.index`)
- `GET /admin/orders/{order}` → `AdminOrderController@show` (name: `orders.show`)
- `POST /admin/orders/{order}/status` → `AdminOrderController@updateStatus` (name: `orders.updateStatus`)

### Auth Routes (Laravel Breeze)

Được import từ `routes/auth.php`:
- `GET /login` → Login page
- `POST /login` → Authenticate
- `POST /logout` → Logout
- `GET /register` → Register page
- `POST /register` → Create account
- `GET /forgot-password` → Forgot password page
- `POST /forgot-password` → Send reset link
- `GET /reset-password/{token}` → Reset password page
- `POST /reset-password` → Update password
- `GET /verify-email` → Email verification page
- `POST /email/verification-notification` → Resend verification

## Middleware

### AdminMiddleware (`app/Http/Middleware/AdminMiddleware.php`)
- **Chức năng**: Bảo vệ admin routes
- **Logic**:
  1. Kiểm tra user đã đăng nhập (redirect về login nếu chưa)
  2. Kiểm tra `is_admin === true` (abort 403 nếu không phải admin)
- **Đăng ký**: `bootstrap/app.php` với alias `admin`
- **Sử dụng**: `Route::middleware(['auth', 'admin'])`

### Auth Middleware (Laravel default)
- Kiểm tra user đã đăng nhập
- Redirect về login nếu chưa đăng nhập

### Verified Middleware (Laravel Breeze)
- Kiểm tra email đã được verify
- Redirect về verify-email page nếu chưa verify

## Views Structure

### Layouts

#### `resources/views/layouts/app.blade.php`
- **Mục đích**: Layout chính cho frontend
- **Features**:
  - Navbar với dropdown user menu
  - Footer 4 cột (Thông tin shop, Danh mục, Trang thông tin, Google Maps)
  - CSS global override cho màu hồng pastel
  - Responsive design
  - Bootstrap Icons integration

#### `resources/views/layouts/admin.blade.php`
- **Mục đích**: Layout cho admin panel
- **Features**:
  - Sidebar navigation cố định bên trái
  - Header với thông tin user
  - Alert messages (success/error)
  - Responsive design với mobile menu

#### `resources/views/layouts/guest.blade.php`
- **Mục đích**: Layout cho trang guest (login, register)
- **Nguồn**: Laravel Breeze default

#### `resources/views/layouts/navigation.blade.php`
- **Mục đích**: Navigation component cho Breeze
- **Nguồn**: Laravel Breeze default

### Shop Views

#### `resources/views/shop/index.blade.php`
- **Route**: `GET /` → `ShopController@index`
- **Chức năng**: Trang chủ
- **Features**:
  - Hero banner với background image
  - Image gallery section (5 hình ảnh)
  - Categories section với cards
  - Featured products banner
  - Products grid với pagination
  - Additional images section
  - Hover effects và animations

#### `resources/views/shop/category.blade.php`
- **Route**: `GET /category/{slug}` → `ShopController@category`
- **Chức năng**: Hiển thị sản phẩm theo danh mục
- **Features**: Product grid với filter theo category

#### `resources/views/shop/product.blade.php`
- **Route**: `GET /product/{slug}` → `ShopController@show`
- **Chức năng**: Chi tiết sản phẩm
- **Features**: Product details, add to cart button

### Admin Views

#### `resources/views/admin/dashboard.blade.php`
- **Route**: `GET /admin` → Admin dashboard
- **Chức năng**: Dashboard admin với thống kê
- **Features**:
  - 4 stats cards (Products, Orders, Categories, Revenue)
  - Recent orders table
  - Gradient cards với icons

#### `resources/views/admin/categories/index.blade.php`
- **Route**: `GET /admin/categories` → `CategoryController@index`
- **Chức năng**: Danh sách categories
- **Features**: Table với CRUD actions

#### `resources/views/admin/categories/create.blade.php`
- **Route**: `GET /admin/categories/create` → `CategoryController@create`
- **Chức năng**: Form tạo category mới

#### `resources/views/admin/categories/edit.blade.php`
- **Route**: `GET /admin/categories/{id}/edit` → `CategoryController@edit`
- **Chức năng**: Form sửa category

#### `resources/views/admin/categories/show.blade.php`
- **Route**: `GET /admin/categories/{id}` → `CategoryController@show`
- **Chức năng**: Chi tiết category và danh sách sản phẩm

#### `resources/views/admin/products/index.blade.php`
- **Route**: `GET /admin/products` → `ProductController@index`
- **Chức năng**: Danh sách products
- **Features**: Table với hình ảnh, giá, stock, status

#### `resources/views/admin/products/create.blade.php`
- **Route**: `GET /admin/products/create` → `ProductController@create`
- **Chức năng**: Form tạo product mới
- **Features**: Upload ảnh, select category, price, stock

#### `resources/views/admin/products/edit.blade.php`
- **Route**: `GET /admin/products/{id}/edit` → `ProductController@edit`
- **Chức năng**: Form sửa product
- **Features**: Preview ảnh hiện tại, update ảnh mới

#### `resources/views/admin/products/show.blade.php`
- **Route**: `GET /admin/products/{id}` → `ProductController@show`
- **Chức năng**: Chi tiết product

#### `resources/views/admin/orders/index.blade.php`
- **Route**: `GET /admin/orders` → `AdminOrderController@index`
- **Chức năng**: Danh sách đơn hàng
- **Features**: Table với status badges

#### `resources/views/admin/orders/show.blade.php`
- **Route**: `GET /admin/orders/{id}` → `AdminOrderController@show`
- **Chức năng**: Chi tiết đơn hàng và cập nhật status
- **Features**: Order items, customer info, status update form

### Cart & Checkout Views

#### `resources/views/cart/index.blade.php`
- **Route**: `GET /cart` → `CartController@index`
- **Chức năng**: Hiển thị giỏ hàng
- **Features**: Danh sách sản phẩm, tổng tiền, button checkout

#### `resources/views/checkout/index.blade.php`
- **Route**: `GET /checkout` → `OrderController@checkout`
- **Chức năng**: Trang thanh toán
- **Features**:
  - Form thông tin khách hàng
  - Form thẻ tín dụng (card preview)
  - Tùy chọn COD
  - Order summary sidebar
  - Auto-format card number và expiry
  - Form validation

### User Views

#### `resources/views/dashboard.blade.php`
- **Route**: `GET /dashboard` → Dashboard view
- **Chức năng**: User dashboard
- **Features**:
  - Welcome section
  - 3 stats cards (Total orders, Processing orders, Total spent)
  - Recent orders table
  - Quick actions
  - Account info

#### `resources/views/orders/show.blade.php`
- **Route**: `GET /orders/{id}` → `OrderController@show`
- **Chức năng**: Chi tiết đơn hàng cho user
- **Features**:
  - Timeline 5 mốc trạng thái (pending → processing → shipping → completed)
  - Order items table
  - Customer info
  - Payment info
  - Order summary

### Auth Views (Laravel Breeze)

- `auth/login.blade.php`: Trang đăng nhập
- `auth/register.blade.php`: Trang đăng ký
- `auth/forgot-password.blade.php`: Quên mật khẩu
- `auth/reset-password.blade.php`: Đặt lại mật khẩu
- `auth/verify-email.blade.php`: Xác thực email
- `auth/confirm-password.blade.php`: Xác nhận mật khẩu

### Profile Views (Laravel Breeze)

- `profile/edit.blade.php`: Chỉnh sửa profile
- `profile/partials/update-profile-information-form.blade.php`: Form cập nhật thông tin
- `profile/partials/update-password-form.blade.php`: Form đổi mật khẩu
- `profile/partials/delete-user-form.blade.php`: Form xóa tài khoản

### Component Views

- `components/application-logo.blade.php`: Logo component
- `components/auth-session-status.blade.php`: Session status
- `components/danger-button.blade.php`: Danger button
- `components/dropdown.blade.php`: Dropdown menu
- `components/input-error.blade.php`: Error message
- `components/input-label.blade.php`: Form label
- `components/modal.blade.php`: Modal component
- `components/nav-link.blade.php`: Navigation link
- `components/primary-button.blade.php`: Primary button
- `components/responsive-nav-link.blade.php`: Responsive nav link
- `components/secondary-button.blade.php`: Secondary button
- `components/text-input.blade.php`: Text input

### Error Views

#### `resources/views/errors/403.blade.php`
- **Chức năng**: Trang lỗi 403 (Forbidden)
- **Features**: Gradient background, links về trang chủ/login

## Database Schema

### Migrations

Tất cả migrations trong `database/migrations/`:

#### Core Migrations (Laravel default)
- `0001_01_01_000000_create_users_table.php`: Tạo bảng users
- `0001_01_01_000001_create_cache_table.php`: Tạo bảng cache
- `0001_01_01_000002_create_jobs_table.php`: Tạo bảng jobs

#### Application Migrations
- `2025_12_05_182359_create_categories_table.php`: 
  - Tạo bảng `categories`
  - Fields: `id`, `name`, `slug`, `timestamps`
  
- `2025_12_05_182400_create_products_table.php`: 
  - Tạo bảng `products`
  - Fields: `id`, `category_id` (foreign key), `name`, `slug`, `description` (text), `price` (decimal), `stock` (integer), `thumbnail` (string), `is_active` (boolean, default true), `timestamps`
  
- `2025_12_05_182401_create_orders_table.php`: 
  - Tạo bảng `orders`
  - Fields: `id`, `user_id` (foreign key, nullable), `customer_name`, `customer_phone`, `customer_email`, `customer_address` (text), `total_price` (decimal), `status` (string, default 'pending'), `timestamps`
  
- `2025_12_05_182402_create_order_items_table.php`: 
  - Tạo bảng `order_items`
  - Fields: `id`, `order_id` (foreign key), `product_id` (foreign key), `quantity` (integer), `price` (decimal), `timestamps`

- `2025_12_05_190814_add_is_admin_to_users_table.php`: 
  - Thêm column `is_admin` (boolean, default false) vào bảng `users`

- `2025_12_05_193332_add_payment_method_to_orders_table.php`: 
  - Thêm column `payment_method` (string, default 'card') vào bảng `orders`

**Lưu ý**: Migration `2025_12_05_183133_create_order_items_table.php` có vẻ là duplicate, có thể bỏ qua.

### Database Tables

#### users
- `id` (bigint, primary key)
- `name` (string)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `is_admin` (boolean, default false)
- `remember_token` (string, nullable)
- `created_at`, `updated_at` (timestamps)

#### categories
- `id` (bigint, primary key)
- `name` (string)
- `slug` (string, unique)
- `created_at`, `updated_at` (timestamps)

#### products
- `id` (bigint, primary key)
- `category_id` (bigint, foreign key → categories.id)
- `name` (string)
- `slug` (string, unique)
- `description` (text, nullable)
- `price` (decimal 10,2)
- `stock` (integer, default 0)
- `thumbnail` (string, nullable)
- `is_active` (boolean, default true)
- `created_at`, `updated_at` (timestamps)

#### orders
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key → users.id, nullable)
- `customer_name` (string)
- `customer_phone` (string)
- `customer_email` (string)
- `customer_address` (text)
- `total_price` (decimal 10,2)
- `status` (string, default 'pending')
- `payment_method` (string, default 'card')
- `created_at`, `updated_at` (timestamps)

#### order_items
- `id` (bigint, primary key)
- `order_id` (bigint, foreign key → orders.id)
- `product_id` (bigint, foreign key → products.id)
- `quantity` (integer)
- `price` (decimal 10,2)
- `created_at`, `updated_at` (timestamps)

## Seeders

### DatabaseSeeder (`database/seeders/DatabaseSeeder.php`)
**Chức năng**: Tạo dữ liệu mẫu khi chạy `php artisan db:seed`

**Tạo users**:
1. **Test User**:
   - Email: `test@example.com`
   - Password: (từ UserFactory, mặc định)
   - `is_admin`: false

2. **Admin User**:
   - Name: `Admin`
   - Email: `admin@handmade.com`
   - Password: `admin123`
   - `is_admin`: true
   - `email_verified_at`: now()

**Lưu ý**: Categories, Products, Orders cần tạo thủ công qua admin panel hoặc tạo thêm seeders.

## Authentication & Authorization

### Authentication
- Sử dụng Laravel Breeze
- Routes: `/login`, `/register`, `/logout`
- Middleware: `auth`, `verified`

### Authorization
- Admin routes bảo vệ bởi `AdminMiddleware`
- Kiểm tra `is_admin` field trong User model
- User thường chỉ xem được đơn hàng của chính mình

## Session & Cart

### Cart Storage
- Lưu trong session: `session('cart')`
- Format:
```php
[
    product_id => [
        'name' => 'Product Name',
        'price' => 100000,
        'quantity' => 2,
        'thumbnail' => 'path/to/image'
    ]
]
```

## File Upload

### Product Images
- Lưu trong: `storage/app/public/products/`
- Hỗ trợ: JPEG, PNG, JPG, GIF, WebP
- Max size: 2MB
- Tự động xóa ảnh cũ khi update

### Storage Link
- Chạy: `php artisan storage:link`
- Tạo symlink: `public/storage` → `storage/app/public`

## Order Status Flow

1. **pending**: Đơn hàng đã đặt (mặc định)
2. **processing**: Đang xử lý
3. **shipping**: Đang giao hàng
4. **completed**: Hoàn thành
5. **cancelled**: Đã hủy

## Payment Methods

- **card**: Thanh toán bằng thẻ tín dụng/ghi nợ
- **cod**: Thanh toán khi nhận hàng

## Inventory Management

- Mỗi sản phẩm có field `stock` để theo dõi tồn kho.
- Khi đặt hàng:
  - Hệ thống kiểm tra từng sản phẩm trong giỏ, không cho đặt vượt quá số lượng còn lại.
  - Sau khi tạo `Order` và `OrderItem`, `stock` của từng `Product` được trừ tương ứng.
- Admin dashboard và danh sách sản phẩm hiển thị cảnh báo với sản phẩm có tồn kho thấp (ví dụ ≤ 5).

## Coupons & Discounts

- Bảng `coupons` lưu thông tin mã giảm giá:
  - `code`, `type` (`percent` hoặc `fixed`), `value`
  - `min_order_value`, `max_uses`, `used_count`
  - `is_active`, `starts_at`, `expires_at`
- Model `Coupon` cung cấp:
  - `isValidForTotal($subtotal)`: kiểm tra điều kiện áp dụng
  - `calculateDiscount($subtotal)`: tính số tiền giảm
- Tại checkout:
  - User nhập `coupon_code`, hệ thống validate và tính `discount_amount`.
  - Bảng `orders` lưu `subtotal`, `discount_amount`, `coupon_code`, `shipping_fee`, `total_price`.

## Customer & Order Management (bổ sung)

- Người dùng:
  - Trang “Đơn hàng của tôi” (`/my-orders`) hiển thị lịch sử đơn hàng.
  - Trang chi tiết đơn (`/orders/{id}`) có timeline trạng thái: `pending → confirmed → producing → shipping → completed / cancelled`.
- Admin:
  - `CustomerController` hiển thị danh sách khách hàng (không bao gồm admin) với số đơn và tổng chi tiêu.
  - Trang chi tiết khách hàng hiển thị lịch sử đơn hàng của khách.
  - `Admin\OrderController` cho phép cập nhật trạng thái đơn với 6 trạng thái: `pending`, `confirmed`, `producing`, `shipping`, `completed`, `cancelled`.

## Color Scheme

### Primary Colors (Hồng Pastel)
- Light: `#ffb3d9`
- Medium: `#ff99cc`
- Dark: `#ff80bf`

### Background Colors
- Body: `#f8f5f2` (màu kem)
- Cards: `#fffdf9`
- Navbar/Footer: `#d9c7b8` → `#c4a992` (gradient nâu)

### Text Colors
- Primary: `#5a4636` (nâu đậm)
- Secondary: `#6b5a4a` (nâu nhạt)
- Price: `#ff99cc` (hồng pastel)

## JavaScript Features

### Checkout Page
- Auto-format card number (thêm khoảng trắng mỗi 4 số)
- Auto-format expiry date (MM/YY)
- Toggle form thẻ khi chọn COD
- Form validation

### Shop Page
- Hover effects trên product cards
- Image zoom on hover

## Security

### CSRF Protection
- Tất cả POST requests có CSRF token
- Laravel tự động validate

### SQL Injection
- Sử dụng Eloquent ORM (parameterized queries)
- Không dùng raw queries trực tiếp

### XSS Protection
- Blade tự động escape output
- Sử dụng `{!! !!}` chỉ khi cần thiết

### File Upload
- Validate file type và size
- Lưu trong storage, không public trực tiếp

## Configuration Files

### Bootstrap (`bootstrap/app.php`)
- **Chức năng**: Cấu hình ứng dụng Laravel
- **Middleware Registration**: Đăng ký `AdminMiddleware` với alias `admin`
- **Routes**: Load routes từ `routes/web.php` và `routes/console.php`
- **Health Check**: Route `/up` để kiểm tra ứng dụng

### Environment (`.env`)
- **Chức năng**: Cấu hình môi trường
- **Quan trọng**:
  - `APP_ENV`: Môi trường (local, production)
  - `APP_DEBUG`: Bật/tắt debug mode
  - `DB_CONNECTION`: Loại database (sqlite, mysql)
  - `DB_DATABASE`: Tên database
  - `APP_URL`: URL ứng dụng

### Database Config (`config/database.php`)
- Cấu hình kết nối database
- Mặc định: SQLite (`database/database.sqlite`)

### Storage Config (`config/filesystems.php`)
- Cấu hình lưu trữ files
- Public disk: `storage/app/public`
- Symlink: `public/storage` → `storage/app/public`

## Best Practices

### Naming Conventions
- Controllers: PascalCase (e.g., `ProductController`)
- Models: PascalCase, singular (e.g., `Product`)
- Routes: kebab-case (e.g., `shop.product`)
- Views: kebab-case (e.g., `shop/index.blade.php`)

### Code Organization
- Controllers chỉ xử lý logic, không chứa HTML
- Business logic trong Models hoặc Services
- Validation trong Form Requests hoặc Controller
- Views chỉ hiển thị, không có logic phức tạp

### Database
- Sử dụng migrations cho mọi thay đổi schema
- Seeders cho dữ liệu mẫu
- Eloquent relationships thay vì raw queries

## Testing

```bash
# Chạy tests
php artisan test

# Chạy tests với coverage
php artisan test --coverage
```

## Deployment Checklist

1. ✅ Set `APP_ENV=production`
2. ✅ Set `APP_DEBUG=false`
3. ✅ Generate app key: `php artisan key:generate`
4. ✅ Run migrations: `php artisan migrate --force`
5. ✅ Build assets: `npm run build`
6. ✅ Cache config: `php artisan config:cache`
7. ✅ Cache routes: `php artisan route:cache`
8. ✅ Cache views: `php artisan view:cache`
9. ✅ Set proper permissions cho `storage/` và `bootstrap/cache/`
10. ✅ Cấu hình web server (Apache/Nginx)

## API Documentation

Hiện tại hệ thống chỉ có web interface, chưa có REST API.

## Future Improvements

- [ ] Thêm REST API
- [ ] Email notifications
- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Product reviews & ratings
- [ ] Wishlist feature
- [ ] Search & filter products
- [ ] Multi-language support
- [ ] Admin analytics dashboard

