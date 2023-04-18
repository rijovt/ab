<?php

namespace App\Http\Controllers;

use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Order::where('finalized_at','!=',null);
        $order = $color = '';
        if(!empty($request->order)){
            $order = trim($request->order);
            $query = $query->where('order_no', 'LIKE', '%' . $order . '%');
        }
        if(!empty($request->color)){
            $color = trim($request->color);
            $query = $query->where('color', 'LIKE', '%' . $color . '%');
        }
        $orders = $query->orderBy('id', 'DESC')->paginate(20);
        return view('orders.index', compact('orders','order','color'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orders = Order::where('finalized_at', null)->where("user_id", auth()->user()->id)->orderBy('id', 'DESC')->get();

        return view('orders.create', compact('orders'));
    }

    public function finalize()
    {
        Order::where('finalized_at', null)->where("user_id", auth()->user()->id)->update(['finalized_at' => Carbon::now()->toDateTimeString()]);

        return redirect()->route('orders.index')->withStatus('The order saved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addorder(Request $request)
    {
        $requestData = $request->all();
        $requestData['color'] = mb_strtoupper($requestData['color']);
        $requestData['user_id'] = auth()->user()->id;
        $createdOrder = Order::create($requestData);
        return response()->json($createdOrder, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroyorder(Order $order)
    {
        $order->delete();
        return response()->json($order, 200); 
    }
}
