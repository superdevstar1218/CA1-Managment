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
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle='modal' data-target='#addScheduleModal'>Add Schedule</button>
                                </div>
                            </div>
                            <div class="card card-nav-tabs card-plain " style="margin-top: 100px;">
                                <div class="card-header card-header-success">
                                    <!-- colors: "header-primary", "header-info", "header-success", "header-warning", "header-danger" -->
                                    <div class="nav-tabs-navigation">
                                        <div class="nav-tabs-wrapper">
                                            <ul class="nav nav-tabs" data-tabs="tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#Year" data-toggle="tab" onclick="setTabOption(1)">Year</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#Month" data-toggle="tab" onclick="setTabOption(2)">Month</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#Week" data-toggle="tab" onclick="setTabOption(3)">Week</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#Day" data-toggle="tab" onclick="setTabOption(4)">Day</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="tab-content text-center">
                                        <div class="tab-pane active" id="Year">
                                        <div class="tab-pane active" id="home">
                                            <div class="d-flex justify-content-end mb-5">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle='modal' data-target='#addYearScheduleModal'>Add Schedule</button>
                                            </div>
                                            <table id="yearScheduleTable" class="display dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Content
                                                        </th>
                                                        <th>
                                                            IsDone
                                                        </th>
                                                        <th>
                                                            Comment
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="Month">
                                            <table id="monthScheduleTable" class="display dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Content
                                                        </th>
                                                        <th>
                                                            IsDone
                                                        </th>
                                                        <th>
                                                            Comment
                                                        </th>
                                                        <th>
                                                            Action                                                                
                                                        </th>
                                                    </tr>
                                        <div class="tab-pane" id="updates">
                                            <div class="d-flex justify-content-end mb-5">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle='modal' data-target='#addMonthScheduleModal'>Add Schedule</button>
                                            </div>
                                            <table id="monthScheduleTable" class="display dataTable">
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
                                        <div class="tab-pane" id="Week">
                                            <table id="weekScheduleTable" class="display dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Content
                                                        </th>
                                                        <th>
                                                            Isdone
                                                        </th>
                                                        <th>
                                                            Comment
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>

                                        <div class="tab-pane" id="Day">
                                            <table id="dayScheduleTable" class="display dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Content
                                                        </th>
                                                        <th>
                                                            Isdone
                                                        </th>
                                                        <th>
                                                            Comment
                                                        </th>
                                                        <th>
                                                            Action
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
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editYearScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModal" aria-hidden="true">
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
                                    Content
                                </div>
                            </div>
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
                                    Comment
                                </div>
                            </div>
                            <input class="form-control" id="editYearOther" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="onYearSaveChange()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addYearScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addYearScheduleModal" aria-hidden="true">
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
                                    Content
                                </div>
                            </div>
                            <textarea class="form-control" id="Schedule_Content" rows="1"></textarea>
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Schedule Type
                                </div>
                            </div>
                            <select class="form-control" id="Schedule_Type">
                                <option value="0">Yearly Schedule</option>
                                <option value="1">Monthly Schedule</option>
                                <option value="2">Weekly Schedule</option>
                                <option value="2">Daily Schedule</option>
                            </select>
                        </div>
                        <br />
                        <div id="editor">
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    IsDone
                                </div>
                            </div>
                            <select class="form-control" id="Schedule_IsDone">
                                <option value="0">Not Done</option>
                                <option value="1">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Comment
                                </div>
                            </div>
                            <input class="form-control" id="Schedule_Comment" placeholder="....."/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="onAddSchedule()">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editMonthScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="editMonthId"/>
                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Period
                                </div>
                            </div>
                            <input id="editMonthPeriod" type="text" class="form-control" placeholder="....">
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Content
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    IsDone
                                </div>
                            </div>
                            <select class="form-control" id="editMonthIsDone">
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
                            <input class="form-control" id="editMonthOther" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="onMonthSaveChange()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addMonthScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addMonthScheduleModal" aria-hidden="true">
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
                            <input id="addMonthPeriod" type="text" class="form-control" placeholder="....">
                        </div>
                    </div>

                    <div class="form-group bmd-form-group">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    Content
                                </div>
                            </div>
                        </div>
                        <br />
                        <div id="editor">

                        </div>
                    </div>

                    <div class="form-group bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    IsDone
                                </div>
                            </div>
                            <select class="form-control" id="addMonthIsDone">
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
                            <input class="form-control" id="addMonthOther" placeholder="....."/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="onAddMonthSchedule()">Add Schedule</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <script>
        let editor ;
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .then( newEditor => {
                editor = newEditor ;
            })
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script>
        var editYearScheduleTable ;
        var editMonthScheduleTable ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function editYearSchedule(id) {
            $("#editYearId").val(id) ;

            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.getOneYear')}}" ,
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
                url : "{{route('myschedule.deleteOneYear')}}" ,
                data : {
                    id : id
                },
                success : function (resp) {
                    alert(resp.status);
                    editYearScheduleTable.ajax.reload();
                }
            })
        }
        function onYearSaveChange() {
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.saveOneYear')}}" ,
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
        function convert(str) {
            var date = new Date(str), mnth = ("0" + (date.getMonth() + 1)).slice(-2), day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        }

        function onAddSchedule() {
            let schedule_date = new Date($("#editDate").val());
            let send_year = convert(schedule_date);
            console.log(send_year);
        function onAddYearSchedule() {
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.addOneYear')}}" ,
                data : {
                    year :  send_year,
                    content : $("#Schedule_Content").val() ,
                    type : $("#Schedule_Type").val() ,
                    isdone : $("#Schedule_IsDone").val(),
                    comment : $("#Schedule_Comment").val()
                },
                success : function (resp) {
                    if(resp.status == "success"){
                        alert("Add Successfully!") ;
                    }
                    editYearScheduleTable.ajax.reload();
                }
            });
        }
        function editMonthSchedule(id) {
            $("#editMonthId").val(id) ;
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.getOneMonth')}}" ,
                data : {
                    id : id
                },
                success : function (resp) {
                    $("#editMonthPeriod").val(resp.period) ;
                    $("#editMonthContent").val(resp.content) ;
                    $("#editMonthIsDone").val(0) ;
                    $("#editMonthOther").val(resp.other) ;
                }
            })
        }
        function deleteMonthSchedule(id) {
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.deleteOneMonth')}}" ,
                data : {
                    id : id
                },
                success : function (resp) {
                    alert(resp.status);
                    editMonthScheduleTable.ajax.reload();
                }
            })
        }
        function onMonthSaveChange() {
            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.saveOneMonth')}}" ,
                data : {
                    id : $("#editMonthId").val() ,
                    period : $("#editMonthPeriod").val() ,
                    content : $("#editMonthContent").val() ,
                    isdone : $("#editMonthIsDone").val(),
                    other : $("#editMonthOther").val()
                },
                success : function (resp) {
                    if(resp.status == "success"){
                        alert("Changed Successfully!") ;
                    }
                    editMonthScheduleTable.ajax.reload();
                }
            });
        }
        function onAddMonthSchedule() {

            $.ajax({
                method : "post" ,
                url : "{{route('myschedule.addOneMonth')}}" ,
                data : {
                    month : new Date( $("#editDate").val()).getFullMonth() ,
                    period : $("#addMonthPeriod").val() ,
                    content : editor.getData(),
                    isdone : $("#addMonthIsDone").val(),
                    other : $("#addMonthOther").val()
                },
                success : function (resp) {
                    if(resp.status == "success"){
                        alert("Add Successfully!") ;
                    }
                    editMonthScheduleTable.ajax.reload();
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
                    url: '/myschedule/getDataYear' ,
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
            editMonthScheduleTable =  $('#monthScheduleTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'retrieve': true,
                'ajax': {
                    url: '/myschedule/getDataMonth' ,
                    type: 'post' ,
                    data: function (d) {
                        d.editDate = $("#editDate").val(),
                        d.tabOption =  $("#tabOption").val()
                    },
                },
                'columns': [
                    { data: 'content', name: 'content', searchable: true },
                    { data: 'isdone', name: 'isdone', searchable: true },
                    { data: 'comment', name: 'comment', searchable: true },
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

