@extends('koffe.frontend.default')

@section('content')
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
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
        <div class="col-sm-12">
          <div class="ecom-wrapper">
            <div class="offcanvas-xxl offcanvas-start ecom-offcanvas" tabindex="-1" id="offcanvas_mail_filter">
              <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                  data-bs-target="#offcanvas_mail_filter" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body p-0 sticky-xxl-top">
                <div id="ecom-filter" class="show collapse collapse-horizontal">
                  <div class="ecom-filter">
                    <div class="card">
                      <div class="card-header">
                        <h5>Filter</h5>
                      </div>
                      <div class="scroll-block">
                        <div class="card-body">
                          <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-2">
                              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse"
                                href="#filtercollapse1">
                                <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Gender
                              </a>
                              <div class="collapse show" id="filtercollapse1">
                                <div class="py-3">
                                  <div class="form-check form-check-inline my-2">
                                    <input class="form-check-input" type="checkbox" id="genderfilter1"
                                      value="option1">
                                    <label class="form-check-label" for="genderfilter1">Male</label>
                                  </div>
                                  <div class="form-check form-check-inline my-2">
                                    <input class="form-check-input" type="checkbox" id="genderfilter2"
                                      value="option2">
                                    <label class="form-check-label" for="genderfilter2">Female</label>
                                  </div>
                                  <div class="form-check form-check-inline my-2">
                                    <input class="form-check-input" type="checkbox" id="genderfilter3"
                                      value="option3">
                                    <label class="form-check-label" for="genderfilter3">Kids</label>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0 py-2">
                              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse"
                                href="#filtercollapse2">
                                <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Categories
                              </a>
                              <div class="collapse show" id="filtercollapse2">
                                <div class="row py-3">
                                  <div class="col-6">
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="checkbox" id="categoryfilter1"
                                        value="option1">
                                      <label class="form-check-label" for="categoryfilter1">All</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="checkbox" id="categoryfilter2"
                                        value="option2">
                                      <label class="form-check-label" for="categoryfilter2">Electronics</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="checkbox" id="categoryfilter3"
                                        value="option3">
                                      <label class="form-check-label" for="categoryfilter3">Fashion</label>
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="checkbox" id="categoryfilter4"
                                        value="option1">
                                      <label class="form-check-label" for="categoryfilter4">Kitchen</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="checkbox" id="categoryfilter5"
                                        value="option2">
                                      <label class="form-check-label" for="categoryfilter5">Books</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="checkbox" id="categoryfilter6"
                                        value="option3">
                                      <label class="form-check-label" for="categoryfilter6">Toys</label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0 py-2">
                              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse"
                                href="#filtercollapse3">
                                <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Colors
                              </a>
                              <div class="collapse show" id="filtercollapse3">
                                <div class="py-3">
                                  <div class="form-check form-check-inline color-checkbox">
                                    <input class="form-check-input" type="checkbox">
                                    <i class="fas fa-circle text-primary"></i>
                                  </div>
                                  <div class="form-check form-check-inline color-checkbox">
                                    <input class="form-check-input" type="checkbox">
                                    <i class="fas fa-circle text-secondary"></i>
                                  </div>
                                  <div class="form-check form-check-inline color-checkbox">
                                    <input class="form-check-input" type="checkbox">
                                    <i class="fas fa-circle text-danger"></i>
                                  </div>
                                  <div class="form-check form-check-inline color-checkbox">
                                    <input class="form-check-input" type="checkbox">
                                    <i class="fas fa-circle text-success"></i>
                                  </div>
                                  <div class="form-check form-check-inline color-checkbox">
                                    <input class="form-check-input" type="checkbox">
                                    <i class="fas fa-circle text-warning"></i>
                                  </div>
                                  <div class="form-check form-check-inline color-checkbox">
                                    <input class="form-check-input" type="checkbox">
                                    <i class="fas fa-circle text-info"></i>
                                  </div>
                                  <div class="form-check form-check-inline color-checkbox">
                                    <input class="form-check-input" type="checkbox">
                                    <i class="fas fa-circle text-dark"></i>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0 py-2">
                              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse"
                                href="#filtercollapse4">
                                <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Price
                              </a>
                              <div class="collapse show" id="filtercollapse4">
                                <div class="row py-3">
                                  <div class="col-6">
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="radio" name="price" id="pricefilter1"
                                        value="option1">
                                      <label class="form-check-label" for="pricefilter1">Below $10</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="radio" name="price" id="pricefilter2"
                                        value="option2">
                                      <label class="form-check-label" for="pricefilter2">$50 - $100</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="radio" name="price" id="pricefilter3"
                                        value="option3">
                                      <label class="form-check-label" for="pricefilter3">$150 - $200</label>
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="radio" name="price" id="pricefilter4"
                                        value="option1">
                                      <label class="form-check-label" for="pricefilter4">$10 - $50</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="radio" name="price" id="pricefilter5"
                                        value="option2">
                                      <label class="form-check-label" for="pricefilter5">$100 - $150</label>
                                    </div>
                                    <div class="form-check my-2">
                                      <input class="form-check-input" type="radio" name="price" id="pricefilter6"
                                        value="option3">
                                      <label class="form-check-label" for="pricefilter6">Over $200</label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0 py-2">
                              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse"
                                href="#filtercollapse5">
                                <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Rating
                              </a>
                              <div class="collapse show" id="filtercollapse5">
                                <div class="py-3">
                                  <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="chkratt1">
                                    <label class="form-check-label" for="chkratt1">4★ &amp; above</label>
                                  </div>
                                  <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="chkratt2">
                                    <label class="form-check-label" for="chkratt2">3★ &amp; above</label>
                                  </div>
                                  <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="chkratt3">
                                    <label class="form-check-label" for="chkratt3">2★ &amp; above</label>
                                  </div>
                                  <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="chkratt4">
                                    <label class="form-check-label" for="chkratt4">1★ &amp; above</label>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item px-0 py-2">
                              <a href="#" class="btn btn-light-danger w-100">Clear All</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
                          <input type="search" class="form-control" placeholder="Search Products">
                        </div>
                      </li>
                    </ul>
                    <ul class="list-inline ms-auto my-1">
                      <li class="list-inline-item">
                        <select class="form-select">
                          <option>Price: High To Low</option>
                          <option>Price: Low To High</option>
                          <option>Popularity</option>
                          <option>Discount</option>
                          <option>Fresh Arrivals</option>
                        </select>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-3 col-md-6">
                  <div class="card product-card">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto p-r-0">
                          <div class="u-img">
                            <img src="../assets/images/user/avatar-4.jpg" alt="user image" class="" style="width: 50px">
                          </div>
                        </div>
                        <div class="col">
                          <a href="#!">
                            <h5 class="m-b-5">David Jones</h5>
                          </a>
                        </div>
                        <div class="col-auto">
                          <p class="text-muted m-b-0">Rp. 20.000</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card product-card">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto p-r-0">
                          <div class="u-img">
                            <img src="../assets/images/user/avatar-4.jpg" alt="user image" class="" style="width: 50px">
                          </div>
                        </div>
                        <div class="col">
                          <a href="#!">
                            <h5 class="m-b-5">David Jones</h5>
                          </a>
                        </div>
                        <div class="col-auto">
                          <p class="text-muted m-b-0">Rp. 20.000</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card product-card">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto p-r-0">
                          <div class="u-img">
                            <img src="../assets/images/user/avatar-4.jpg" alt="user image" class="" style="width: 50px">
                          </div>
                        </div>
                        <div class="col">
                          <a href="#!">
                            <h5 class="m-b-5">David Jones</h5>
                          </a>
                        </div>
                        <div class="col-auto">
                          <p class="text-muted m-b-0">Rp. 20.000</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-6">
                  <div class="card product-card">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto p-r-0">
                          <div class="u-img">
                            <img src="../assets/images/user/avatar-4.jpg" alt="user image" class="" style="width: 50px">
                          </div>
                        </div>
                        <div class="col">
                          <a href="#!">
                            <h5 class="m-b-5">David Jones</h5>
                          </a>
                        </div>
                        <div class="col-auto">
                          <p class="text-muted m-b-0">Rp. 20.000</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ sample-page ] end -->
      </div>
      <!-- [ Main Content ] end -->
    </div>
    @endsection