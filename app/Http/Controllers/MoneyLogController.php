<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MoneyLog;
use App\Models\Customer;
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
        $customers = Customer::all();
        return view('pages.moneylogs', compact('moneylogs', 'projects', 'customers'));
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
        $new_moneylog->currency = $data['currency'] ;
        $new_moneylog->amount = $data['amount'] ;
        $new_moneylog->fee = $data['fee'] ;
        $new_moneylog->real_pay = $data['real_pay'] ;
        $new_moneylog->project_id = $data['project'] ;
        $new_moneylog->comment = $data['comment'] ;

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
        $moneylog->currency = $data['currency'] ;
        $moneylog->amount = $data['amount'] ;
        $moneylog->fee = $data['fee'] ;
        $moneylog->real_pay = $data['real_pay'] ;
        $moneylog->project_id = $data['project'] ;
        $moneylog->comment = $data['comment'] ;

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
        $customers = $request->post('customers');
        $projects = $request->post('projects');

        $first_date = date($first_date." 00:00:00") ;
        $last_date = date($last_date." 23:59:59") ;

        $moneylogs = MoneyLog::whereBetween('received_date' , [ $first_date , $last_date ] )
                ->whereIn('project_id', $projects)->whereIn('customer_id', $customers)->orderBy('received_date' , 'asc')->get() ;

        return Datatables::of($moneylogs)
            ->addColumn('customer', function ($row) {
                $customer = $row->customer->firstname.' '.$row->customer->lastname ;
                return '<a href="customer/detail/'.$row->customer_id.'">'.$customer.'</a>';
            })
            ->addColumn('project', function ($row) {
                $project = $row->project->name ;
                return $project;
            })
            ->addColumn('actions' , function($row) {
                $dates = explode('-', $row->received_date);
                return '<a onclick="setedit('.$row->id.','.$row->customer_id.',\''.$row->currency.'\',\''.$row->amount.'\',\''.$row->fee.'\',\''.$row->real_pay.'\',\''.$row->project_id.'\',\''.$row->comment.'\','.$dates[0].','.$dates[1].','.$dates[2].')" data-toggle="modal" data-target="#EditMoneyLogModal" class="btn btn-primary btn-sm" style="color:#fff"> Edit </a>';
            })
            ->rawColumns(['received_date' , 'customer' , 'project' , 'actions'])
            ->make(true);
    }

    public function customer_analysis(Request $request) {

        $moneylog_pie_chart_data = [] ;

        // $date_start = $request->post('date_start') ;
        // $date_end = $request->post('date_end') ;
        $moneylogs = MoneyLog::get() ;

        $total_pay = 0;
        $customer_pay = [];
        foreach($moneylogs as $moneylog) {
            $total_pay += $moneylog->real_pay;
            if(!isset($moneylog_pie_chart_data[$moneylog->customer_id])){
                $moneylog_pie_chart_data[$moneylog->customer_id] = 0 ;
                $customer_pay[$moneylog->customer_id] = 0;
            }
            $moneylog_pie_chart_data[$moneylog->customer_id] += $moneylog->real_pay ;
            $customer_pay[$moneylog->customer_id] += $moneylog->real_pay ;
        }

        foreach($moneylog_pie_chart_data as $key => $value) {
            $moneylog_pie_chart_data[$key] = $total_pay ? round( $value  * 100.0 / $total_pay , 2 ) : 0 ;
        }

        return response()->json([
            'pieDatas' => $moneylog_pie_chart_data ,
            'customer_pay' => $customer_pay ,
        ]);
    }
    
    public function project_analysis(Request $request) {

        $moneylog_pie_chart_data = [] ;

        // $date_start = $request->post('date_start') ;
        // $date_end = $request->post('date_end') ;
        $moneylogs = MoneyLog::get();

        $total_pay = 0;
        $project_pay = [];
        foreach($moneylogs as $moneylog) {
            $total_pay += $moneylog->real_pay;
            if(!isset($moneylog_pie_chart_data[$moneylog->project_id])){
                $moneylog_pie_chart_data[$moneylog->project_id] = 0 ;
                $project_pay[$moneylog->project_id] = 0;
            }
            $moneylog_pie_chart_data[$moneylog->project_id] += $moneylog->real_pay ;
            $project_pay[$moneylog->project_id] += $moneylog->real_pay ;
        }

        foreach($moneylog_pie_chart_data as $key => $value) {
            $moneylog_pie_chart_data[$key] = $total_pay ? round( $value  * 100.0 / $total_pay , 2 ) : 0 ;
        }

        return response()->json([
            'pieDatas' => $moneylog_pie_chart_data ,
            'project_pay' => $project_pay ,
        ]);
    }
    
    public function analysis(Request $request) {

        $moneylog_pie_chart_data = [] ;

        // $date_start = $request->post('date_start') ;
        // $date_end = $request->post('date_end') ;
        // $moneylogs = MoneyLog::whereBetween('received_date' , [$date_start, $date_end])->orderBy('received_date', 'ASC')->get();
        $moneylogs = MoneyLog::orderBy('received_date', 'ASC')->get();
        return response()->json([
            'analysis_moneylogs' => $moneylogs ,
        ]);
    }
}
