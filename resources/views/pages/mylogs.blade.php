@extends('layouts.app', ['activePage' => 'my-logs', 'titlePage' => __('User Logs')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">My Logs</h4>
                            <p class="card-category"> Here you can see your logs</p>
                        </div>
                        <div class="card-body">
                            <label for="start">Select Date Range:</label>
                            <?php date_default_timezone_set('Asia/Tokyo'); ?>
                            &nbsp;
                                        <input type="date" id="date_start" name="date_start" value="{{date('Y-m-d' , time() - 60*60*24 )}}" max="{{date('Y-m-d' , time() - 60*60*24 )}}"/>
                                        &nbsp;
                                        <input type="date" id="date_end" name="date_end" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}"/>
                                        &nbsp;
                            <!-- <select id="project" multiple="multiple">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                            <button id="project-toggle" class="btn btn-primary">Select All</button>
                            <select id="category" multiple="multiple">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <button id="category-toggle" class="btn btn-primary">Select All</button> -->
                            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                            <table id="empTable" class="display dataTable">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Project</th>
                                        <th>Start time</th>
                                        <th>End time</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <script>
            var start_dates = "{{date('Y-m-d' , time() - 60*60*24 )}}";
            var end_dates = "{{date('Y-m-d')}}";
            var categories = [];
            var projects = [];
            var categories_all = [];
            var projects_all = [];
            
            @foreach ($categories as $category)
              categories_all.push({{$category->id}})
            @endforeach
            @foreach ($projects as $project)
            projects_all.push({{ $project->id }})
            @endforeach
            function multiselect_selected($el) {
                var ret = true;
                $('option', $el).each(function(element) {
                    if (!!!$(this).prop('selected')) {
                        ret = false;
                    }
                });
                return ret;
            }

            function multiselect_selectAll($el) {
                $('option', $el).each(function(element) {
                    $el.multiselect('select', $(this).val());
                });
            }

            function multiselect_deselectAll($el) {
                $('option', $el).each(function(element) {
                    $el.multiselect('deselect', $(this).val());
                });
            }

            function multiselect_toggle($el, $btn) {
                if (multiselect_selected($el)) {
                    multiselect_deselectAll($el);
                    $btn.text("Select All");
                } else {
                    multiselect_selectAll($el);
                    $btn.text("Deselect All");
                }
            }
            $(document).ready(function() {
                $("#date_start , #date_end").on("change" , function(e){
            drawPieChart();
        });
 $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function drawPieChart() {
            $.ajax({
                method : "POST" ,
                url : "{{url('/analysis_registry')}}" ,
                data : {
                    user_id: "{{Auth::id()}}",
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
                                text: "my log"
                            }
                        }
                    });
                }
            });
        }

        drawPieChart() ;

                $('#project').multiselect({
                    onChange: function(element, checked) {
                      projects = $('#project').val();
                      table.ajax.reload();
                    }
                });
                $('#category').multiselect({
                    onChange: function(element, checked) {
                      categories = $('#category').val();
                      table.ajax.reload();
                    }
                });
                $("#project-toggle").click(function(e) {
                    e.preventDefault();
                    multiselect_toggle($("#project"), $(this));
                    projects = $('#project').val();
                    table.ajax.reload();
                });
                $("#category-toggle").click(function(e) {
                    e.preventDefault();
                    multiselect_toggle($("#category"), $(this));
                    categories = $('#category').val();
                    table.ajax.reload();
                });
            });
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
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#date_start').datepicker().on("input click" , function(e) {
            $("#ui-datepicker-div").hide() ;
        });
        $('#date_end').datepicker().on("input click" , function(e) {
            $("#ui-datepicker-div").hide() ;
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
