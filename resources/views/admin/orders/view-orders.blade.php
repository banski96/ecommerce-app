@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Orders Management</h1>
        </div>
        <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill">{{ $orders->count() }} Total Orders</span>
    </div>

    <!-- 1. DESKTOP/TABLET VIEW: Traditional Data Table (Visible on md screens and up) -->
    <div class="card border-0 shadow-sm rounded-3 d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-muted border-bottom">
                        <tr>
                            <th class="ps-4 py-3">Order info</th>
                            <th class="py-3">Customer Details</th>
                            <th class="py-3">Items</th>
                            <th class="py-3">Total Amount</th>
                            <th class="py-3">Order Status</th>
                            <th class="py-3">Payment</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="border-bottom">
                            <!-- Order Info -->
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark">#{{ $order->order_id }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 120px;" title="{{ $order->reference_number }}">
                                    Ref: {{ $order->reference_number }}
                                </div>
                            </td>

                            <!-- Customer Details -->
                            <td class="py-3">
                                <div class="text-dark fw-semibold">User ID: {{ $order->user_id }}</div>
                                <div class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>{{ $order->mobile_number }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 180px;" title="{{ $order->shipping_address }}">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $order->shipping_address }}
                                </div>
                            </td>

                            <!-- Items Preview -->
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    @foreach($order->items as $item)
                                        @if($loop->iteration <= 3)
                                            <img src="{{ asset($item->product->product_image) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="rounded border me-1 bg-light"
                                                 style="width: 40px; height: 40px; object-fit: cover;"
                                                 title="{{ $item->product->name }}">
                                        @endif
                                    @endforeach

                                    @if($order->items->count() > 3)
                                        <span class="text-muted small fw-bold ms-1">+{{ $order->items->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Total Amount -->
                            <td class="py-3">
                                <span class="fw-bold text-dark">${{ number_format($order->total_amount, 2) }}</span>
                            </td>

                            <!-- Order Status Badge -->
                            <td class="py-3">
                                @if($order->status === 'completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Completed</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Pending</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>

                            <!-- Payment Status Badge -->
                            <td class="py-3">
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-3">Paid</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3">Unpaid</span>
                                @endif
                            </td>

                            <!-- Actions Row -->
                            <td class="py-3 text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.orders.view', $order) }}"
                                       class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 shadow-sm"
                                       title="View Order Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="#"
                                       class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-sm"
                                       title="Edit Order">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary"></i>
                                No orders found in the database.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. MOBILE VIEW: Clean Order List Cards (Visible on screens smaller than md) -->
    <div class="d-md-none">
        @forelse($orders as $order)
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body p-3">
                    <!-- Top Section: ID & Price -->
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                        <div>
                            <span class="fw-bold text-dark fs-5">#{{ $order->order_id }}</span>
                            <span class="text-muted d-block small text-truncate" style="max-width: 150px;">Ref: {{ $order->reference_number }}</span>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-dark d-block fs-5">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Middle Section: Badges & Thumbnails -->
                    <div class="row align-items-center mb-3 g-2">
                        <div class="col-6">
                            <div class="small text-muted mb-1">Status</div>
                            @if($order->status === 'completed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill w-100 text-center">Completed</span>
                            @elseif($order->status === 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill w-100 text-center">Pending</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill w-100 text-center">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                        <div class="col-6">
                            <div class="small text-muted mb-1">Payment</div>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-3 w-100 text-center">Paid</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-3 w-100 text-center">Unpaid</span>
                            @endif
                        </div>

                        <div class="col-12 mt-2">
                            <div class="small text-muted mb-1">Items:</div>
                            <div class="d-flex align-items-center bg-light p-2 rounded border">
                                @foreach($order->items as $item)
                                    @if($loop->iteration <= 4)
                                        <img src="{{ asset($item->product->product_image) }}"
                                            alt="{{ $item->product->name }}"
                                            class="rounded border me-1 bg-white"
                                            style="width: 35px; height: 35px; object-fit: cover;">
                                    @endif
                                @endforeach
                                @if($order->items->count() > 4)
                                    <span class="text-muted small fw-bold ms-1">+{{ $order->items->count() - 4 }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section: Customer Details Summary & Buttons -->
                    <div class="bg-light p-2 rounded mb-3 small text-muted">
                        <div class="text-dark fw-semibold">User ID: {{ $order->user_id }}</div>
                        <div class="text-truncate"><i class="bi bi-geo-alt me-1"></i>{{ $order->shipping_address }}</div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.orders.view', $order) }}" class="btn btn-sm btn-outline-primary w-100 py-2 d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-sm btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-3 text-center py-5 text-muted">
                <div class="card-body">
                    <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary"></i>
                    No orders found in the database.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
