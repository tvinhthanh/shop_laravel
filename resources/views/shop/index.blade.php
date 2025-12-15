@extends('layouts.app')

@section('content')

<!-- Hero Banner Section -->
<div class="hero-banner-section mb-5 position-relative overflow-hidden" style="border-radius: 20px; height: 500px; background: linear-gradient(135deg, rgba(217, 199, 184, 0.9) 0%, rgba(196, 169, 146, 0.9) 100%), url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&q=80') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
        <div class="text-center text-white" style="z-index: 2;">
            <h1 class="display-3 fw-bold mb-3" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">
                Handmade Cozy Collection
            </h1>
            <p class="lead mb-4 fs-4" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.3);">
                Được làm thủ công với tình yêu & cảm hứng từ thiên nhiên
            </p>
            <div class="hero-badges">
                <span class="badge bg-light text-dark me-2 px-4 py-2 fs-6">Tự nhiên</span>
                <span class="badge bg-light text-dark me-2 px-4 py-2 fs-6">Thủ công</span>
                <span class="badge bg-light text-dark px-4 py-2 fs-6">Độc đáo</span>
            </div>
        </div>
    </div>
</div>

<!-- Image Gallery Section -->
<div class="image-gallery-section mb-5">
    <div class="container">
        <h2 class="section-title mb-4 text-center" style="color: #5a4636; font-weight: 600;">
            <i class="bi bi-images"></i> Bộ sưu tập của chúng tôi
        </h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="gallery-item position-relative overflow-hidden rounded-4" style="height: 300px; cursor: pointer;">
                    <img src="https://images.unsplash.com/photo-1607082349566-187342175e2f?w=800&q=80" 
                         alt="Handmade Products" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                         style="background: rgba(90, 70, 54, 0.3); transition: background 0.3s;">
                        <h5 class="text-white fw-bold">Sản phẩm thủ công</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="gallery-item position-relative overflow-hidden rounded-4" style="height: 300px; cursor: pointer;">
                    <img src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=800&q=80" 
                         alt="Natural Materials" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                         style="background: rgba(90, 70, 54, 0.3); transition: background 0.3s;">
                        <h5 class="text-white fw-bold">Nguyên liệu tự nhiên</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="gallery-item position-relative overflow-hidden rounded-4" style="height: 300px; cursor: pointer;">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80" 
                         alt="Cozy Home" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                         style="background: rgba(90, 70, 54, 0.3); transition: background 0.3s;">
                        <h5 class="text-white fw-bold">Không gian ấm cúng</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="gallery-item position-relative overflow-hidden rounded-4" style="height: 250px; cursor: pointer;">
                    <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&q=80" 
                         alt="Handmade Art" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                         style="background: rgba(90, 70, 54, 0.3); transition: background 0.3s;">
                        <h5 class="text-white fw-bold">Nghệ thuật thủ công</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="gallery-item position-relative overflow-hidden rounded-4" style="height: 250px; cursor: pointer;">
                    <img src="https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=800&q=80" 
                         alt="Creative Design" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                         style="background: rgba(90, 70, 54, 0.3); transition: background 0.3s;">
                        <h5 class="text-white fw-bold">Thiết kế sáng tạo</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
@if($categories->count() > 0)
<div class="categories-section mb-5">
    <div class="container">
        <h2 class="section-title mb-4" style="color: #5a4636; font-weight: 600; text-align: center;">
            <i class="bi bi-tags-fill"></i> Danh mục sản phẩm
        </h2>
        <div class="row g-3">
            @foreach($categories as $category)
            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ route('shop.category', $category->slug) }}" class="category-card text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm" style="transition: all 0.3s; background: #fffdf9;">
                        <div class="card-body text-center p-3">
                            <div class="category-icon mb-2" style="font-size: 2rem;">
                                <i class="bi bi-tag"></i>
                            </div>
                            <h6 class="mb-0" style="color: #5a4636; font-size: 0.9rem;">{{ $category->name }}</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Featured Products Banner -->
@if($products->count() > 0)
<div class="featured-banner mb-5 position-relative overflow-hidden rounded-4" style="height: 400px; background: linear-gradient(135deg, rgba(217, 199, 184, 0.8) 0%, rgba(196, 169, 146, 0.8) 100%), url('https://images.unsplash.com/photo-1445205170230-053b83016050?w=1200&q=80') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
        <div class="text-center text-white">
            <h2 class="display-5 fw-bold mb-3" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">
                Sản phẩm được yêu thích nhất
            </h2>
            <p class="lead fs-5" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.3);">
                Khám phá bộ sưu tập độc đáo của chúng tôi
            </p>
        </div>
    </div>
