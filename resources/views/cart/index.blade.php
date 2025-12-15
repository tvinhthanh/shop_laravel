@extends('layouts.app')

@section('content')
<h2 class="fw-bold" style="color:#8b5e3c;">Giỏ hàng của bạn</h2>

@if(!$cart)
<p class="text-muted">Giỏ hàng đang trống.</p>
@endif

<table class="table mt-4">
    <thead class="table-light">
        <tr>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @foreach($cart as $id => $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>{{ number_format($item['price']) }} đ</td>
            <td>{{ $item['quantity'] }}</td>
            <td>
                <form method="POST" action="/cart/{{ $id }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">Xoá</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if($cart)
<a href="/checkout" class="btn btn-success">Tiến hành thanh toán</a>
@endif

@endsection
