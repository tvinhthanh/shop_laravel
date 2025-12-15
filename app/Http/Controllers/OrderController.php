<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Lịch sử đơn hàng của user hiện tại
     */
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $cart = session('cart', []);

        if (!$cart) {
            return redirect('/')->with('error', 'Giỏ hàng trống');
        }

        return view('checkout.index', compact('cart'));
    }

    public function place(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_email' => 'nullable|email|max:255',
            'coupon_code' => 'nullable|string|max:50',
            'payment_method' => 'required|in:card,cod',
            'card_number' => 'nullable|required_if:payment_method,card',
            'card_name' => 'nullable|required_if:payment_method,card',
            'card_cvv' => 'nullable|required_if:payment_method,card',
            'card_expiry' => 'nullable|required_if:payment_method,card',
        ]);

        DB::beginTransaction();

        try {
            // Validate stock for each item
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $productId => $item) {
                /** @var \App\Models\Product|null $product */
                $product = $products->get($productId);

                if (!$product) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Một sản phẩm trong giỏ hàng không còn tồn tại.');
                }

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', "Sản phẩm {$product->name} chỉ còn {$product->stock} sản phẩm trong kho.");
                }
            }

            $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            $shipping = 30000; // Phí vận chuyển

            $coupon = null;
            $discount = 0;

            if (!empty($data['coupon_code'])) {
                $coupon = Coupon::where('code', strtoupper($data['coupon_code']))->first();

                if ($coupon && $coupon->isValidForTotal($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['coupon_code' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
                }
            }

            $totalPrice = max($subtotal - $discount + $shipping, 0);

            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? auth()->user()->email ?? null,
                'customer_address' => $data['customer_address'],
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'coupon_code' => $coupon?->code,
                'shipping_fee' => $shipping,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($cart as $productId => $item) {
                // Create order items
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Decrease stock
                $product = $products->get($productId);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Tăng số lần sử dụng coupon
            if ($coupon && $discount > 0) {
                $coupon->increment('used_count');
            }

            DB::commit();

            session()->forget('cart');

            $paymentMethod = $data['payment_method'] === 'card' ? 'thẻ tín dụng' : 'COD';
            
            return redirect()->route('shop.home')
                ->with('success', "Đặt hàng thành công! Mã đơn hàng: #{$order->id}. Phương thức thanh toán: {$paymentMethod}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Show order details for user
     */
    public function show($id)
    {
        $order = Order::with('items.product')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
