<form class="d-flex flex-grow-1 mx-3" action="{{ route('customer.search') }}" method='GET' role="search">
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted ps-3">
            <i class="bi bi-search"></i>
        </span>
        <input class="form-control border-start-0 rounded-end-pill py-2"
            type="search"
            name="query"
            value="{{ request('query') }}"
            placeholder="Search products..."
            aria-label="Search">
    </div>
</form>
