@extends('layouts.app', ['page' => 'Manage Receipt', 'pageSlug' => 'receipts', 'section' => 'inventory'])


@section('content')
    @include('alerts.success')
    @include('alerts.error')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Receipt Summary</h4>
                        </div>
                        <div class="col-4 text-right">
                            @if (!$receipt->finalized_at)
                                @if ($receipt->products->count() === 0)
                                    <form action="{{ route('receipts.destroy', $receipt) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Delete Receipt
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-success" onclick="confirm('ATTENTION: At the end of this receipt you will not be able to load more products in it.') ? window.location.replace('{{ route('receipts.finalize', $receipt) }}') : ''">
                                        Finalize Receipt
                                    </button>
                                @endif
                            @endif
                            
                            <a href="{{ route('receipts.index') }}" class="btn btn-sm btn-primary">Back</a>
                        </div>

                        
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Provider</th>
                            <th>products</th>
                            <th>Stock</th>
                            <!-- <th>Defective Stock</th> -->
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $receipt->id }}</td>
                                <td>{{ date('d-m-y', strtotime($receipt->created_at)) }}</td>
                                <td style="max-width:150px;">{{ $receipt->title }}</td>
                                <td>{{ $receipt->user->name }}</td>
                                <td>
                                    @if($receipt->provider_id)
                                        <a href="{{ route('providers.show', $receipt->provider) }}">{{ $receipt->provider->name }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $receipt->products->count() }}</td>
                                <td>{{ $receipt->products->sum('stock') }}</td>
                                <!-- <td>{{ $receipt->products->sum('stock_defective') }}</td> -->
                                <td>{!! $receipt->finalized_at ? 'Finalized' : '<span style="color:red; font-weight:bold;">TO FINALIZE</span>' !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">products: {{ $receipt->products->count() }}</h4>
                        </div>
                        @if (!$receipt->finalized_at)
                            <div class="col-4 text-right">
                                <a href="{{ route('receipts.product.add', ['receipt' => $receipt]) }}" class="btn btn-sm btn-primary">Add</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-striped">
                        <thead>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Barcode</th>
                            <th>Selling Price</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @foreach ($receipt->products as $received_product)
                                <tr>
                                    <td><a href="{{ route('categories.show', $received_product->item->category) }}">{{ $received_product->item->category->name }}</a></td>
                                    <td>{{ $received_product->item->name }}</td>
                                    <td><a class="btn btn-sm btn-success">{{ $received_product->barcode }}</a></td>
                                    <td>{{ $received_product->selling_price }}</td>
                                    <td>{{ $received_product->stock }}</td>
                                    <td>{{ $received_product->price }}</td>
                                    <td>{{ $received_product->price * $received_product->stock }}</td>                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/sweetalerts2.js"></script>
@endpush