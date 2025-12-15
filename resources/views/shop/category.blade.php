@extends('layouts.app')

@section('content')
<h2>{{ $category->name }}</h2>

<div class="row mt-4">
    @foreach($products as $p)
        <div class="col-md-3 mb-4">
            <div class="card">
                @if($p->thumbnail)
                    <img src="{{ asset('storage/' . $p->thumbnail) }}" 
                         alt="{{ $p->name }}" 
                         class="card-img-top"
                         style="height: 220px; object-fit: cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center" 
                         style="height: 220px; background: #f0f0f0;">
                        <i class="bi bi-image" style="font-size: 2rem; color: #ccc;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5>{{ $p->name }}</h5>
                    <strong>{{ number_format($p->price) }} đ</strong>
                    <a href="/product/{{ $p->slug }}" class="btn btn-primary w-100 mt-2">Xem</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{ $products->links() }}
@endsection
