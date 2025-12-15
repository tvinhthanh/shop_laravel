<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Danh sách khách hàng (user thường, không bao gồm admin)
     */
    public function index()
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->withSum('orders as total_spent', 'total_price')
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Chi tiết khách hàng + lịch sử đơn hàng
     */
    public function show(User $customer)
    {
        if ($customer->is_admin) {
            abort(404);
        }

        $orders = $customer->orders()
            ->with('items.product')
            ->latest()
            ->paginate(20);

        return view('admin.customers.show', compact('customer', 'orders'));
    }
}


