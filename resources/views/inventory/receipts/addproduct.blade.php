@extends('layouts.app', ['page' => 'Add Product', 'pageSlug' => 'receipt', 'section' => 'inventory'])

@section('content')
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Add Product</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('receipts.show', $receipt) }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" id="formData" autocomplete="off">
                            @csrf
                            
                            <input type="hidden" name="receipt_id" id="receipt_id" value="{{ $receipt->id }}">
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-item">Item</label>
                                        <select name="item_id" id="input-item" class="form-select form-control-alternative" required>
                                            @foreach ($items as $item)
                                                <option value="{{$item['id']}}">{{ $item->name }} [{{ $item->category->name }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-stock">Qty</label>
                                        <input type="number" name="stock" id="input-stock" class="form-control form-control-alternative" value="0" required>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-price">Price</label>
                                        <input type="number" name="price" id="input-price" class="form-control form-control-alternative" value="0" required>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-selling">Selling Price</label>
                                        <input type="number" name="selling_price" id="input-selling" class="form-control form-control-alternative" value="0" required>
                                    </div>
                                    <div class="col-2">
                                        <input type="button" id="btn-add" value="Add" class="btn btn-sm btn-success mt-4" >
                                    </div>
                                </div>
                            </div>
                        </form>

                    <table class="table table-sm table-striped">
                        <thead>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Barcode</th>
                            <th>Selling Price</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th></th>
                        </thead>
                        <tbody id="links-list">
                            @foreach ($receipt->products->reverse() as $received_product)
                                <tr id="row_{{ $received_product->id }}">
                                    <td>{{ $received_product->item->category->name }}</td>
                                    <td>{{ $received_product->item->name }}</td>
                                    <td><a class="btn btn-sm btn-success">{{ $received_product->barcode }}</a></td>
                                    <td>{{ $received_product->selling_price }}</td>
                                    <td>{{ $received_product->stock }}</td>
                                    <td>{{ $received_product->price }}</td>
                                    <td>{{ round($received_product->price * $received_product->stock,1) }}</td>
                                    <td class="text-center">
                                        <!-- @if(!$receipt->finalized_at)
                                            <a href="{{ route('receipts.product.edit', ['receipt' => $receipt, 'receivedproduct' => $received_product]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Pedido">
                                                <i class="tim-icons icon-pencil"></i>
                                            </a> -->
                                            <a href="javascript:void(0)" onclick="deleteProd({{ $received_product->id }})" ><i class="tim-icons icon-simple-remove"></i></a>
                                            
                                        <!-- @endif -->
                                    </td>
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
    <script>
        new SlimSelect({
            select: '.form-select'
        });

$(document).ready(function($){
     // Clicking the save button
    $("#btn-add").click(function (e) { 
        e.preventDefault(); 
        var a = $("#input-stock").val()
        var b = $("#input-price").val()
        var c = $("#input-selling").val()
        if (a==0 || a=="" || b==0 || b=="" || c==0 || c=="")
        {
            alert("Please fill all fields");
            return false;
        }
        else{       
            $.ajax({
                type: "POST",
                url: "{{ route('receipts.product.store') }}",
                data: $("#formData").serialize(),
                dataType: 'json',
                success: function (response) {
                    if(response.code == 200) {
                        var link = '<tr id="row_' + response.data.id + '"><td>' + response.data.item.category.name + '</td><td>' + response.data.item.name + '</td><td><a class="btn btn-sm btn-success">' + response.data.barcode + '</a></td><td>' + response.data.selling_price + '</td><td>' + response.data.stock + '</td><td>' + response.data.price + '</td><td>' + response.data.total  + '</td>';
                        link += '<td class="text-center"><a href="javascript:void(0)" onclick="deleteProd(' + response.data.id + ')" ><i class="tim-icons icon-simple-remove"></i></a></td></tr>';
                        
                        $('#links-list').prepend(link);                
                        $('#formData').trigger("reset");
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });    
});

// Clicking delete
function deleteProd(id){
    if(confirm('Are you sure ?')){
        let _url = `/inventory/receipts/product/${id}` 
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: _url,
            type: 'DELETE',
            data: {
              _token: _token
            },
            success: function(response) {
                $("#row_"+id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
}
    </script>
@endpush