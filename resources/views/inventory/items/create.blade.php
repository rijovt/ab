@extends('layouts.app', ['page' => 'New Item', 'pageSlug' => 'items', 'section' => 'inventory'])

@section('content')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">New Item</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('items.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('items.store') }}" autocomplete="off">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">Item Information</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-name">Name</label>
                                            <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Name" value="{{ old('name') }}" required autofocus>
                                            @include('alerts.feedback', ['field' => 'name'])
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group{{ $errors->has('prefix') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-prefix">Barcode Prefix</label>
                                            <input type="text" name="prefix" id="input-prefix" class="form-control form-control-alternative{{ $errors->has('prefix') ? ' is-invalid' : '' }}" placeholder="Prefix" value="{{ old('prefix') }}" oninput="this.value = this.value.toUpperCase()" required>
                                            @include('alerts.feedback', ['field' => 'prefix'])
                                        </div>
                                    </div>                                    
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group{{ $errors->has('product_category_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-category">Category</label>
                                            <select name="product_category_id" id="input-category" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                                                @foreach ($categories as $category)
                                                    @if($category['id'] == old('document'))
                                                        <option value="{{$category['id']}}" selected>{{$category['name']}}</option>
                                                    @else
                                                        <option value="{{$category['id']}}">{{$category['name']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @include('alerts.feedback', ['field' => 'product_category_id'])
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group{{ $errors->has('hsn') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-hsn">HSN</label>
                                            <input type="text" name="hsn" id="input-hsn" class="form-control form-control-alternative{{ $errors->has('hsn') ? ' is-invalid' : '' }}" placeholder="HSN" value="{{ old('hsn') }}" required>
                                            @include('alerts.feedback', ['field' => 'hsn'])
                                        </div>
                                    </div>                                    
                                </div>
            
                                <div class="row">
                                    <div class="col-4">                                    
                                        <div class="form-group{{ $errors->has('tax') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-tax">Tax %</label>
                                            <input type="number" step=".01" name="tax" id="input-tax" class="form-control form-control-alternative" placeholder="Tax" value="{{ old('tax') }}" required>
                                            @include('alerts.feedback', ['field' => 'tax'])
                                        </div>
                                    </div>                            
                                    <div class="col-4">                                    
                                        <div class="form-group{{ $errors->has('cess') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-cess">Cess</label>
                                            <input type="number" step=".01" name="cess" id="input-cess" class="form-control form-control-alternative" placeholder="Cess" value="{{ old('cess') }}">
                                            @include('alerts.feedback', ['field' => 'cess'])
                                        </div>
                                    </div>                                                    
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        new SlimSelect({
            select: '.form-select'
        })
    </script>
@endpush