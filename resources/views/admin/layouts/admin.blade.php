<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Dashboard') | Chauhan Agro Industries CMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Framework: Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-icons.min.css') }}">

    <!-- Admin Panel Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    @stack('styles')
</head>
<body class="admin-body">

    <!-- ==============================================
         Admin Sidebar
         ============================================== -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <div class="bg-success text-white p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="bi bi-gear-wide-connected fs-5"></i>
            </div>
            <div>
                <h4>Chauhan Agro</h4>
                <small>chauhanagroindustries.com</small>
            </div>
        </div>

        <ul class="admin-menu">
            <li class="admin-menu-header">Overview</li>
            <li class="admin-menu-item">
                <a href="{{ route('admin.dashboard') }}" class="admin-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Content Manager & Super Admin --}}
            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('content-manager'))
                <li class="admin-menu-header">Content Management</li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.pages.index') }}" class="admin-menu-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-richtext"></i>
                        <span>Pages & Sections</span>
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.banners.index') }}" class="admin-menu-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                        <i class="bi bi-images"></i>
                        <span>Hero Banners</span>
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.menus.index') }}" class="admin-menu-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <i class="bi bi-menu-button-wide"></i>
                        <span>Navigation Menus</span>
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.media.index') }}" class="admin-menu-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                        <i class="bi bi-folder2-open"></i>
                        <span>Media Library</span>
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.languages.index') }}" class="admin-menu-link {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                        <i class="bi bi-translate"></i>
                        <span>Languages</span>
                    </a>
                </li>
            @endif

            {{-- Product Manager & Super Admin --}}
            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('product-manager'))
                <li class="admin-menu-header">Machinery Catalogue</li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.categories.index') }}" class="admin-menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-grid"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.products.index') }}" class="admin-menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="bi bi-tractor"></i>
                        <span>Products & Specs</span>
                    </a>
                </li>
            @endif

            {{-- Enquiry Manager & Super Admin --}}
            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('enquiry-manager'))
                <li class="admin-menu-header">Customer CRM</li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.enquiries.index') }}" class="admin-menu-link {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
                        <i class="bi bi-inbox"></i>
                        <span>Enquiry Inbox</span>
                        @php $unreadCount = \App\Models\Enquiry::where('status', 'unread')->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="badge bg-danger admin-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
            @endif

            {{-- Super Admin Only --}}
            @if(auth()->user()->isSuperAdmin())
                <li class="admin-menu-header">Administration</li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.users.index') }}" class="admin-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Staff & Roles</span>
                    </a>
                </li>
                <li class="admin-menu-item">
                    <a href="{{ route('admin.settings.index') }}" class="admin-menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        <span>Site Settings</span>
                    </a>
                </li>
            @endif
        </ul>

        <div class="p-3 border-top border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 text-white small">
                <i class="bi bi-person-circle fs-5 text-warning"></i>
                <div class="overflow-hidden">
                    <div class="fw-bold text-truncate">{{ auth()->user()->name }}</div>
                    <small class="text-muted text-truncate d-block">{{ auth()->user()->role->name ?? 'Administrator' }}</small>
                </div>
            </div>
        </div>
    </aside>

    <!-- ==============================================
         Admin Main Content Wrapper
         ============================================== -->
    <div class="admin-main-wrapper">
        <!-- Top Navbar -->
        <header class="admin-header">
            <button class="btn btn-light d-lg-none" type="button" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div class="d-none d-md-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                    <i class="bi bi-shield-lock-fill me-1"></i> Role: {{ auth()->user()->role->name ?? 'Admin' }}
                </span>
                <span class="text-muted small">|</span>
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" style="font-size: 0.8rem;">
                    <i class="bi bi-box-arrow-up-right me-1"></i> View Live Site
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- User dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="fw-bold small">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userMenuDropdown">
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="admin-content">
            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Please check the form errors below:</div>
                    <ul class="mb-0 small ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts: Bootstrap 5 JS Bundle -->
    <script src="{{ asset('assets/vendor/js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
