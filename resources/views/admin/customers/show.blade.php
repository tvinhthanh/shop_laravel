@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')
@section('page-title', 'Chi tiết khách hàng')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person"></i> Thông tin khách hàng
            </div>
            <div class="card-body">
                <h5 class="card-title mb-1">{{ $customer->name }}</h5>
                <p class="text-muted mb-3">{{ $customer->email }}</p>
                <p class="mb-1">
                    <strong>Ngày đăng ký:</strong><br>
                    {{ $customer->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="mb-1">
                    <strong>Số đơn hàng:</strong>
                    <span class="badge bg-primary">{{ $customer->orders()->count() }}</span>
                </p>
                <p class="mb-1">
                    <strong>Tổng chi tiêu:</strong><br>
                    {{ number_format($customer->orders()->sum('total_price'), 0, ',', '.') }}đ
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt"></i> Lịch sử đơn hàng</span>
                <span class="text-muted small">
                    Tổng: {{ $orders->total() }} đơn
                </span>
            </div>
            <div class="card-body">
                @if($orders->count() === 0)
                    <p class="text-muted mb-0">Khách hàng này chưa có đơn hàng nào.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
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
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->items->sum('quantity') }}</td>
                                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        <td>
                                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Xem
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


