@extends('layouts.app', ['page' => 'Add Sales', 'pageSlug' => 'sales', 'section' => 'transactions'])

@section('content')
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Add Sales Item</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">Back to Sales</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="border" method="post" id="formData" autocomplete="off">
                            @csrf

                            <input type="hidden" name="sale_id" id="sale_id" value="{{ $sale->id }}">
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-product">Barcode</label>
                                        <select name="product_id" id="input-product" class="form-select" autofocus >
                                            <option value="">--</option>
                                        @foreach ($products as $product)
                                            <option value="{{$product['id']}}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-item">Item</label>
                                        <input type="text" name="item" id="input-item" class="form-control" disabled>
                                    </div>
                                    <div class="col-1">
                                        <label class="form-control-label" for="input-price">Price</label>
                                        <input type="text" name="price" id="input-price" class="form-control" readonly>
                                    </div>
                                    <div class="col-1">
                                        <label class="form-control-label" for="input-avl">Avl Qty</label>
                                        <input type="text" name="avl" id="input-avl" class="form-control" disabled>
                                    </div>
                                    <div class="col-1">
                                        <label class="form-control-label" for="input-qty">Qty</label>
                                        <input type="text" name="qty" id="input-qty" class="form-control" value="0" >
                                    </div>
                                    <div class="col-1">
                                        <label class="form-control-label" for="input-discount">Discount</label>
                                        <input type="text" name="discount" id="input-discount" class="form-control" value="0" onblur="blurFn()">
                                    </div>
                                    <div class="col-1">
                                        <label class="form-control-label" for="input-total">Total</label>
                                        <input type="text" name="total_amount" id="input-total" class="form-control" readonly>
                                    </div>
                                    <div class="col-2">
                                        <input type="button" id="btn-add" value="Add" class="btn btn-sm btn-success mt-4" >
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table class="table border border-primary table-bordered">
                            <thead class="thead-light text-center">
                                <th scope="col">#</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">Item</th>
                                <th scope="col">Price</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Tax</th>
                                <th scope="col">Total</th>
                                <th ></th>
                            </thead>                        
                            <tbody id="links-list">
                                <?php $c=$sale->products->count('id') ?>
                                @foreach ($sale->products->reverse() as $sold_product)
                                    <tr class="text-center" id="row_{{ $sold_product->id }}">
                                        <td class="nos">{{ $c-- }}</td>
                                        <td>{{ $sold_product->product->name }}</td>
                                        <td>{{ $sold_product->product->item->name }}</td>
                                        <td>{{ format_money($sold_product->price) }}</td>
                                        <td>{{ $sold_product->qty }}</td>
                                        <td>{{ $sold_product->discount }}</td>
                                        <td>{{ $sold_product->tax_amt }}</td>
                                        <td class="text-right">{{ format_money($sold_product->total_amount) }}</td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="deleteProd({{ $sold_product->id }})" ><i class="tim-icons icon-simple-remove"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>                        
                        </table>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-9 text-right">Grand Total</div>
                                <div class="col-2">                                    
                                    <input type="text" id="grand_total" class="form-control" value="{{ format_money($sale->products->sum('total_amount')) }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-9"></div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-sm btn-success mt-4" onclick="confirm('ATTENTION: Do you want to finalize it? Your records cannot be modified from now on.') ? window.location.replace('{{ route('sales.finalize', $sale) }}') : ''">
                                        Finalize Sale
                                    </button>                                   
                                </div>
                            </div>
                        </div>
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
        });
    </script>
    <script>
        let input_qty = document.getElementById('input-qty');
        let input_avl = document.getElementById('input-avl');
        let input_price = document.getElementById('input-price');
        let input_discount = document.getElementById('input-discount');
        let input_total = document.getElementById('input-total');
        input_qty.addEventListener('input', updateTotal);
        input_discount.addEventListener('input', updateTotal);
        function updateTotal () {
            if(parseFloat(input_qty.value) > parseFloat(input_avl.value) )
            {
                alert('No available qty !!');
                input_qty.value = '';
                return false;
            }
            else
                input_total.value = ((parseFloat(input_qty.value) * parseFloat(input_price.value)) - parseFloat(input_discount.value)) || 0;
        }
        
    function blurFn() {        
        $('#btn-add').focus();
    }   

    // get barcode details   
    $('.form-select').on('change', function(e){
        e.preventDefault();
        let id =  $(this).val();
        let _url = `/products/${id}` 

        $.ajax({
            url: _url,
            type: 'GET',            
            success: function(res) {
                $('#input-item').val(res.item);
                $('#input-price').val(res.price);
                $('#input-avl').val(res.avl_qty);
                $('#input-qty').select();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $("#btn-add").click(function (e) { 
        e.preventDefault(); 
        var a = $("#input-product").val()
        var b = $("#input-price").val()
        var c = $("#input-qty").val()
        if (a==0 || a=="" || b==0 || b=="" || c==0 || c=="")
        {
            alert("Please fill all fields");
            return false;
        }
        else{
            let _sid = $('#sale_id').val();       
            $.ajax({
                type: "POST",
                url: `/sales/${_sid}/product`,
                data: $("#formData").serialize(),
                dataType: 'json',
                success: function (response) {
                    var link = '<tr class="text-center" id="row_' + response.id + '"><td class="nos"></td><td>' + response.bar + '</td><td>' + response.item + '</td><td>' + response.price + '</td><td>' + response.qty + '</td><td>' + response.discount + '</td><td>' + response.tax_amt + '</td><td class="text-right">' + response.total_amount  + '</td>';
                    link += '<td class="text-center"><a href="javascript:void(0)" onclick="deleteProd(' + response.id + ')" ><i class="tim-icons icon-simple-remove"></i></a></td></tr>';
                    
                    $('#links-list').prepend(link);
                    $('#grand_total').val(response.grand);                                 
                    $('#formData').trigger("reset");                    
                    
                    var nos = parseInt($('.nos').length);
                    $('.nos').each(function(i, obj) {
                        $(this).text( nos-- );
                    });
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });

    // Clicking delete
    function deleteProd(id){
        if(confirm('Are you sure to delete ?')){
            let _sid = $('#sale_id').val();
            let _url = `/sales/${_sid}/product/${id}` 
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                  _token: _token
                },
                success: function(response) {
                    $("#row_"+id).remove();
                    $('#grand_total').val(response.grand);

                    var nos = parseInt($('.nos').length);
                    $('.nos').each(function(i, obj) {
                        $(this).text( nos-- );
                    });
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    }
    </script>
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