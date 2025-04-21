@extends('layouts.client')

@section('content')
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
        {{-- <h1 class="mb-5">Most Popular Items</h1> --}}
    </div>
    <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
        <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
            @foreach ($categoryAll as $index =>$item)
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-{{ $index + 1 }}">
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
            <div id="tab-1" class="tab-pane fade show p-0 active">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-1.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-2.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-3.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-4.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-5.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-6.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-7.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-8.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane fade show p-0">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-1.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-2.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-3.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-4.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-5.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-6.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-7.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-8.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab-3" class="tab-pane fade show p-0">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-1.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-2.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-3.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-4.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-5.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-6.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-7.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid rounded" src="img/menu-8.jpg" alt="" style="width: 80px;">
                            <div class="w-100 d-flex flex-column text-start ps-4">
                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                    <span>Chicken Burger</span>
                                    <span class="text-primary">$115</span>
                                </h5>
                                <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo diam</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection