<form class="d-flex mb-2 w-100" action="{{ route('customer.search') }}" method='GET' role="search">
    <div class="input-group shadow-sm rounded-pill border bg-white overflow-hidden">
        <span class="input-group-text border-0 bg-transparent text-muted ps-3 pe-2">
            <i class="bi bi-search" style="font-size: 0.95rem;"></i>
        </span>

        <input class="form-control border-0 bg-transparent py-2 shadow-none ps-0"
            type="search"
            name="query"
            value="{{ request('query') }}"
            placeholder="Search products..."
            aria-label="Search">
    </div>
</form>
