@extends('layouts.app', ['page' => 'Stock', 'pageSlug' => 'products', 'section' => 'inventory'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Stock Items</h4>
                        </div>
                        <!-- <div class="col-4 text-right">
                            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">New product</a>
                        </div> -->
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <form class="border" method="get" action="{{ route('products.index') }}" autocomplete="off">
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-bar"></label>
                                        <input type="text" name="bar" id="input-bar" class="form-control" value="{{!empty($bar) ? $bar :''}}" placeholder="Barcode">
                                    </div> 
                                    <div class="pt-lg-4">or</div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-bar"></label>
                                        <input type="text" name="item" id="input-bar" class="form-control" value="{{!empty($item) ? $item :''}}" placeholder="Item">
                                    </div>
                                    <div class="col-2">
                                        <input type="submit" value="Search" class="btn btn-sm btn-success mt-4" >
                                        @if (!empty($bar) || !empty($item))
                                            <a href="{{ route('products.index') }}">
                                                <input type="button" value="Clear" class="btn btn-sm btn-success mt-4" >
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </form>

                    
                        <table class="table border border-primary">
                            <thead class="thead-light text-center">
                                <th scope="col">#</th>
                                <th scope="col">Category</th>
                                <th scope="col">Item</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">Selling Price</th>
                                <th scope="col">Stock</th>
                                <!-- <th scope="col">Faulty</th> -->
                                <th scope="col">Total Sold</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr class="text-center">
                                        <td>{{ $products->firstItem() + $key }}</td>
                                        <td><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></td>
                                        <td>{{ $product->item->name }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ format_money($product->price) }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $product->solds->sum('qty') }}</td>
                                        <td class="td-actions text-center">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="bottom" title="More Details">
                                                <i class="tim-icons icon-zoom-split"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Product">
                                                <i class="tim-icons icon-pencil"></i>
                                            </a>
                                            <!-- <form action="{{ route('products.destroy', $product) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Delete Product" onclick="confirm('Are you sure you want to remove this product? The records that contain it will continue to exist.') ? this.parentElement.submit() : ''">
                                                    <i class="tim-icons icon-simple-remove"></i>
                                                </button>
                                            </form> -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end">
                        {{ $products->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <style type="text/css">
        tbody {
            display:block;
            height:500px;
            overflow:auto;
        }
        thead, tbody tr {
            display:table;
            width:100%;
            table-layout:fixed;
        }
    </style>
@endpush