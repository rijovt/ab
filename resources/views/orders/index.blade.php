@extends('layouts.app', ['page' => 'Orders', 'pageSlug' => 'orders', 'section' => 'production'])

@section('content')
    @include('alerts.success')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Orders</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">Create Order</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <table class="table">
                            <thead class="thead-light">
                                <th>Date</th>
                                <th>Order No</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Finished Qty</th>
                                <th>User</th>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ date('d-m-y', strtotime($order->finalized_at ?? $order->created_at)) }}</td>
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->color }}</td>
                                        <td>{{ $order->size }}</td>
                                        <td>{{ $order->qty }}</td>
                                        <td>{{ $order->finish_qty }}</td>
                                        <td>{{ $order->user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $orders->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
