<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customers = Customer::all();
        return view('pages.customers', compact('customers'));
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
     * @param  \Illuminate\Http\data  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $data)
    {
        //
        // $validator = Validator::make($data->all(), [
        //     'email' => ['required', 'string', 'max:255', 'unique:customers'],
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(array('result' => false, 'errors' => $validator->getMessageBag()->toArray()));
        // }

        $new_customer = new Customer ;

        $new_customer->firstname = $data['firstname'] ;
        $new_customer->lastname = $data['lastname'] ;
        $new_customer->email = $data['email'] ;

        $new_customer->save();

        return response()->json(array('result' => true, 'success' => 'Customer Created'));
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
     * @param  \Illuminate\Http\request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->all();
        $customer = Customer::find($id);

        $customer->firstname = $data['firstname'];
        $customer->lastname = $data['lastname'];
        $customer->email = $data['email'] ;

        $customer->save();

        return response()->json(array('result' => true, 'success' => 'customer Updated'));
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
}
