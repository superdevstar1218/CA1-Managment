
@extends('layouts.app', ['activePage' => 'moneylogs', 'titlePage' => __('Money Logs')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Money Logs</h4>
                        <p class="card-category"> Here you can manage money logs</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddCategoryModal">
                                    Add Log
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>
                                            ID
                                        </th>
                                        <th>
                                            Received Date
                                        </th>
                                        <th>
                                            Customer
                                        </th>
                                        <th>
                                            Currency
                                        </th>
                                        <th>
                                            Amount
                                        </th>
                                        <th>
                                            Project
                                        </th>
                                        <th class="text-right">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
        
@endpush

