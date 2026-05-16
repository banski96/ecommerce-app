<div class="d-flex">
    {{-- Sidebar --}}
    <div class="nav flex-column me-3 sidebar">
        <a href="{{ route('admin.categories') }}"
            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('admin.categories') }}"
            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('admin.products') }}"
            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.products') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
        </a>
        <a href="{{ route('admin.orders') }}"
            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check"></i> Orders
        </a>
        @auth
            <a href="#"
                class="nav-link d-flex align-items-center gap-2 text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @endauth
    </div>

    {{-- Main content area --}}
    <div class="flex-grow-1">
        {{ $slot }}
    </div>
</div>
