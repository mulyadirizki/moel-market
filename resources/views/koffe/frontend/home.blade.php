@extends('koffe.frontend.default')

@section('content')
  <div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('kasir') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="javascript: void(0)">E-commerce</a></li>
              <li class="breadcrumb-item" aria-current="page">Products</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">Products</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- [ sample-page ] start -->
      <div class="ecom-content">
        <div class="card">
          <div class="card-body p-3">
            <div class="d-sm-flex align-items-center">
              <ul class="list-inline me-auto my-1">
                <li class="list-inline-item align-bottom">
                  <a href="#" class="d-xxl-none btn btn-link-secondary" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvas_mail_filter">
                    <i class="ti ti-filter f-16"></i> Filter
                  </a>
                  <a href="#" class="d-none d-xxl-inline-flex btn btn-link-secondary"
                    data-bs-toggle="collapse" data-bs-target="#ecom-filter">
                    <i class="ti ti-filter f-16"></i> Filter
                  </a>
                </li>
                <li class="list-inline-item">
                  <div class="form-search">
                    <i class="ti ti-search"></i>
                    <input type="search" class="form-control" placeholder="Search Products" style="width: 320px;">
                  </div>
                </li>
              </ul>
              <ul class="list-inline ms-auto my-1">
                <a href="{{ route('create.item') }}">
                  <button type="button" class="btn btn-primary">Create Item</button>
                </a>
                <a href="{{ route('manage.category') }}">
                  <button type="button" class="btn btn-primary">Manage Categories</button>
                </a>
                <!-- <li class="list-inline-item">
                  <select class="form-select">
                    <option>Price: High To Low</option>
                    <option>Price: Low To High</option>
                    <option>Popularity</option>
                    <option>Discount</option>
                    <option>Fresh Arrivals</option>
                  </select>
                </li> -->
              </ul>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-3 col-md-6" style="margin-top: -10px;">
            <div class="card product-card">
              <div style="padding: 10px;">
                <div class="row align-items-center">
                  <div class="col-auto p-r-0">
                    <div class="u-img">
                      <img src="https://png.pngtree.com/template/20191104/ourmid/pngtree-kf-letter-logo-image_326979.jpg" alt="user image" class="" style="width: 50px">
                    </div>
                  </div>
                  <div class="col">
                    <a href="{{ route('all.item') }}">
                      <h5 class="m-b-5">All Items</h5>
                    </a>
                  </div>
                  <div class="col-auto">
                    <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @foreach($category as $ct)
            <div class="col-xl-3 col-md-6" style="margin-top: -10px;">
              <div class="card product-card">
                <div style="padding: 10px;">
                  <div class="row align-items-center">
                    <div class="col-auto p-r-0">
                      <div class="u-img">
                        <img src="https://png.pngtree.com/template/20191104/ourmid/pngtree-kf-letter-logo-image_326979.jpg" alt="user image" class="" style="width: 50px">
                      </div>
                    </div>
                    <div class="col">
                      <a href="{{ route('category.item', ['id' => $ct->id_category]) }}">
                        <h5 class="m-b-5">{{ $ct->category_name }}</h5>
                      </a>
                    </div>
                    <div class="col-auto">
                      <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <!-- [ sample-page ] end -->
    </div>
      <!-- [ Main Content ] end -->
  </div>
@endsection