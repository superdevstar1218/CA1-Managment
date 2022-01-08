@extends('layouts.app', ['activePage' => 'user-status', 'titlePage' => __('User Detail')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">{{$user->name}}</h4>
                            <p class="card-category"> Here you can see logs</p>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{route('status.editstatus')}}" method="post" class="form-horizontal">
                                    <div>
                                        <?php date_default_timezone_set('Asia/Tokyo'); ?>
                                        <label for="start">Select Date Range:</label>
                                        @csrf
                                        <input type="hidden" id="user_id" name="user-id" value="<?php echo $user->id ?>" />
                                        &nbsp;
                                        <input type="date" id="date_start" name="date_start" value="{{date('Y-m-d' , time() - 60*60*24 )}}" max="{{date('Y-m-d' , time() - 60*60*24 )}}"/>
                                        &nbsp;
                                        <input type="date" id="date_end" name="date_end" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}"/>
                                        &nbsp;
                                        <input type="submit" class="btn btn-sm btn-primary" id="btn_edit" value="Edit" />
                                    </div>
                                </form>
                            </div>
                            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                            <table id="empTable" class="display dataTable">
                                <thead>
                                    <tr>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Start time
                                        </th>
                                        <th>
                                            End time
                                        </th>
                                        <th>
                                            Comment
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
<script src="{{ asset('material') }}/js/plugins/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function drawPieChart () {
            $.ajax({
                method : "POST" ,
                url : "{{url('/analysis_registry')}}" ,
                data : {
                    user_id : "{{$user->id}}" ,
                    date_start : new Date( $("#date_start").val() ).getUTCFullYear() + "-" + (new Date( $("#date_start").val() ).getUTCMonth() + 1) + "-" + new Date( $("#date_start").val() ).getUTCDate() + " " + "00:00:00",
                    date_end : new Date( $("#date_end").val() ).getUTCFullYear() + "-" + (new Date( $("#date_end").val() ).getUTCMonth() + 1) + "-" + new Date( $("#date_end").val() ).getUTCDate() + " " + "23:59:59"
                },
                success : function (resp) {
                    var xValues = [];
                    var barColors = [] ;
                    var yValues = [] ;

                    @foreach($categories as $category)
                        xValues.push("{{$category->name}}") ;
                        barColors.push("{{$category->bgcolor}}") ;
                        if(resp.pieDatas["{{$category->id}}"] === "undefined"){
                            yValues.push(0) ;
                        } else {
                            yValues.push(resp.pieDatas["{{$category->id}}"]) ;
                        }
                    @endforeach

                    new Chart("myChart", {
                        type: "pie",
                        data: {
                            labels: xValues,
                            datasets: [{
                                backgroundColor: barColors,
                                data: yValues
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: "{{$user->name}}" + "'s log"
                            }
                        }
                    });
                }
            });
        }

        drawPieChart() ;

        var table =  $('#empTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'retrieve': true,
            'ajax': {
                url: '/statusdetail/' + '{{$id}}',
                type: 'post',
                data: function (d) {
                    d.start_date = $("#date_start").val(),
                    d.end_date = $("#date_end").val()
                },
            },
            'columns': [
                { data: 'status', name: 'status', searchable: true},
                { data: 'start', name: 'start', searchable: true },
                { data: 'endStr', name: 'endStr', searchable: true },
                { data: 'comment', name: 'comment', searchable: true },
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
        $("#date_start , #date_end").on("change" , function(e){
            drawPieChart();
        });
        $('#date_start').datepicker().on("input click" , function(e) {
            $("#ui-datepicker-div").hide() ;
            table.ajax.reload();
        });
        $('#date_end').datepicker().on("input click" , function(e) {
            $("#ui-datepicker-div").hide() ;
            table.ajax.reload();
        });
     })
  </script>
@endpush

