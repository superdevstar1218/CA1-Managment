@extends('layouts.app', ['activePage' => 'my-schedule', 'titlePage' => __('User Schedule')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">My Schedule</h4>
                        <p class="card-category"> Here you can manage your schedule</p>
                    </div>
                    <div class="card-body">
                        <div class="col">
                            <div class="bmd-form-group pt-3 md-8 mb-5">
                                <div class="input-group d-flex align-items-center">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                          <label for="selectUserStatus">Date : </label>
                                        </span>
                                    </div>
                                    <input type="hidden" id="tabOption" value="1"/>
                                    <input type="date" id="editDate" value="{{date("Y-m-d")}}" min="{{substr(Auth::user()->created_at , 0 , 10)}}" max="{{date('Y-m-d')}}"/>
                                </div>
                            </div>
                            <div class="card card-nav-tabs card-plain " style="margin-top: 100px;">
                                <div class="card-header card-header-success">
                                    <!-- colors: "header-primary", "header-info", "header-success", "header-warning", "header-danger" -->
                                    <div class="nav-tabs-navigation">
                                        <div class="nav-tabs-wrapper">
                                            <ul class="nav nav-tabs" data-tabs="tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#home" data-toggle="tab" onclick="setTabOption(1)">Year</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#updates" data-toggle="tab" onclick="setTabOption(2)">Month</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#history" data-toggle="tab" onclick="setTabOption(3)">Day</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="tab-content text-center">
                                        <div class="tab-pane active" id="home">
                                            <div class="d-flex justify-content-end mb-5">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle='modal' data-target='#addScheduleModal'>Add Schedule</button>
                                            </div>
                                            <table id="yearScheduleTable" class="display dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Period
                                                        </th>
                                                        <th>
                                                            Content
                                                        </th>
                                                        <th>
                                                            IsDone
                                                        </th>
                                                        <th>
                                                            Other
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="updates">
                                        </div>
                                        <div class="tab-pane" id="history">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="editYearId"/>
                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Period
                                </div>
                            </div>
                            <input id="editYearPeriod" type="text" class="form-control" placeholder="....">
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Content
                                </div>
                            </div>
                            <textarea class="form-control" id="editYearContent" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    IsDone
                                </div>
                            </div>
                            <select class="form-control" id="editYearIsDone">
                                <option value="0">Not Done</option>
                                <option value="1">Done</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Other
                                </div>
                            </div>
                            <input class="form-control" id="editYearOther" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="onSaveChange()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Period
                                </div>
                            </div>
                            <input id="addYearPeriod" type="text" class="form-control" placeholder="....">
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Content
                                </div>
                            </div>
                            <textarea class="form-control" id="addYearContent" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    IsDone
                                </div>
                            </div>
                            <select class="form-control" id="addYearIsDone">
                                <option value="0">Not Done</option>
                                <option value="1">Done</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Other
                                </div>
                            </div>
                            <input class="form-control" id="addYearOther" placeholder="....."/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="onAddYearSchedule()">Add Schedule</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var editYearScheduleTable ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function editYearSchedule(id) {
            $("#editYearId").val(id) ;

            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.getOne')}}" ,
                data : {
                    id : id
                },
                success : function (resp) {
                    $("#editYearPeriod").val(resp.period) ;
                    $("#editYearContent").val(resp.content) ;
                    $("#editYearIsDone").val(0) ;
                    $("#editYearOther").val(resp.other) ;
                }
            })
        }
        function setTabOption(tabOption) {
            $("#tabOption").val(tabOption) ;
        }

        function deleteYearSchedule(id) {
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.deleteOne')}}" ,
                data : {
                    id : id
                },
                success : function (resp) {
                    alert(resp.status);
                    editYearScheduleTable.ajax.reload();
                }
            })
        }
        function onSaveChange() {
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.saveOne')}}" ,
                data : {
                   id : $("#editYearId").val() ,
                   period : $("#editYearPeriod").val() ,
                   content : $("#editYearContent").val() ,
                   isdone : $("#editYearIsDone").val(),
                   other : $("#editYearOther").val()
                },
                success : function (resp) {
                    if(resp.status == "success"){
                        alert("Changed Successfully!") ;
                    }
                    editYearScheduleTable.ajax.reload();
                }
            });
        }
        function onAddYearSchedule() {
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.addOne')}}" ,
                data : {
                    year : new Date( $("#editDate").val()).getFullYear() ,
                    period : $("#addYearPeriod").val() ,
                    content : $("#addYearContent").val() ,
                    isdone : $("#addYearIsDone").val(),
                    other : $("#addYearOther").val()
                },
                success : function (resp) {
                    if(resp.status == "success"){
                        alert("Add Successfully!") ;
                    }
                    editYearScheduleTable.ajax.reload();
                }
            });
        }

        $(document).ready(function () {
            editYearScheduleTable =  $('#yearScheduleTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'retrieve': true,
                'ajax': {
                    url: '/myschedule/' ,
                    type: 'post' ,
                    data: function (d) {
                        d.editDate = $("#editDate").val(),
                        d.tabOption =  $("#tabOption").val()
                    },
                },
                'columns': [
                    { data: 'period', name: 'period', searchable: true},
                    { data: 'content', name: 'content', searchable: true },
                    { data: 'isdone', name: 'isdone', searchable: true },
                    { data: 'other', name: 'other', searchable: true },
                    { data: 'action', name: 'action', searchable: true }
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
        });
    </script>
@endpush

