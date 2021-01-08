@extends('layouts.app', ['page' => 'List of Items', 'pageSlug' => 'items', 'section' => 'inventory'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Items</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('items.create') }}" class="btn btn-sm btn-primary">New Item</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="">
                        <table class="table">
                            <thead class="thead-light">
                                <th scope="col">Category</th>
                                <th scope="col">Name</th>
                                <th scope="col">Prefix</th>
                                <th scope="col">HSN</th>
                                <th scope="col">Tax %</th>
                                <th scope="col">Cess</th>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->prefix }}</td>
                                        <td>{{ $item->hsn }}</td>
                                        <td>{{ $item->tax }}</td>
                                        <td>{{ $item->cess }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $items->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
