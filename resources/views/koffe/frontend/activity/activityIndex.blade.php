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
                            <li class="breadcrumb-item" aria-current="page">Activity</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Activity</h2>
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
                        </div>
                    </div>
                </div>
                <div class="row" id="data-activity">
                    @foreach ($groupedData as $tanggal => $transactions)
                        <div class="col-xl-3 col-md-6" style="margin-top: -10px;">
                            <div class="card product-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5>{{ $tanggal }}</h5>
                                    <h4 style="font-weight: bold; font-size: 18px;">Total Rp. {{ number_format(array_sum(array_column($transactions, 'total')), 0, ',', '.') }}</h4>
                                </div>
                                @foreach($transactions as $trans)
                                    @if($trans->status == '2')
                                        <a href="{{ route('activity.detail', ['id' => $trans->id_penjualan]) }}">
                                            <div style="padding: 10px; background-color: salmon;">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                            <h5 class="m-b-5" style="color: white">Rp. {{ number_format($trans->total, 0, ',', '.') }}</h5>
                                                            <span style="color: white">{{ $trans->item_names }}</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="pc-arrow">{{ $trans->jam_nota }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <hr>
                                    @else
                                        <a href="{{ route('activity.detail', ['id' => $trans->id_penjualan]) }}">
                                            <div style="padding: 10px;">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <a href="">
                                                            <h5 class="m-b-5">Rp. {{ number_format($trans->total, 0, ',', '.') }}</h5>
                                                            <span>{{ $trans->item_names }}</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="pc-arrow">{{ $trans->jam_nota }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        <a href="">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
        <!-- [ sample-page ] end -->
        </div>
      <!-- [ Main Content ] end -->
  </div>
@endsection
@push('script')
    <script>

        getAllActivity = () => {
            $.get("{{ route('activity') }}", function (items) {
                console.log(items)
            });
        }

        $(document).ready(function() {
            getAllActivity();
        });
    </script>
@endpush