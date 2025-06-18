<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="../dashboard/index.html" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <!-- <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo"> -->
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-hasmenu">
                    <a href="{{ route('kasir') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-dashboard"></i></span><span
                    class="pc-mtext">Point Of Sale</span><span class="pc-arrow"></span></a>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-chart-arcs"></i></span><span
                    class="pc-mtext">Online Order</span><span class="pc-arrow"></span></a>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="{{ route('activity') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-chart-infographic"></i></span><span
                    class="pc-mtext">Activity</span><span class="pc-arrow"></span></a>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="{{ route('pengeluaran') }}" class="pc-link"><span class="pc-micon"><i class="ti ti ti-arrow-back-up"></i></span><span
                    class="pc-mtext">Pengeluaran</span><span class="pc-arrow"></span></a>
                </li>
                <li class="pc-item"><a href="{{ route('setting') }}" class="pc-link"><span class="pc-micon">
                    <i class="ti ti-brand-chrome"></i></span><span class="pc-mtext">Setting</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>