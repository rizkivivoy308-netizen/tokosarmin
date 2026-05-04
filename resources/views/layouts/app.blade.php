<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Kelontong')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6fa; }
        #sidebar {
            width: 250px; min-height: 100vh;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            position: fixed; top: 0; left: 0; z-index: 100; transition: all 0.3s;
        }
        #sidebar .brand { padding: 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        #sidebar .brand h5 { color: #fff; font-weight: 700; margin: 0; font-size: 1rem; }
        #sidebar .brand small { color: rgba(255,255,255,0.6); font-size: 0.75rem; }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.75); padding: 10px 16px; border-radius: 8px;
            margin: 2px 8px; font-size: 0.875rem; transition: all 0.2s;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            background: rgba(255,255,255,0.15); color: #fff;
        }
        #sidebar .nav-link i { width: 20px; margin-right: 8px; }
        #sidebar .nav-section {
            color: rgba(255,255,255,0.4); font-size: 0.7rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px; padding: 12px 24px 4px;
        }
        #main-content { margin-left: 250px; min-height: 100vh; }
        #topbar {
            background: #fff; border-bottom: 1px solid #e9ecef;
            padding: 12px 24px; position: sticky; top: 0; z-index: 99;
        }
        .stat-card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        }
        .table-hover tbody tr:hover { background-color: #f8f9ff; }
        .badge-lunas { background-color: #d1fae5; color: #065f46; }
        .badge-belum { background-color: #fee2e2; color: #991b1b; }
        @media (max-width: 768px) {
            #sidebar { margin-left: -250px; }
            #sidebar.show { margin-left: 0; }
            #main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
    @laravelPWA
</head>
<body>

<nav id="sidebar">
    <div class="brand">
        <h5><i class="bi bi-shop me-2"></i>Toko SARMIN</h5>
        <small>Sistem Manajemen</small>
    </div>
    <div class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <div class="nav-section">Master Data</div>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Pelanggan
        </a>
        <a href="{{ route('barangs.index') }}" class="nav-link {{ request()->routeIs('barangs.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Barang
        </a>
        <div class="nav-section">Transaksi</div>
        <a href="{{ route('transaksis.index') }}" class="nav-link {{ request()->routeIs('transaksis.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Penjualan
        </a>
        <a href="{{ route('hutangs.index') }}" class="nav-link {{ request()->routeIs('hutangs.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Hutang
        </a>
        <a href="{{ route('pembayarans.index') }}" class="nav-link {{ request()->routeIs('pembayarans.*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i> Pembayaran
        </a>
        <div class="nav-section">Laporan</div>
        <a href="{{ route('laporan.transaksi') }}" class="nav-link {{ request()->routeIs('laporan.transaksi') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Lap. Transaksi
        </a>
        <a href="{{ route('laporan.hutang') }}" class="nav-link {{ request()->routeIs('laporan.hutang') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-minus"></i> Lap. Hutang
        </a>
        <a href="{{ route('laporan.pembayaran') }}" class="nav-link {{ request()->routeIs('laporan.pembayaran') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-check"></i> Lap. Pembayaran
        </a>
    </div>
</nav>

<div id="main-content">
    <div id="topbar" class="d-flex justify-content-between align-items-center">
        <div>
            <button class="btn btn-sm btn-light d-md-none me-2" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="fw-semibold text-muted">@yield('title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        </div>
    </div>

    <div class="p-4">
        @include('components.alert')
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>
@stack('scripts')
</body>
</html>
