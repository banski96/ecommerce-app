<!-- Desktop nav -->
<nav class="desktop-nav navbar navbar-expand-md bg-light p-3 d-none d-md-flex">
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('customer.home') }}">
            <img src="{{ url('/assets/logo.png') }}" alt="Logo" style="height: 100px;">
        </a>

        <x-search-bar />

        <!-- Nav links / cart -->
        <ul class="navbar-nav d-flex flex-row align-items-center gap-3 mb-0">
            <li class="nav-item"><a class="nav-link" href="{{route('customer.home')}}">Home</a></li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="{{ route('cart.view') }}">
                    <i class="bi bi-cart"></i>
                </a>
            </li>

            <!-- Authentication Dropdown -->
            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class='bi bi-gear fs-4'></i>
                                {{ __('Accounts') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{route('customer.order')}}">
                                <i class="bi bi-clipboard-check fs-4"></i>
                                {{ __('Orders') }}
                            </a>
                        </li>
                         <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right fs-4"></i>
                                {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>

                    </ul>
                </li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
            @endauth
        </ul>
    </div>
</nav>

<!-- Mobile search bar (top) -->
<div class="d-md-none p-2 bg-light">
    <x-search-bar-mobile />
</div>

<!-- Mobile nav (bottom) -->
<div class="mobile-nav d-flex d-md-none justify-content-around bg-light border-top py-2 fixed-bottom">
    <a href="{{ route('customer.home') }}" class="text-center text-decoration-none text-dark">
        <i class="bi bi-house fs-4"></i><br><small>Home</small>
    </a>

    @auth
        <!-- Link to Profile for the "You" tab -->
        <a href="{{ route('profile.edit') }}" class="text-center text-decoration-none text-dark">
            <i class="bi bi-gear fs-4"></i><br><small>Account</small>
        </a>
        <a href="{{route('customer.order')}}" class="text-center text-decoration-none text-dark">
            <i class="bi bi-clipboard-check fs-4"></i><br><small>Orders</small>
        </a>
    @else
        <a href="{{ route('login') }}" class="text-center text-decoration-none text-dark">
            <i class="bi bi-person fs-4"></i><br><small>Login</small>
        </a>
    @endauth

    <a href="{{ route('cart.view') }}" class="text-center text-decoration-none text-dark">
        <i class="bi bi-cart fs-4"></i><br><small>Cart</small>
    </a>

    <!-- Simple Logout for Mobile "Menu" or an Offcanvas -->
    @auth
        <a href="#" class="text-center text-decoration-none text-dark"
            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
            <i class="bi bi-box-arrow-right fs-4"></i><br><small>Logout</small>
        </a>
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endauth
</div>
