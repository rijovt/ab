<?php

namespace App\Http\Controllers;

use App\Client;
use App\Sale;
use App\Product;
use App\Item;
use Carbon\Carbon;
use App\SoldProduct;
use App\Transaction;
use App\PaymentMethod;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::latest()->paginate(25);

        return view('sales.index', compact('sales'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();

        return view('sales.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Sale $model)
    {
        $existent = Sale::where('client_id', $request->get('client_id'))->where('finalized_at', null)->get();

        if($existent->count()) {
            return back()->withError('There is already an unfinished sale belonging to this customer. <a href="'.route('sales.show', $existent->first()).'">Click here to go to it</a>');
        }

        $sale = $model->create($request->all());
        
        return redirect()
            ->route('sales.product.add', ['sale' => $sale->id])
            ->withStatus('Sale registered successfully, you can start registering products and transactions.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return view('sales.show', ['sale' => $sale]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()
            ->route('sales.index')
            ->withStatus('The sale record has been successfully deleted.');
    }

    public function finalize(Sale $sale)
    {
        $sale->total_amount = $sale->products->sum('total_amount');

        /*foreach ($sale->products as $sold_product) {
            $product_name = $sold_product->product->name;
            $product_stock = $sold_product->product->stock;
            if($sold_product->qty > $product_stock) return back()->withError("The product '$product_name' does not have enough stock. Only has $product_stock units.");
        }*/
        $last = Sale::where('inv_no', '!=', null)->where('active', 1)->orderBy('id', 'DESC')->first();
        $sale->inv_no = empty($last->inv_no) ? 1 : $last->inv_no+1;
        $sale->finalized_at = Carbon::now()->toDateTimeString();
        $sale->client->balance -= $sale->total_amount;
        $sale->save();
        $sale->client->save();

        return redirect()->route('sales.index')->withStatus('The sale has been successfully completed.');
    }

    public function print(Sale $sale)
    {
        return view('sales.print', ['sale' => $sale]);
    }

    public function print1(Sale $sale)
    {
        return view('sales.print1', ['sale' => $sale]);
    }

    public function addproduct(Sale $sale)
    {
        $products = Product::where('stock', '>', 0)->get();

        return view('sales.addproduct', compact('sale', 'products'));
    }

    public function storeproduct(Request $request, Sale $sale)
    {   
        $bar = Product::find($request->product_id);
        $item = Item::find($bar->item_id);
        if(config('app.SaleMode') == 'Include'){
            $request['tax_amt'] = round( ($request->total_amount/($item->tax+100))*$item->tax , 2);
        }
        else{
            $request['tax_amt'] = round( (($request->total_amount*$item->tax)/100), 2);
            $request['total_amount'] += $request['tax_amt'];
        }

        $soldProduct = SoldProduct::create($request->all());

        $bar->stock -= $request->qty;
        $bar->save();
                
        $soldProduct['bar'] = $bar->name;
        $soldProduct['item'] = $item->name;
        $soldProduct['total_amount'] = format_money($soldProduct->total_amount);
        $soldProduct['grand'] = format_money($sale->products->sum('total_amount'));        

        return response()->json($soldProduct, 200);
    }

    public function editproduct(Sale $sale, SoldProduct $soldproduct)
    {
        $products = Product::all();

        return view('sales.editproduct', compact('sale', 'soldproduct', 'products'));
    }

    public function updateproduct(Request $request, Sale $sale, SoldProduct $soldproduct)
    {
        $request->merge(['total_amount' => $request->get('price') * $request->get('qty')]);

        $soldproduct->update($request->all());

        return redirect()->route('sales.show', $sale)->withStatus('Product successfully modified.');
    }

    public function destroyproduct(Sale $sale, SoldProduct $soldproduct)
    {
        $soldproduct->delete();
     
        $soldproduct->product->stock += $soldproduct->qty;
        $soldproduct->product->save();        
        
        $soldProduct['grand'] = format_money($sale->products->sum('total_amount'));
        return response()->json($soldProduct, 200); 
    }

    public function addtransaction(Sale $sale)
    {
        $payment_methods = PaymentMethod::all();

        return view('sales.addtransaction', compact('sale', 'payment_methods'));
    }

    public function storetransaction(Request $request, Sale $sale)
    {
        switch($request->all()['type']) {
            case 'income':
                $request->merge(['title' => 'Payment Received from Sale ID: ' . $request->get('sale_id')]);
                break;

            case 'expense':
                $request->merge(['title' => 'Sale Return Payment ID: ' . $request->all('sale_id')]);

                if($request->get('amount') > 0) {
                    $request->merge(['amount' => (float) $request->get('amount') * (-1) ]);
                }
                break;
        }

        $transaction->create($request->all());

        return redirect()
            ->route('sales.show', compact('sale'))
            ->withStatus('Successfully registered transaction.');
    }

    public function edittransaction(Sale $sale, Transaction $transaction)
    {
        $payment_methods = PaymentMethod::all();

        return view('sales.edittransaction', compact('sale', 'transaction', 'payment_methods'));
    }

    public function updatetransaction(Request $request, Sale $sale, Transaction $transaction)
    {
        switch($request->get('type')) {
            case 'income':
                $request->merge(['title' => 'Payment Received from Sale ID: '. $request->get('sale_id')]);
                break;

            case 'expense':
                $request->merge(['title' => 'Sale Return Payment ID: '. $request->get('sale_id')]);

                if($request->get('amount') > 0) {
                    $request->merge(['amount' => (float) $request->get('amount') * (-1)]);
                }
                break;
        }
        $transaction->update($request->all());

        return redirect()
            ->route('sales.show', compact('sale'))
            ->withStatus('Successfully modified transaction.');
    }

    public function destroytransaction(Sale $sale, Transaction $transaction)
    {
        $transaction->delete();

        return back()->withStatus('Transaction deleted successfully.');
    }
}
