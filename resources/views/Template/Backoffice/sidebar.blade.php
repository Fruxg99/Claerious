<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon rotate-n-15">
            
        </div>
        <div class="sidebar-brand-text mx-3">Claerious</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="{{ url('/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Toko
    </div>

    <?php 
        if (json_decode($_SESSION["seller"])->id_user == "Admin") {
    ?>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('/category') }}">
                <i class="fas fa-fw fa-layer-group"></i>
                <span>Kategori</span>
            </a>
        </li>

    <?php 
        }
    ?>
    

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <i class="fas fa-fw fa-table"></i>
            <span>Produk</span>
        </a>
        <div id="collapseOne" class="collapse" aria-labelledby="product" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/product') }}">Daftar Produk</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('/transaction') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Transaksi</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('/voucher') }}">
            <i class="fas fa-fw fa-ticket-alt"></i>
            <span>Voucher</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="report" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="buttons.html">Laporan Penjualan</a>
            </div>
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="buttons.html">Laporan Stok</a>
            </div>
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="buttons.html">Laporan Pendapatan</a>
            </div>
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="buttons.html">Laporan Transaksi Seller</a>
            </div>
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="buttons.html">Laporan Voucher</a>
            </div>
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="buttons.html">Laporan User</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

</ul>