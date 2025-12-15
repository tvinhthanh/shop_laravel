@extends('layouts.admin')

@section('title', 'Chi tiết Sản phẩm')
@section('page-title', 'Chi tiết Sản phẩm')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin Sản phẩm</h5>
            </div>
            <div class="card-body">
                @if($product->thumbnail)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                             alt="{{ $product->name }}" 
                             style="max-width: 300px; border-radius: 10px;">
                    </div>
                @endif
                
                <table class="table">
                    <tr>
                        <th width="200">ID:</th>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên sản phẩm:</th>
                        <td><strong>{{ $product->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Danh mục:</th>
                        <td><span class="badge bg-secondary">{{ $product->category->name ?? 'N/A' }}</span></td>
                    </tr>
                    <tr>
                        <th>Giá:</th>
                        <td><strong class="text-primary">{{ number_format($product->price, 0, ',', '.') }}đ</strong></td>
                    </tr>
                    <tr>
                        <th>Tồn kho:</th>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Mô tả:</th>
                        <td>{{ $product->description ?? 'Chưa có mô tả' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

