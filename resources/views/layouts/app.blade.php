<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serbaguna Produk - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px; height: 100vh;">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2" style="height: 40px;">
                <span class="fs-4">Serbaguna Produk</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" class="nav-link text-white {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i> Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('finance.index') }}" class="nav-link text-white {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack me-2"></i> Keuangan
                    </a>
                </li>
                <li>
                    <a href="{{ route('notifications.index') }}" class="nav-link text-white {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell me-2"></i> Notifikasi
                        @if(auth()->check() && $unreadCount = \App\Models\Notification::where('read', false)->count())
                            <span class="badge bg-danger rounded-pill ms-2">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('calculator.index') }}" class="nav-link text-white {{ request()->routeIs('calculator.*') ? 'active' : '' }}">
                        <i class="bi bi-calculator me-2"></i> Kalkulator
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>
                    <strong>Admin</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="#" id="themeToggle">Toggle Dark Mode</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Sign out</a></li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1 p-4" style="background-color: var(--bs-body-bg); min-height: 100vh;">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
