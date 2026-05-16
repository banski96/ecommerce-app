@extends('layouts.customerLayout')
@section('content')
<div class="container my-5">
    <h2 class="mb-4">Your Orders</h2>
    <div class="card" style="width: 18rem; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
        <img src="https://path.to.your/reusable_travel_mugs.jpg" class="card-img-top p-3 rounded" alt="">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title m-0">Reusable Travel Mugs</h5>
            <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">PAID</span>
            </div>

            <div class="text-muted mb-3" style="font-size: 0.9rem;">
            <p class="m-0">SKU: HM-TM-4</p>
            <p class="m-0">Unit Price: $80.00</p>
            </div>

            <div class="input-group input-group-sm mb-3">
            <button class="btn btn-outline-secondary" type="button">-</button>
            <input type="text" class="form-control text-center" value="1" readonly>
            <button class="btn btn-outline-secondary" type="button">+</button>
            </div>

            <div class="row text-muted small mb-3">
            <div class="col-6 border-end">
                <p><i class="bi bi-credit-card me-1"></i> Credit Card</p>
                <p><i class="bi bi-calendar-event me-1"></i> Date</p>
                <p><i class="bi bi-geo-alt me-1"></i> Address</p>
            </div>
            <div class="col-6 text-end">
                <p>Subtotal: $155.00</p>
                <h6 class="text-dark">Order Total: $165.00</h6>
            </div>
            </div>

            <a href="#" class="btn btn-primary w-100">View Item Details →</a>
        </div>
        </div>

</div>
