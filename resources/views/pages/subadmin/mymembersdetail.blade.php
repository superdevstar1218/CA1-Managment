@extends('layouts.app', ['activePage' => 'my-members', 'titlePage' => __('My Members Detail')])

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
              <label for="start">Select Date Range:</label>

              <input type="date" id="date_start" name="date-start"
                   
                      min="2021-01-01" max= "<?php
                  echo date('Y-m-d');
                  ?>"
                  >
                  <input type="date" id="date_end" name="date-end"
                      
                      min="2021-01-01" max= "<?php
                  echo date('Y-m-d');
                  ?>"
                  >
              <table id="empTable" class="display dataTable">
                <thead>
                  <tr>
                    <th>Status</th>
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
  <script>
    var start_dates = '';
      var end_dates = '';
        $('#date_start').datepicker().on("input change", function (e) {
            start_dates = $('#date_start').val();
            $('#date_end').attr({min: start_dates})
            table.ajax.reload();
        });

        $('#date_end').datepicker().on("input change", function (e) {
          end_dates = $('#date_end').val();
          $('#date_start').attr({max: end_dates})
            table.ajax.reload();
        });

    
     var table = $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'retrieve': true,
                'ajax': {
                  url: '/mymembersdetail/' + '{{$id}}',
                  type: 'post',
                  data: function (d) {
                        d.start_date = start_dates;
                        d.end_date = end_dates;
                    },
                },
                'columns': [
                    { data: 'status', name: 'status', searchable: true},
                    { data: 'start_at', name: 'start_at', searchable: true },
                    { data: 'end', name: 'end', searchable: true },
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
                }
            });
   
  </script>
@endpush

