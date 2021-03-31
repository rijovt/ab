@extends('layouts.app', ['page' => 'Manage Sale', 'pageSlug' => 'sales', 'section' => 'transactions'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Sale Summary</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-sm btn-success" onclick="prit()">Print</button>
                            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">Back to Sales</a>
                        </div>
                    </div>
                    <div id="pdiv" class="card-body"> 
 <style type="text/css">
    #page-wrap {
        border: 1px solid;
        margin: 0 auto;
        font-family:Arial, Helvetica, sans-serif;
        width: 21cm;
        min-height:30.9cm;
    }
    .center-justified {
        text-align: center;
    }
    table {
        border-spacing: 0;
        font-size:12px;
    }
    table.outline-table {
        border: 1px solid;
        border-spacing: 0;
    }
    tr.border-bottom td, td.border-bottom {
        border-bottom: 1px solid;
    }
    tr.border-top td, td.border-top {
        border-top: 1px solid;
    }
    tr.border-right td, td.border-right {
        border-right: 1px solid;
    }
    tr.border-right td:last-child {
        border-right: 0px;
    }
    tr.center td, td.center {
        text-align: center;
        vertical-align: text-top;
    }
    td.pad-left {
        padding: 5px;
    }
    tr.right-center td, td.right-center {
        text-align: right;
        padding-right: 50px;
    }
    tr.right td, td.right {
        text-align: right;
    }
    .grey {
        /*background:#CCCCCC;*/
    }
</style>
                        <div id="page-wrap">
                            <table width="100%">
                                <tbody>                                    
                                    <tr>
                                        <td>
                                            <div class="center-justified">
                                                <div><h1 style="margin-bottom: 0px">EUFAB Fashion Bespoke</h1></div>
                                                <div>Kottaram Cross Road, East Nadakkave, CALICUT-6</div>
                                                <div><strong>04954856234, 8751054321</strong></div>
                                                <div><strong>GSTIN : 32CYMPM8090A1ZF</strong></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="100%">
                                <tbody>
                                    <tr class="border-right border-top border-left">
                                        <td colspan="2"><div class="center-justified"><h3 style="margin-bottom: 0px">TAX INVOICE</h3></div></td>
                                    </tr>
                                    <tr class="border-right border-top border-left">
                                        <td width="50%" class="pad-left">Reverse Charge: <strong>NO</strong></td>
                                        <td width="50%" class="pad-left">Transport: </td>
                                    </tr>
                                    <tr class="border-right border-left">
                                        <td width="50%" class="pad-left">Invoice No: <strong>{{ $sale->inv_no }}</strong></td>
                                        <td width="50%" class="pad-left">L.R. No: </td>
                                    </tr>
                                    <tr class="border-right border-left">
                                        <td class="pad-left">Invoice Date: <strong>{{ date('d-m-Y', strtotime($sale->finalized_at)) }}</strong></td>
                                        <td class="pad-left">Supply Date: <strong>{{ date('d-m-Y', strtotime($sale->finalized_at)) }}</strong></td>
                                    </tr>
                                    <tr class="border-right border-left">
                                        <td class="pad-left">State: <strong>Kerala</strong> </td>
                                        <td class="pad-left">Place of Supply: <strong></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="100%">
                                <tbody>
                                    <tr class="border-right border-top border-bottom border-left">
                                        <td width="50%" class="pad-left center grey">Details of Receiver(Bill To)</td>
                                        <td width="50%" class="pad-left center grey">Details of Consignee(Shipp To)</td>
                                    </tr>
                                    <tr class="border-right border-left">
                                        <td class="pad-left">Name :<strong>{{ $sale->client->name }}</strong></td>
                                        <td class="pad-left">Name :<strong>{{ $sale->client->name }}</strong></td>
                                    </tr>
                                    <tr class="border-right border-left">
                                        <td class="pad-left">Address :<strong>{{ $sale->client->address ?? '-' }}</strong></td>
                                        <td class="pad-left">Address :<strong>{{ $sale->client->address ?? '-' }}</strong></td>
                                    </tr>
                                    <tr class="border-right border-left">
                                        <td class="pad-left">Phone No : <strong>{{ $sale->client->phone }}</strong></td>
                                        <td class="pad-left">Phone No : <strong>{{ $sale->client->phone }}</strong></td>
                                    </tr>
                                    <tr class="border-right">
                                        <td class="pad-left"><strong>GSTIN:- {{ $sale->client->gstin }}</strong></td>
                                        <td class="pad-left"><strong>GSTIN:- {{ $sale->client->gstin }}</strong></td>
                                    </tr>
                                    <tr class="border-right">
                                        <td class="pad-left"><strong>State</strong>: {{ $sale->client->state }}</td>
                                        <td class="pad-left"><strong>State</strong>: {{ $sale->client->state }}</td>
                                    </tr>
                                </tbody>
                            </table>       
                            <table width="100%">
                                <tbody>
                                    <tr class="border-right border-top border-bottom border-left">
                                        <td width="3%" class="pad-left center grey"><strong>No</strong></td>
                                        <td width="19%" class="pad-left center grey"><strong>Description of Goods</strong></td>
                                        <td width="11%" class="pad-left center grey"><strong>HSN</strong></td>
                                        <td width="5%" class="pad-left center grey"><strong>Qty</strong></td>
                                        <td width="10%" class="pad-left center grey"><strong>Item Rate</strong></td>
                                        <td width="10%" class="pad-left center grey"><strong>Item Discount(Rs)</strong></td>
                                        <td width="5%" class="pad-left center grey"><strong>GST %</strong></td>                                    
                                        <td width="13%" class="pad-left center grey"><strong>TOTAL</strong></td>
                                    </tr>
                                    <?php $i=$taxable_t=$tax_t=$disc_t=0; ?>
                                    @foreach ($sale->products as $sold_product)
                                    <?php $taxable = $sold_product->total_amount - ($sold_product->tax_amt + $sold_product->cess_amt);
                                        $taxable_t += $taxable;
                                        $tax_t += format_money($sold_product->tax_amt/2) + format_money($sold_product->tax_amt/2);
                                        $disc_t += $sold_product->discount;
                                    ?> 
                                    <tr class="border-right border-left">
                                        <td class="pad-left center">{{ ++$i }}</td>
                                        <td class="pad-left">{{ substr($sold_product->product->item->name,0,14) }}</td>
                                        <td class="pad-left center">{{ $sold_product->product->item->hsn }}</td>
                                        <td class="pad-left center">{{ $sold_product->qty }}</td>
                                        <td class="pad-left center">{{ $sold_product->price }}</td>
                                        <td class="pad-left center">{{ $sold_product->discount>0 ? $sold_product->discount :'-' }}</td>
                                        <td class="pad-left center">{{ $sold_product->product->item->tax+0 }}</td>
                                        <td valign="top" class="pad-left right">{{ format_money($sold_product->total_amount) }}</td>
                                    </tr>
                                    @endforeach
                                    <?php for($i;$i<6;++$i) {?>
                                    <tr class="border-right  border-left">
                                        <td class="pad-left center">&nbsp;</td>
                                        <td class="pad-left"></td>
                                        <td class="pad-left center"></td>
                                        <td class="pad-left center"></td>
                                        <td class="pad-left center"></td>
                                        <td class="pad-left center"></td>
                                        <td class="pad-left center"></td>                                     
                                        <td valign="top" class="pad-left right"></td>
                                    </tr>
                                    <?php }?>
                                    <tr class="border-top border-right border-bottom border-left">
                                        <td class="pad-left center grey" colspan="2"></td>
                                        <td class="pad-left center">TOTAL</td>
                                        <td class="pad-left center">{{ $sale->products->sum('qty') }}</td>
                                        <td class="pad-left center"> - </td>
                                        <td class="pad-left center">{{ $sale->products->sum('discount') ? $sale->products->sum('discount') :'-' }}</td> 
                                        <td class="pad-left center">-</td>
                                        <td class="pad-left right">{{ format_money($sale->products->sum('total_amount')) }}</td>
                                    </tr>               
                                </tbody>
                            </table>
                            <table width="100%">
                                <tbody>
                                    <tr class="border-right border-bottom border-left">
                                        <td colspan="3" class="pad-left"><strong>Remarks</strong> </td>
                                   </tr>
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left" rowspan="6" valign="top"><strong>Terms and Conditions :</strong><br>
                                        *GOODS ONCE SOLD WILL NOT BE TAKEN BACK.<br>
                                        *CLAIMS, IF ANY REGARDING THIS INVOICE MUST BE INFORMED IN WRITING WITHIN 2 DAYS
                                        </td>
                                        <td width="25%" class="pad-left">Discount Total</td>
                                        <td width="25%" class="pad-left right">{{ format_money($disc_t) }}</td>
                                    </tr>
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left">Total Taxable value</td>
                                        <td class="pad-left right">{{ format_money($taxable_t) }}</td>
                                    </tr>

                                    @if ($sale->client->state != 'Kerala')
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left">IGST Total</td>
                                        <td class="pad-left right">{{ format_money($tax_t) }}</td>
                                    </tr>
                                    @else
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left">CGST Total</td>
                                        <td class="pad-left right">{{ format_money($tax_t/2) }}</td>
                                    </tr>
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left">SGST Total</td>
                                        <td class="pad-left right">{{ format_money($tax_t/2) }}</td>
                                    </tr>                                      
                                    @endif

                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left">Total Tax Amount </td>
                                        <td class="pad-left right">{{ format_money($tax_t) }}</td>
                                    </tr>                                    
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left">Total Amount After Tax </td>
                                        <td class="pad-left right">{{ format_money($taxable_t + $tax_t) }}</td>
                                    </tr>
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left" rowspan="2" valign="top"><strong>Total Invoice Amount in Words</strong><br><br>{{ ucwords(convert_number_to_words($sale->total_amount+0))}} Rupees Only</td>
                                        <td class="pad-left">Round Off </td>
                                        <td class="pad-left right">{{ format_money($sale->total_amount -($taxable_t + $tax_t)) }}</td>
                                    </tr>
                                    <tr class="border-right border-bottom border-left">
                                        <td class="pad-left"><strong>TOTAL</strong></td>
                                        <td class="pad-left right"><strong>{{ format_money($sale->total_amount)}}</strong></td>
                                    </tr>               
                                </tbody>
                            </table>
                            <table width="100%">
                                <tbody>
                                    <tr><td class="pad-left">E. & O.E</td></tr>
                                    <tr><td class="pad-left right">For, <strong>EUFAB Fashion Bespoke</strong></td></tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr><td class="pad-left right">Authorized Signatory</td></tr>
                                    <tr><td class="pad-left">This is computer generated invoice and does not require a signature</td></tr>
                                <tbody>
                            </table>        
                    </div>
                </div>
            </div>
        </div>
    </div>   
@endsection

@push('js')
<script>
function prit()
{
    var newstr=document.getElementById("pdiv").innerHTML;
    
    w=window.open('', '_blank', 'width=1100,height=600');
        w.document.write(newstr);
        w.print();
        w.close();
}
</script>
@endpush