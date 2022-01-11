<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MoneyLog;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Project;
use Yajra\Datatables\Datatables;

class MoneyLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $moneylogs = MoneyLog::all();
        $projects = Project::all();
        $currencies = Currency::all();
        $customers = Customer::all();
        return view('pages.moneylogs', compact('moneylogs', 'projects', 'currencies', 'customers'));
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
    public function store(Request $data)
    {
        //
        $new_moneylog = new MoneyLog ;

        $new_moneylog->received_date = $data['received_date'] ;
        $new_moneylog->customer_id = $data['customer'] ;
        $new_moneylog->currency_id = $data['currency'] ;
        $new_moneylog->amount = $data['amount'] ;
        $new_moneylog->project_id = $data['project'] ;

        $new_moneylog->save();

        return response()->json(array('result' => true, 'success' => 'MoneyLog Created'));
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
        $data = $request->all();
        $moneylog = MoneyLog::find($id);

        $moneylog->received_date = $data['received_date'] ;
        $moneylog->customer_id = $data['customer'] ;
        $moneylog->currency_id = $data['currency'] ;
        $moneylog->amount = $data['amount'] ;
        $moneylog->project_id = $data['project'] ;

        $moneylog->save();

        return response()->json(array('result' => true, 'success' => 'MoneyLog Updated'));
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

    public function  getDetail(Request $request) {
        date_default_timezone_set("Asia/Tokyo");

        $first_date =   $request->post('start_date') ;
        $last_date =   $request->post('end_date') ;

        $first_date = date($first_date." 00:00:00") ;
        $last_date = date($last_date." 23:59:59") ;

        $moneylogs = MoneyLog::whereBetween('received_date' , [ $first_date , $last_date ] )->orderBy('received_date' , 'asc')->get() ;

        return Datatables::of($moneylogs)
            ->addColumn('customer', function ($row) {
                $customer = $row->customer->email ;
                return $customer;
            })
            ->addColumn('currency', function ($row) {
                $currency = $row->currency->name ;
                return $currency;
            })
            ->addColumn('project', function ($row) {
                $project = $row->project->name ;
                return $project;
            })
            ->addColumn('actions' , function($row) {
                $dates = explode('-', $row->received_date);
                return '<a onclick="setedit('.$row->id.','.$row->customer_id.','.$row->currency_id.','.$row->amount.','.$row->project_id.','.$dates[0].','.$dates[1].','.$dates[2].')" data-toggle="modal" data-target="#EditMoneyLogModal" class="btn btn-primary btn-sm" style="color:#fff"> Edit </a>';
            })
            ->rawColumns(['received_date' , 'customer' , 'currency' , 'project' , 'actions'])
            ->make(true);
    }
}
