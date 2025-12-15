@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #5a4636;">
                <i class="bi bi-receipt"></i> Đơn hàng của tôi
            </h2>
            <p class="text-muted mb-0">Xem lại lịch sử mua hàng của bạn tại Handmade Cozy Shop</p>
        </div>
        <a href="{{ route('shop.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if($orders->count() === 0)
        <div class="text-center py-5">
            <i class="bi bi-bag-x" style="font-size: 3rem; color: #ccc;"></i>
            <h5 class="mt-3" style="color: #5a4636;">Bạn chưa có đơn hàng nào</h5>
            <p class="text-muted">Bắt đầu mua sắm những sản phẩm handmade xinh xắn ngay hôm nay!</p>
            <a href="{{ route('shop.home') }}" class="btn btn-primary">
                <i class="bi bi-shop"></i> Xem sản phẩm
            </a>
        </div>
    @else
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr style="background-color: #f8f5f2;">
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Số sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipping' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipping' => 'Đang giao hàng',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    $color = $statusColors[$order->status] ?? 'secondary';
                                    $label = $statusLabels[$order->status] ?? $order->status;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $order->items->sum('quantity') }}</td>
                                    <td class="fw-bold text-primary">
                                        {{ number_format($order->total_price, 0, ',', '.') }}đ
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection


