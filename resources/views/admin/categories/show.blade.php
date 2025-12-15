@extends('layouts.admin')

@section('title', 'Chi tiết Danh mục')
@section('page-title', 'Chi tiết Danh mục')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin Danh mục</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">ID:</th>
                        <td>{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên danh mục:</th>
                        <td><strong>{{ $category->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $category->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Số sản phẩm:</th>
                        <td><span class="badge bg-info">{{ $category->products->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-box-seam"></i> Sản phẩm trong danh mục</h5>
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <ul class="list-group">
                        @foreach($category->products as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                            <span class="badge bg-primary rounded-pill">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Chưa có sản phẩm nào trong danh mục này</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

