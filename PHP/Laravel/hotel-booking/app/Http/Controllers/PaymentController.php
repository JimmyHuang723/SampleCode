<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pay(Request $request){
 
        $token  = $request['token'];
        $cutomer_id  =$request['customer_id'];
        $total_price  =$request['total']*100;
 
        $customer = Customer::find($cutomer_id);
 
        if($customer->charge($total_price,
            [
            'source' => $token,
             'receipt_email' => $customer->email
            ]))
        {
            $mess = ['status' => "OK","message" =>"Payment ok"];
            return $mess;
        }else{
            $mess = ['status' => "ERROR","message" =>"Error submitting payment"];
            return $mess;
        }
    }

}
