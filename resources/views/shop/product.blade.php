@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6">
        @if($product->thumbnail)
            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                 alt="{{ $product->name }}"
                 class="img-fluid rounded shadow-sm">
        @else
            <div class="d-flex align-items-center justify-content-center" 
                 style="height: 300px; background: #f0f0f0;" >
                <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <h2 class="fw-bold" style="color:#5e4633;">{{ $product->name }}</h2>

        <div class="price mb-3">
            {{ number_format($product->price) }} đ
        </div>

        <form action="/cart/add/{{ $product->id }}" method="POST">
            @csrf
            <button class="btn btn-warning btn-lg">
                Thêm vào giỏ
            </button>
        </form>

        <hr>

        <p style="color:#6d5a4a; font-size: 18px;">
            {{ $product->description }}
        </p>
    </div>
</div>

@endsection
