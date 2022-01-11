
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
                            <div class="col-sm-12 col-md-9">
                                <label for="start">Select Date Range:</label>
                                <?php date_default_timezone_set('Asia/Tokyo'); ?>
                                &nbsp;
                                <input type="date" id="date_start" name="date_start" value="{{date('Y-m-d' , time() - 60*60*24 )}}" max="{{date('Y-m-d' , time() - 60*60*24 )}}"/>
                                &nbsp;
                                <input type="date" id="date_end" name="date_end" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}"/>
                                &nbsp;
                            </div>
                            <div class="col-sm-12 col-md-3 text-right">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddMoneyLogModal">
                                    Add Log
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id= "empTable">
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
    <script>
        $('#date_start').datepicker().on("input change", function(e) {
            start_dates = $('#date_start').val();
            $('#date_end').attr({
                min: start_dates
            })
            table.ajax.reload();
        });

        $('#date_end').datepicker().on("input change", function(e) {
            end_dates = $('#date_end').val();
            $('#date_start').attr({
                max: end_dates
            })
            table.ajax.reload();
        });
        $('#date_start').datepicker().on("input click" , function(e) {
            $("#ui-datepicker-div").hide() ;
        });
        $('#date_end').datepicker().on("input click" , function(e) {
            $("#ui-datepicker-div").hide() ;
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#empTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'retrieve': true,
            'ajax': {
                url: '/mylogs',
                type: 'post',
                data: function(d) {
                    d.start_date =  start_dates;
                    d.end_date =  end_dates;
                    // d.projects = projects.length == 0 ? projects_all: projects;
                    // d.categories = categories.length == 0 ? categories_all: categories;
                },
            },
            'columns': [{
                    data: 'status',
                    name: 'status',
                    searchable: true
                },
                {
                    data: 'project',
                    name: 'project',
                    searchable: true
                },
                {
                    data: 'start',
                    name: 'start',
                    searchable: true
                },
                {
                    data: 'end',
                    name: 'end',
                    searchable: true
                },
                {
                    data: 'comment',
                    name: 'comment',
                    searchable: true
                },
            ],
            'initComplete': function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val()).draw();
                        });
                });
            }
        });
    </script>
@endpush

