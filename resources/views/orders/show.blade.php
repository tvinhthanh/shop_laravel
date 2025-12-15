@extends('layouts.app')

@section('content')

<div class="order-detail-page">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-2" style="color: #5a4636;">
                    <i class="bi bi-receipt-cutoff"></i> Chi tiết đơn hàng #{{ $order->id }}
                </h2>
                <p class="text-muted mb-0">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row g-4">
            <!-- Left Column: Order Timeline -->
            <div class="col-lg-8">
                <!-- Order Status Timeline -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                            <i class="bi bi-clock-history"></i> Trạng thái đơn hàng
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $statuses = [
                                'pending' => [
                                    'label' => 'Đơn hàng đã đặt',
                                    'icon' => 'bi-cart-check',
                                    'description' => 'Đơn hàng của bạn đã được tiếp nhận'
                                ],
                                'processing' => [
                                    'label' => 'Đang xử lý',
                                    'icon' => 'bi-gear',
                                    'description' => 'Đơn hàng đang được chuẩn bị'
                                ],
                                'shipping' => [
                                    'label' => 'Đang giao hàng',
                                    'icon' => 'bi-truck',
                                    'description' => 'Đơn hàng đang trên đường đến bạn'
                                ],
                                'completed' => [
                                    'label' => 'Hoàn thành',
                                    'icon' => 'bi-check-circle',
                                    'description' => 'Đơn hàng đã được giao thành công'
                                ],
                                'cancelled' => [
                                    'label' => 'Đã hủy',
                                    'icon' => 'bi-x-circle',
                                    'description' => 'Đơn hàng đã bị hủy'
                                ]
                            ];

                            $currentStatus = $order->status;
                            $statusOrder = ['pending', 'processing', 'shipping', 'completed'];
                            $currentIndex = array_search($currentStatus, $statusOrder);
                            if ($currentIndex === false) $currentIndex = -1;
                        @endphp

                        <div class="timeline">
                            @foreach($statusOrder as $index => $status)
                                @php
                                    $isActive = $index <= $currentIndex;
                                    $isCurrent = $index === $currentIndex;
                                    $statusInfo = $statuses[$status] ?? [];
                                @endphp
                                
                                <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="bi {{ $statusInfo['icon'] ?? 'bi-circle' }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1" style="color: #5a4636; font-weight: 600;">
                                            {{ $statusInfo['label'] ?? ucfirst($status) }}
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            {{ $statusInfo['description'] ?? '' }}
                                        </p>
                                        @if($isCurrent && $order->updated_at)
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> {{ $order->updated_at->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($index < count($statusOrder) - 1)
                                    <div class="timeline-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                @endif
                            @endforeach

                            @if($currentStatus === 'cancelled')
                                <div class="timeline-item active current cancelled">
                                    <div class="timeline-marker">
                                        <i class="bi bi-x-circle"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1 text-danger" style="font-weight: 600;">
                                            Đơn hàng đã bị hủy
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            Đơn hàng này đã bị hủy
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                            <i class="bi bi-box-seam"></i> Sản phẩm trong đơn
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($order->items->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr style="background: #f8f5f2;">
                                            <th>Sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->thumbnail)
                                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                                                    @endif
                                                    <div>
                                                        <strong style="color: #5a4636;">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td><strong class="text-primary">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</strong></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Tổng cộng:</th>
                                            <th class="text-primary" style="font-size: 1.2rem;">{{ number_format($order->total_price, 0, ',', '.') }}đ</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-4">Không có sản phẩm nào trong đơn hàng</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Info -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                            <i class="bi bi-person"></i> Thông tin khách hàng
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <table class="table table-sm">
                            <tr>
                                <th width="120" style="color: #5a4636;">Họ tên:</th>
                                <td>{{ $order->customer_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $order->customer_email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Điện thoại:</th>
                                <td>{{ $order->customer_phone }}</td>
                            </tr>
                            <tr>
                                <th>Địa chỉ:</th>
                                <td>{{ $order->customer_address }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                            <i class="bi bi-credit-card"></i> Thông tin thanh toán
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <table class="table table-sm">
                            <tr>
                                <th width="120" style="color: #5a4636;">Phương thức:</th>
                                <td>
                                    @if($order->payment_method === 'card')
                                        <span class="badge" style="background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%); color: white;">
                                            <i class="bi bi-credit-card"></i> Thẻ tín dụng
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-cash-coin"></i> COD
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tổng tiền:</th>
                                <td><strong class="text-primary">{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
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
                                    <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #f8f5f2 0%, #fffdf9 100%);">
                    <div class="card-body p-4">
                        <h6 class="mb-3" style="color: #5a4636; font-weight: 600;">Tóm tắt đơn hàng</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số lượng sản phẩm:</span>
                            <strong>{{ $order->items->sum('quantity') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ngày đặt:</span>
                            <strong>{{ $order->created_at->format('d/m/Y') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong style="color: #5a4636;">Tổng thanh toán:</strong>
                            <strong class="text-primary" style="font-size: 1.3rem;">{{ number_format($order->total_price, 0, ',', '.') }}đ</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
        position: relative;
    }

    .timeline-item.active .timeline-marker {
        background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%);
        color: white;
        border-color: #ffb3d9;
    }

    .timeline-item.current .timeline-marker {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-color: #4facfe;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.2);
        animation: pulse 2s infinite;
    }

    .timeline-item.cancelled .timeline-marker {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .timeline-marker {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e9ecef;
        border: 3px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #6c757d;
        flex-shrink: 0;
        transition: all 0.3s;
        z-index: 2;
    }

    .timeline-content {
        margin-left: 20px;
        flex: 1;
        padding-top: 8px;
    }

    .timeline-line {
        width: 3px;
        height: 40px;
        background: #dee2e6;
        margin-left: 23.5px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }

    .timeline-line.active {
        background: linear-gradient(180deg, #ffb3d9 0%, #ff99cc 100%);
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.2);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(79, 172, 254, 0.1);
        }
    }

    .table th {
        color: #5a4636;
        font-weight: 600;
        border-bottom: 2px solid #e6dfd8;
    }
</style>

@endsection

