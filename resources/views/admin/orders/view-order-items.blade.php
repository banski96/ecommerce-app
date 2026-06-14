@extends('admin.layout')

@section('content')
<h1 class="title-name">Order Items</h1>

<table class="table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Description</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->product_name}}</td>
            <td>
                @if($item->product->product_image)
                    <img src="{{ $item->product->product_image }}" alt="{{ $item->product->product_name }}" class="img-fluid" style="max-width:50px;">
                @else
                    <p>No image available</p>
                @endif
            </td>
            <td>{{ $item->product->description }}</td>
            <td>{{ $item->unit_price }}</td>
            <td>{{ $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