</div>
@endif

<!-- Products Section -->
<div class="products-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0" style="color: #5a4636; font-weight: 600;">
                <i class="bi bi-star-fill"></i> Sản phẩm nổi bật
            </h2>
            <span class="text-muted">{{ $products->total() }} sản phẩm</span>
        </div>

        @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card-wrapper">
                        <div class="product-card h-100">
                            <a href="{{ route('shop.product', $product->slug) }}" class="text-decoration-none">
                                <div class="product-image-wrapper position-relative overflow-hidden" style="border-radius: 15px 15px 0 0; background: #f8f5f2;">
                                    @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                             alt="{{ $product->name }}" 
                                             class="product-image w-100"
                                             style="height: 250px; object-fit: cover; transition: transform 0.5s;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="height: 250px; background: #e6dfd8;">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #c4a992;"></i>
                                        </div>
                                    @endif
                                    @if($product->stock == 0)
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-danger">Hết hàng</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-info p-3" style="background: #fffdf9;">
                                    <h5 class="product-name mb-2" style="color: #5a4636; font-size: 1rem; font-weight: 600; min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $product->name }}
                                    </h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="price" style="color: #ff99cc; font-size: 1.3rem; font-weight: bold;">
                                            {{ number_format($product->price, 0, ',', '.') }}đ
                                        </div>
                                        @if($product->stock > 0)
                                            <span class="badge bg-success" style="font-size: 0.75rem;">Còn hàng</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <div class="product-actions p-3" style="background: #fffdf9; border-top: 1px solid #e6dfd8; border-radius: 0 0 15px 15px;">
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-add-cart w-100" 
                                            {{ $product->stock == 0 ? 'disabled' : '' }}
                                            style="background: linear-gradient(135deg, #ffb3d9 0%, #ff99cc 100%); color: white; border: none; border-radius: 8px; padding: 10px; font-weight: 600; transition: all 0.3s;">
                                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #c4a992;"></i>
                <p class="text-muted mt-3" style="font-size: 1.1rem;">Chưa có sản phẩm nào</p>
            </div>
        @endif
    </div>
</div>

<!-- Additional Image Section -->
<div class="additional-images-section mt-5 mb-5">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="image-box rounded-4 overflow-hidden shadow-sm" style="height: 200px;">
                    <img src="https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=600&q=80" 
                         alt="Handmade" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="image-box rounded-4 overflow-hidden shadow-sm" style="height: 200px;">
                    <img src="https://images.unsplash.com/photo-1586075010923-2dd4570fb338?w=600&q=80" 
                         alt="Craft" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="image-box rounded-4 overflow-hidden shadow-sm" style="height: 200px;">
                    <img src="https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=600&q=80" 
                         alt="Design" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="image-box rounded-4 overflow-hidden shadow-sm" style="height: 200px;">
                    <img src="https://images.unsplash.com/photo-1607082349566-187342175e2f?w=600&q=80" 
                         alt="Products" 
                         class="w-100 h-100" 
                         style="object-fit: cover; transition: transform 0.5s;">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hero-banner-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(90, 70, 54, 0.2);
        z-index: 1;
    }

    .gallery-item:hover img {
        transform: scale(1.15);
    }

    .gallery-item:hover .position-absolute {
        background: rgba(90, 70, 54, 0.5) !important;
    }

    .image-box:hover img {
        transform: scale(1.1);
    }

    .category-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }

    .product-card-wrapper {
        height: 100%;
    }

    .product-card {
        background: #fffdf9;
        border: 1px solid #e6dfd8;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        border-color: #c4a992;
    }

    .product-card:hover .product-image {
        transform: scale(1.1);
    }

    .btn-add-cart:hover:not(:disabled) {
        background: linear-gradient(135deg, #ff99cc 0%, #ff80bf 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(198, 121, 66, 0.3);
    }

    .btn-add-cart:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .section-title {
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #ffb3d9, #ff99cc);
        border-radius: 2px;
    }

    .featured-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(90, 70, 54, 0.2);
        z-index: 1;
    }

    @media (max-width: 768px) {
        .hero-banner-section {
            height: 350px !important;
        }
        
        .hero-banner-section h1 {
            font-size: 2rem !important;
        }
        
        .product-image {
            height: 200px !important;
        }

        .gallery-item {
            height: 200px !important;
        }

        .featured-banner {
            height: 300px !important;
        }
    }
</style>

@endsection
