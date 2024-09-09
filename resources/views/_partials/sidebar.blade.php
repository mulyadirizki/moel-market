<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('admin') }}" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ url('assets/images/img-navbar-card.png') }}" class="img-fluid logo-lg" alt="logo">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item">
                    <a href="{{ route('admin') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>
                <!-- {{ $globalUser->bisnis_id }} -->
                <li class="pc-item pc-caption">
                    <label>Components</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <?php if ($globalUser->bisnis_id == 1) { ?>
                    <?php if($globalUser->roles == 1) { ?>
                        <li class="pc-item pc-hasmenu">
                            <a href="#" class="pc-link"><span class="pc-micon"><i class="ti ti-menu"></i></span><span class="pc-mtext">Master Data</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('karyawan.data') }}">Data Karyawan</a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('data.satuan') }}">Data Satuan</a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('data.kategori') }}">Data Kategori</a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('data.supplier') }}">Data Supplier</a>
                                </li>
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#" class="pc-link"><span class="pc-micon"><i class="ti ti-server"></i></span><span class="pc-mtext">Data Barang</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('data.barang') }}">List Barang</a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('data.barang.masuk') }}">Barang Masuk</a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('data.stok.barang') }}">Stok Barang</a>
                                </li>
                            </ul>
                        </li>
                    <?php } else if($globalUser->roles == 3) { ?>
                        <li class="pc-item pc-hasmenu">
                            <a href="#" class="pc-link"><span class="pc-micon"><i class="ti ti-shopping-cart"></i></span><span class="pc-mtext">Laporan Penjualan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('manajemen.penjualan') }}">Data Penjualan Selesai</a>
                                </li>
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#" class="pc-link"><span class="pc-micon"><i class="ti ti-database"></i></span><span class="pc-mtext">Data Stok</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('manajemen.stok.barang') }}">Data Stok Barang</a>
                                </li>
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#" class="pc-link"><span class="pc-micon"><i class="ti ti-timer"></i></span><span class="pc-mtext">Laporan Pendapatan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('manajemen.laba.pendapatan') }}">Data Laba Pendapatan</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
                <?php } else if ($globalUser->bisnis_id == 2) { ?>
                    <li class="pc-item">
                        <a href="{{ route('karyawan.data') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext">Data Karyawan </span>
                        </a>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-shopping-cart"></i></span><span class="pc-mtext">Penjualan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('penjualan.butuh.dibayarkan') }}">Butuh Dibayarkan</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('penjualan.selesai') }}">Selesai</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('penjualan.refund') }}">Dibatalkan</a></li>
                        </ul>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('data.pembelian') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-edit"></i></span>
                            <span class="pc-mtext">Pembelian</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('data.pendapatan') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-credit-card"></i></span>
                            <span class="pc-mtext">Pendapatan</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>