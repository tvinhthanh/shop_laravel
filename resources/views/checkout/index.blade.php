@extends('layouts.app')

@section('content')

<div class="checkout-page">
    <div class="container">
        <h2 class="fw-bold mb-4" style="color: #5a4636;">
            <i class="bi bi-credit-card"></i> Thanh toán đơn hàng
        </h2>

        <form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm">
            @csrf

            <div class="row g-4">
                <!-- Left Column: Customer Info & Payment -->
                <div class="col-lg-8">
                    <!-- Customer Information -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                            <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                                <i class="bi bi-person"></i> Thông tin khách hàng
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="color: #5a4636; font-weight: 500;">
                                        Họ tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="customer_name" 
                                           class="form-control @error('customer_name') is-invalid @enderror" 
                                           value="{{ old('customer_name', auth()->user()->name ?? '') }}" 
                                           required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" style="color: #5a4636; font-weight: 500;">
                                        Số điện thoại <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" 
                                           name="customer_phone" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           value="{{ old('customer_phone') }}" 
                                           required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" style="color: #5a4636; font-weight: 500;">
                                        Email
                                    </label>
                                    <input type="email" 
                                           name="customer_email" 
                                           class="form-control @error('customer_email') is-invalid @enderror" 
                                           value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label" style="color: #5a4636; font-weight: 500;">
                                        Địa chỉ giao hàng <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="customer_address" 
                                              class="form-control @error('customer_address') is-invalid @enderror" 
                                              rows="3" 
                                              required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                            <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                                <i class="bi bi-credit-card-2-front"></i> Phương thức thanh toán
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cardPayment" value="card" checked>
                                    <label class="form-check-label" for="cardPayment" style="color: #5a4636; font-weight: 500;">
                                        <i class="bi bi-credit-card"></i> Thẻ tín dụng/Thẻ ghi nợ
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="codPayment" value="cod">
                                    <label class="form-check-label" for="codPayment" style="color: #5a4636; font-weight: 500;">
                                        <i class="bi bi-cash-coin"></i> Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                            </div>

                            <!-- Card Payment Form -->
                            <div id="cardPaymentForm">
                                <div class="card-preview mb-4" style="background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%); border-radius: 15px; padding: 30px; color: white; position: relative; overflow: hidden;">
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <i class="bi bi-credit-card-2-back" style="font-size: 2rem; opacity: 0.3;"></i>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-white-50 small">Số thẻ</label>
                                        <input type="text" 
                                               name="card_number" 
                                               id="cardNumber"
                                               class="form-control bg-transparent text-white border-white" 
                                               placeholder="1234 5678 9012 3456"
                                               maxlength="19"
                                               style="font-size: 1.2rem; letter-spacing: 2px;">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-8">
                                            <label class="text-white-50 small">Tên chủ thẻ</label>
                                            <input type="text" 
                                                   name="card_name" 
                                                   class="form-control bg-transparent text-white border-white" 
                                                   placeholder="NGUYEN VAN A"
                                                   style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-4">
                                            <label class="text-white-50 small">CVV</label>
                                            <input type="text" 
                                                   name="card_cvv" 
                                                   class="form-control bg-transparent text-white border-white" 
                                                   placeholder="123"
                                                   maxlength="4">
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-2">
                                        <div class="col-6">
                                            <label class="text-white-50 small">Ngày hết hạn</label>
                                            <input type="text" 
                                                   name="card_expiry" 
                                                   class="form-control bg-transparent text-white border-white" 
                                                   placeholder="MM/YY"
                                                   maxlength="5">
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> 
                                    <strong>Thông tin thẻ ảo để test:</strong><br>
                                    Số thẻ: <code>4111 1111 1111 1111</code><br>
                                    CVV: Bất kỳ 3 số<br>
                                    Ngày hết hạn: Bất kỳ (tương lai)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                            <h5 class="mb-0" style="color: #5a4636; font-weight: 600;">
                                <i class="bi bi-receipt"></i> Tóm tắt đơn hàng
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @php
                                $subtotal = 0;
                                foreach($cart as $item) {
                                    $subtotal += $item['price'] * $item['quantity'];
                                }
                                $shipping = 30000; // Phí vận chuyển
                                $discount = 0;
                                $total = $subtotal - $discount + $shipping;
                            @endphp

                            <div class="order-items mb-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($cart as $item)
                                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1" style="color: #5a4636; font-size: 0.9rem;">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Số lượng: {{ $item['quantity'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong style="color: #ff99cc;">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</strong>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="order-summary">
                                <div class="mb-3">
                                    <label class="form-label" style="color: #5a4636; font-weight: 500;">
                                        <i class="bi bi-ticket-perforated"></i> Mã giảm giá
                                    </label>
                                    <div class="input-group">
                                        <input type="text" 
                                               name="coupon_code" 
                                               class="form-control @error('coupon_code') is-invalid @enderror" 
                                               value="{{ old('coupon_code') }}"
                                               placeholder="Nhập mã coupon (nếu có)">
                                        <button class="btn btn-outline-primary" type="button" disabled>
                                            Áp dụng
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        * Tạm thời mã sẽ được áp dụng khi bạn bấm đặt hàng.
                                    </small>
                                    @error('coupon_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span style="color: #5a4636;">Tạm tính:</span>
                                    <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span style="color: #5a4636;">Giảm giá (ước tính):</span>
                                    <span class="text-success">- {{ number_format($discount, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span style="color: #5a4636;">Phí vận chuyển:</span>
                                    <span>{{ number_format($shipping, 0, ',', '.') }}đ</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong style="color: #5a4636; font-size: 1.1rem;">Tổng cộng:</strong>
                                    <strong style="color: #ff99cc; font-size: 1.3rem;">{{ number_format($total, 0, ',', '.') }}đ</strong>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-lg" style="background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%); border: none; border-radius: 10px; padding: 15px; font-weight: 600;">
                                    <i class="bi bi-lock-fill"></i> Hoàn tất đặt hàng
                                </button>

                                <p class="text-center text-muted small mt-3 mb-0">
                                    <i class="bi bi-shield-check"></i> Thanh toán an toàn và bảo mật
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .card-preview input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .card-preview input:focus {
        background: rgba(255, 255, 255, 0.1) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        color: white !important;
    }

    #codPaymentForm {
        display: none;
    }

    .sticky-top {
        position: sticky;
    }
</style>

<script>
    // Format card number
    document.getElementById('cardNumber')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // Format expiry date
    document.querySelector('input[name="card_expiry"]')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Toggle payment method
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const cardForm = document.getElementById('cardPaymentForm');
            if (this.value === 'cod') {
                cardForm.style.display = 'none';
                // Không bắt buộc các field thẻ
                document.querySelectorAll('#cardPaymentForm input').forEach(input => {
                    input.removeAttribute('required');
                });
            } else {
                cardForm.style.display = 'block';
                // Bắt buộc các field thẻ
                document.querySelectorAll('#cardPaymentForm input').forEach(input => {
                    input.setAttribute('required', 'required');
                });
            }
        });
    });

    // Form validation
    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'card') {
            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const cardName = document.querySelector('input[name="card_name"]').value;
            const cardCVV = document.querySelector('input[name="card_cvv"]').value;
            const cardExpiry = document.querySelector('input[name="card_expiry"]').value;

            if (!cardNumber || cardNumber.length < 16) {
                e.preventDefault();
                alert('Vui lòng nhập số thẻ hợp lệ (16 số)');
                return false;
            }

            if (!cardName) {
                e.preventDefault();
                alert('Vui lòng nhập tên chủ thẻ');
                return false;
            }

            if (!cardCVV || cardCVV.length < 3) {
                e.preventDefault();
                alert('Vui lòng nhập CVV hợp lệ');
                return false;
            }

            if (!cardExpiry || cardExpiry.length < 5) {
                e.preventDefault();
                alert('Vui lòng nhập ngày hết hạn hợp lệ (MM/YY)');
                return false;
            }
        }
    });
</script>

@endsection
