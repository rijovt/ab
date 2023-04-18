@extends('layouts.app', ['page' => 'Orders', 'pageSlug' => 'orders', 'section' => 'production'])

@section('content')
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Create Order</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">Back to Orders</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="border" method="post" id="formData" autocomplete="off">
                            @csrf

                            <div class="pl-lg-4">
                                <div class="row">        
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-order_no">Order no</label>
                                        <input type="number" name="order_no" id="input-order_no" class="form-control" autofocus>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-color">Color</label>
                                        <input type="text" name="color" id="input-color" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-size">Size</label>
                                        <select name="size" id="input-size" class="form-select"> 
                                            <option value="">--</option>
                                            <option>XS</option>
                                            <option>S</option>
                                            <option>M</option>
                                            <option>L</option>
                                            <option>XL</option>
                                            <option>XXL</option>
                                            <option>XXXL</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-control-label" for="input-qty">Qty</label>
                                        <input type="number" name="qty" id="input-qty" class="form-control" value="0" >
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
                                <th scope="col">Order No</th>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col">Qty</th>
                                <th ></th>
                            </thead>                        
                            <tbody id="links-list">
                                <?php $c=$orders->count('id') ?>
                                @foreach ($orders as $order)
                                    <tr class="text-center" id="row_{{ $order->id }}">
                                        <td class="nos">{{ $c-- }}</td>
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->color }}</td>
                                        <td>{{ $order->size }}</td>
                                        <td>{{ $order->qty }}</td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="deleteOrder({{ $order->id }})" ><i class="tim-icons icon-simple-remove"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>                        
                        </table>

                        <div class="pl-lg-4">
                            <div class="row">
                                <button type="button" class="btn btn-sm btn-success" onclick="confirm('ATTENTION: Do you want to finalize it?') ? window.location.replace('{{ route('orders.finalize') }}') : ''">
                                    Finalize
                                </button>
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
    $("#btn-add").click(function (e) { 
        e.preventDefault(); 
        var a = $("#input-order_no").val()
        var b = $("#input-color").val()
        var c = $("#input-size").val()
        var d = $("#input-qty").val()
        if (a==0 || a=="" || b==0 || b=="" || c==0 || c=="" || d==0 || d=="")
        {
            alert("Please fill all fields");
            return false;
        }
        else{
            $.ajax({
                type: "POST",
                url: `/production/orders/add`,
                data: $("#formData").serialize(),
                dataType: 'json',
                success: function (response) {
                    var link = '<tr class="text-center" id="row_' + response.id + '"><td class="nos"></td><td>' + response.order_no + '</td><td>' + response.color + '</td><td>' + response.size + '</td><td>' + response.qty + '</td>';
                    link += '<td class="text-center"><a href="javascript:void(0)" onclick="deleteOrder(' + response.id + ')" ><i class="tim-icons icon-simple-remove"></i></a></td></tr>';
                    
                    $('#links-list').prepend(link);
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
    function deleteOrder(id){
        if(confirm('Are you sure to delete ?')){
            let _url = `/production/orders/delete/${id}` 
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                  _token: _token
                },
                success: function(response) {
                    $("#row_"+id).remove();

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
            height:300px;
            overflow:auto;
        }
        thead, tbody tr {
            display:table;
            width:100%;
            table-layout:fixed;
        }
    </style>
@endpush