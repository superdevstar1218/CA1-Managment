
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
                        <table class="display dataTable" id= "empTable" style="width:100%">
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="AddMoneyLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Money Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <div class="modal-body">
                    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
                    <span class="error" style="color:red; margin-top:10px; margin-bottom: 10px;"></span>

                    <form class="form" id="MoneyLogCreate">
                        @csrf

                        <div class=" card-login card-hidden mb-3">
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Customer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <select class="form-control" name="customer" id="customer">
                                            <option> </option>
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}"> {{$customer->email}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Currency&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <select class="form-control" name="currency">
                                            <option> </option>
                                            @foreach ($currencies as $currency)
                                                <option value="{{$currency->id}}"> {{$currency->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" name="amount" class="form-control" placeholder="{{ __('Amount...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Project&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <select class="form-control" name="project">
                                            <option> </option>
                                            @foreach ($projects as $project)
                                                <option value="{{$project->id}}"> {{$project->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Project&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="date" id="received_date" name="received_date" value="{{date('Y-m-d')}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="justify-content-center">
                                <button type="button" class="btn btn-primary btn-link btn-lg save-data">{{ __('Create Log') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditMoneyLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Money Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <div class="modal-body">
                    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
                    <span class="error" style="color:red; margin-top:10px; margin-bottom: 10px;"></span>

                    <form class="form" id="MoneyLogCreate">
                        @csrf
                        <input type="text" name="edit_id" id="edit_id" hidden>

                        <div class=" card-login card-hidden mb-3">
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Customer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <select class="form-control" name="edit_customer" id="edit_customer">
                                            <option> </option>
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}"> {{$customer->email}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Currency&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <select class="form-control" name="edit_currency" id="edit_currency">
                                            <option> </option>
                                            @foreach ($currencies as $currency)
                                                <option value="{{$currency->id}}"> {{$currency->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" name="edit_amount" id="edit_amount" class="form-control" placeholder="{{ __('Amount...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Project&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <select class="form-control" name="edit_project" id="edit_project">
                                            <option> </option>
                                            @foreach ($projects as $project)
                                                <option value="{{$project->id}}"> {{$project->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Project&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="date" id="edit_received_date" name="received_date" value="{{date('Y-m-d')}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="justify-content-center">
                                <button type="button" class="btn btn-primary btn-link btn-lg edit-data">{{ __('Update Log') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function setedit(id, customer, currency, amount, project, year, month, day) {
            $("#edit_id").val(id) ;
            $("#edit_customer").val(customer) ;
            $("#edit_currency").val(currency) ;
            $("#edit_project").val(project) ;
            $('#edit_amount').val(amount) ;
            
            let received_date = new Date(year, month - 1, day);
            const dateFormatter = Intl.DateTimeFormat('sv-SE');
            $('#edit_received_date').val(dateFormatter.format(received_date)) ;
        }
        $(document).ready(function() {
            $(".edit-data").click(function(event){
                event.preventDefault();

                let id = $("#edit_id").val();
                let customer = $("#edit_customer").val();
                let currency = $("#edit_currency").val();
                let amount = $("#edit_amount").val();
                let project = $("#edit_project").val();
                let received_date = $("#edit_received_date").val();
                let _token   = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "/moneylog/" + id,
                    type:"PUT",
                    data:{
                        customer: customer,
                        currency: currency,
                        amount : amount ,
                        project : project,
                        received_date: received_date,
                        _token: _token
                    },
                    success:function(response){
                        if(response) {
                            if(response.result){
                                $('.success').text(response.success);
                                $('.error').hide();
                                window.location.reload();
                            } else {
                                $('.error').text(response.errors);
                                $('.success').hide();
                            }
                        }
                    },
                });
            });
            $(".save-data").click(function(event){
                event.preventDefault();

                let customer = $("select[name=customer]").val();
                let currency = $("select[name=currency]").val();
                let amount = $("input[name=amount]").val();
                let project = $("select[name=project]").val();
                let received_date = $("input[name=received_date]").val();
                let _token   = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "/moneylog",
                    type:"POST",
                    data:{
                        customer: customer,
                        currency: currency,
                        amount : amount ,
                        project : project,
                        received_date: received_date,
                        _token: _token
                    },
                    success:function(response){
                        if(response) {
                            if(response.result){
                                $('.success').text(response.success);
                                $('.error').hide();
                                window.location.reload();
                            } else {
                                $('.error').text(response.errors);
                                $('.success').hide();
                            }
                        }
                    },
                });
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table =  $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'retrieve': true,
                'ajax': {
                    url: '/moneylogdetail',
                    type: 'post',
                    data: function (d) {
                        d.start_date = $("#date_start").val(),
                        d.end_date = $("#date_end").val()
                    },
                },
                'columns': [
                    { data: 'id', name: 'id', searchable: true},
                    { data: 'received_date', name: 'received_date', searchable: true },
                    { data: 'customer', name: 'customer', searchable: true },
                    { data: 'currency', name: 'currency', searchable: true },
                    { data: 'amount', name: 'amount', searchable: true },
                    { data: 'project', name: 'project', searchable: true },
                    { data: 'actions', name: 'actions', searchable: true, class: 'td-actions text-right' },
                ],
                'initComplete': function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement("input");

                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val()).draw();
                            });
                    });
                },
            });
            $('#date_start').datepicker().on("input click" , function(e) {
                $("#date_end").attr('min' , $("#date_start").val());
                if($("#date_end").val() < $("#date_start").val())
                    $("#date_end").val($("#date_start").val());
                $("#analysis_info").html('... Loading') ;
                $("#ui-datepicker-div").hide() ;
                table.ajax.reload();
            });
            $('#date_end').datepicker().on("input click" , function(e) {
                $('#date_start').attr('max', $('#date_end').val());
                if($('#date_start').val() > $('#date_end').val())
                    $('#date_start').val($('#date_end').val());
                $("#analysis_info").html('... Loading') ;
                $("#ui-datepicker-div").hide() ;
                table.ajax.reload();
            });
        });
    </script>
@endpush

