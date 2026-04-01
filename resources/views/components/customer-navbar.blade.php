<!-- Desktop nav -->
<nav class="desktop-nav navbar navbar-expand-md bg-light p-3 d-none d-md-flex">
    <div class="container d-flex align-items-center justify-content-between">
    <!-- Logo -->
        <a class="navbar-brand" href="#">Logo</a>

        <x-search-bar />

    <!-- Nav links / cart -->
        <ul class="navbar-nav d-flex flex-row align-items-center gap-3 mb-0">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2" href="#">
            <i class="bi bi-cart"></i>
            </a>
        </li>
        </ul>
    </div>
</nav>


<!-- Mobile search bar (top) -->
<div class="d-md-none p-2 bg-light">
    <x-search-bar-mobile />
</div>
<!-- Mobile nav (bottom) -->
<div class="mobile-nav d-flex d-md-none">
    <a href="#" class="text-center text-decoration-none">
    <i class="bi bi-house fs-4"></i><br><small>Home</small>
    </a>
    <a href="#" class="text-center text-decoration-none">
    <i class="bi bi-person fs-4"></i><br><small>You</small>
    </a>
    <a href="#" class="text-center text-decoration-none">
    <i class="bi bi-cart fs-4"></i><br><small>Cart</small>
    </a>
    <a href="#" class="text-center text-decoration-none">
    <i class="bi bi-list fs-4"></i><br><small>Menu</small>
    </a>
</div>
