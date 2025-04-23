@extends('layouts.client')

@section('content')
    <div class="col-12">
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5" id="table-tab-content">
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
            </div>
            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                    @foreach ($categoryAll as $index => $item)
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 tab-link" id="tab-active-{{ $index + 1 }}" data-bs-toggle="pill" href="#tab-{{ $index + 1 }}" data-category-id="{{ $item->id }}">
                                <i class="{{ $item->icon }} fa-2x text-primary"></i>
                                <div class="ps-3">
                                    <small class="text-body">Danh Mục</small>
                                    <h6 class="mt-n1 mb-0">{{ $item->name }}</h6>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    <div id="loading-icon" class="spinner-border text-primary" style="position: absolute; top:50%; width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="product-list row g-4" id="products-tab-content">
                    
                        <!-- Products will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="cart-container bg-light p-3 rounded shadow-sm">
                <h5 class="text-center text-primary mb-3"><i class="fa fa-shopping-cart me-2"></i>Giỏ hàng</h5>
                <div id="cart-items" class="mb-3" style="max-height: 400px; overflow-y: auto;">
                    
                </div>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Tổng:</span>
                    <span id="cart-total">0₫</span>
                </div>
                <div class="mt-3 d-grid">
                    <button class="btn btn-primary" id="checkout-btn">Thanh toán</button>
                </div>
            </div>
            
        </div>
    </div>

    @include('client.menu.script')
@endsection
