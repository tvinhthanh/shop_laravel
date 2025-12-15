@extends('layouts.app')

@section('content')

<div class="dashboard-page">
    <!-- Welcome Section -->
    <div class="welcome-section mb-4">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #d9c7b8 0%, #c4a992 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2" style="color: #5a4636;">
                            <i class="bi bi-person-circle"></i> Xin chào, {{ auth()->user()->name }}!
                        </h2>
                        <p class="mb-0" style="color: #6b5a4a;">
                            Chào mừng bạn đến với Handmade Cozy Shop
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                        <a href="{{ route('shop.home') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-shop"></i> Mua sắm ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%); border-radius: 15px;">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Tổng đơn hàng</h6>
                            <h2 class="mb-0">{{ auth()->user()->orders()->count() ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-cart-check" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px;">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Đơn đang xử lý</h6>
                            <h2 class="mb-0">{{ auth()->user()->orders()->whereIn('status', ['pending', 'processing'])->count() ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 15px;">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Tổng chi tiêu</h6>
                            <h2 class="mb-0">{{ number_format(auth()->user()->orders()->where('status', 'completed')->sum('total_price') ?? 0, 0, ',', '.') }}đ</h2>
                        </div>
                        <i class="bi bi-currency-dollar" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                        <i class="bi bi-clock-history"></i> Đơn hàng gần đây
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $orders = auth()->user()->orders()->with('items.product')->latest()->take(5)->get();
                    @endphp
                    
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr style="background: #f8f5f2;">
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $order->items->sum('quantity') }}</span>
                                        </td>
                                        <td><strong class="text-primary">{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Chờ xử lý',
                                                    'processing' => 'Đang xử lý',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy'
                                                ];
                                                $color = $statusColors[$order->status] ?? 'secondary';
                                                $label = $statusLabels[$order->status] ?? $order->status;
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Xem tất cả đơn hàng</a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #c4a992;"></i>
                            <p class="text-muted mt-3">Bạn chưa có đơn hàng nào</p>
                            <a href="{{ route('shop.home') }}" class="btn btn-primary">
                                <i class="bi bi-shop"></i> Mua sắm ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                        <i class="bi bi-lightning-charge"></i> Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('shop.home') }}" class="btn btn-outline-primary">
                            <i class="bi bi-shop"></i> Mua sắm
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-cart"></i> Giỏ hàng
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-info">
                            <i class="bi bi-person"></i> Cập nhật hồ sơ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                        <i class="bi bi-info-circle"></i> Thông tin tài khoản
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <strong style="color: #5a4636;">Email:</strong><br>
                        <span class="text-muted">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="mb-3">
                        <strong style="color: #5a4636;">Thành viên từ:</strong><br>
                        <span class="text-muted">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <strong style="color: #5a4636;">Trạng thái:</strong><br>
                        <span class="badge bg-success">Đang hoạt động</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-page {
        min-height: 60vh;
    }

    .card {
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .table th {
        color: #5a4636;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .welcome-section h2 {
            font-size: 1.5rem;
        }
    }
</style>

@endsection
