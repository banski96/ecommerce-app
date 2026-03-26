<div class="d-flex">
    {{-- Sidebar --}}
    <div class="nav flex-column me-3 sidebar">
        <a href="{{ route('admin.categories') }}"
            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="{{ route('admin.categories') }}"
            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('admin.categories.create') }}"
            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
            <i class="bi bi-cart"></i> Products
        </a>
    
    </div>
    

    {{-- Main content area --}}
    <div class="flex-grow-1">
        {{ $slot }}
    </div>
</div>