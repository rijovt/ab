<?php

namespace App\Http\Controllers;

use App\Receipt;
use App\Provider;
use App\Product;
use App\Item;

use Carbon\Carbon;
use App\ReceivedProduct;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Receipt  $model
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receipts = Receipt::paginate(25);

        return view('inventory.receipts.index', compact('receipts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::all();

        return view('inventory.receipts.create', compact('providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Receipt $receipt)
    {
        $receipt = $receipt->create($request->all());

        return redirect()
            ->route('receipts.show', $receipt)
            ->withStatus('Receipt registered successfully, you can start adding the products belonging to it.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        return view('inventory.receipts.show', compact('receipt'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();

        return redirect()
            ->route('receipts.index')
            ->withStatus('Receipt successfully removed.');
    }

    /**
     * Finalize the Receipt for stop adding products.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function finalize(Receipt $receipt)
    {
        $receipt->finalized_at = Carbon::now()->toDateTimeString();
        $receipt->save();

        foreach($receipt->products as $receivedproduct) {
            $product = Product::create([
                    'name' => $receivedproduct->barcode,
                    'item_id' => $receivedproduct->item_id,
                    'product_category_id' => $receivedproduct->item->product_category_id,
                    'price' => $receivedproduct->selling_price,
                    'stock' => $receivedproduct->stock
                ]);

            $receivedproduct->product_id = $product->id;
            $receivedproduct->save();
        }

        return back()->withStatus('Receipt successfully completed.');
    }

    /**
     * Add product on Receipt.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function addproduct(Receipt $receipt)
    {
        $items = Item::all();

        return view('inventory.receipts.addproduct', compact('receipt', 'items'));
    }

    /**
     * Add product on Receipt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function storeproduct(Request $request)
    {
        $request->validate([
            'receipt_id' => 'required',
            'item_id' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'selling_price' => 'required',
        ]);

        $last = ReceivedProduct::where('item_id', $request->item_id)->orderBy('id', 'DESC')->first();
        $item = Item::find($request->item_id);
        if(!empty($last->barcode))
            $no = (str_replace($item->prefix,'',$last->barcode))+1;
        else
            $no = 1000;
        $request->request->add( ['barcode' => $item->prefix.$no] );

        $receivedproduct = ReceivedProduct::create($request->all());
        $receivedproduct['total']= round($receivedproduct->price * $receivedproduct->stock,1);
        $receivedproduct->item->category->name;

        return response()->json(['code'=>200, 'message'=>'Product Added successfully','data' => $receivedproduct], 200);
    }

    /**
     * Editor product on Receipt.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function editproduct(Receipt $receipt, ReceivedProduct $receivedproduct)
    {
        $products = Product::all();

        return view('inventory.receipts.editproduct', compact('receipt', 'receivedproduct', 'products'));
    }

    /**
     * Update product on Receipt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function updateproduct(Request $request, Receipt $receipt, ReceivedProduct $receivedproduct)
    {
        $receivedproduct->update($request->all());

        return redirect()
            ->route('receipts.show', $receipt)
            ->withStatus('Product edited successfully.');
    }

    /**
     * Add product on Receipt.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroyproduct(ReceivedProduct $receivedproduct)
    {
        $receivedproduct->delete();

        return response()->json(['success'=>'Product Deleted successfully']);        
    }
}
