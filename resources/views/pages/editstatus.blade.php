
@extends('layouts.app', ['activePage' => 'user-status', 'titlePage' => __('User Detail')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row pb-3 d-flex align-items-center">
                <div class="col-md-12">
                    {{$user_name}} &nbsp;
                    <input type="date"  value="{{$date_start}}" id="date_start" disabled/>
                    <input type="date"  value="{{$date_end}}" id="date_end"  disabled/>
                    <button type="submit" class="btn btn-sm btn-primary" id="btn_save">
                        Save
                    </button>
                    &nbsp;
                    <a type="button" class="btn btn-sm btn-primary" href="{{route('status.detail' , $user_id)}}">Go Back</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal " id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Insert Log</h5>
                    <button type="button" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="canInsert" name="canInsert" />
                    <input type="hidden" id="start" name="start" />
                    <input type="hidden" id="end" name="end" />
                    <input type="hidden" id="startStr" name="startStr" />
                    <input type="hidden" id="endStr" name="endStr" />
                    <input type="hidden" id="allStrDay" name="allDay" />
                    <input type="hidden" id="user_id" name="user_id" value="{{$user_id}}" />
                    <input type="hidden" id="cur_date" name="cur_date" />

{{--                        @foreach($categories as $category)--}}
{{--                            <input type="hidden" id="{{$category->name}}" value="{{$category->bgcolor}}" />--}}
{{--                        @endforeach--}}


                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Category
                                </span>
                            </div>
                            <select name="category_id" class="form-control" id="category_id">
                                @foreach($categories as $category)
                                    <option id="{{$category->id}}" value="{{$category->bgcolor}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Project
                                </span>
                            </div>
                            <select name="project_id" class="form-control" id="project_id">
                                @foreach($projects as $project)
                                    <option id="{{$project->id}}" value="{{$project->name}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Comment
                                </span>
                            </div>
                            <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" id="btn_insert">Insert</button>
                    <button type="button" class="btn btn-sm btn-info" id="btn_close">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{asset('material')}}/js/lib/main.js"></script>

    <script>
        var maxID = "<?php if(isset($registries)) echo count($registries) ?>" ;

        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                // headerToolbar: {
                //     center: 'title',
                //     right: 'timeGridDay'
                // },
                // timeZone: 'Asia/Tokyo' ,
                weekends :true,
                initialView: 'timeGrid',
                validRange : {
                    start : $("#date_start").val() ,
                    end : "{{$limited_date}}" ,
                },
                selectOverlap : function(event) {

                },
                eventStartEditable : true ,
                dayCount : 4,
                selectable: true,
                select: function(arg) {

                    if( arg.jsEvent.toElement.className != "fc-highlight"){
                        $("#canInsert").val('0') ;
                    } else {
                        $("#canInsert").val('1') ;
                    }

                    $("#start").val(arg.start) ;
                    $("#end").val(arg.end) ;
                    $("#startStr").val(arg.startStr) ;
                    $("#endStr").val(arg.endStr) ;
                    $("#allDay").val(arg.allDay) ;
                    $("#cur_date").val($("#date_start").val());

                    $("#exampleModal").show() ;

                    calendar.unselect() ;
                },
                eventDrop : function(arg){
                    trimEventAPI(arg.event.id , false) ;
                },
                eventClick: function(arg) {
                    console.log('arg.event.id : ', arg.event.id) ;
                    if (confirm('Are you sure you want to delete this event?')) {
                        arg.event.remove() ;
                    }
                },
                editable: true,
                dayMaxEvents: true,
                events: [
                    @foreach($registries as $registry)
                    {
                        id  : "{{$loop->index}}" ,
                        title : '{{$registry->category->name}}' + " : " +  "{{$registry->project->name}}" + " - " + "{{$registry->comment}}",
                        {{--url : '{{$registry->category_id}}' ,--}}
                        start : '{{str_replace(" " , "T" , $registry->start)."-08:00"}}' ,
                        end : '{{str_replace(" " , "T" , $registry->end)."-08:00"}}' ,
                        color : '{{$registry->category->bgcolor}}' ,
                        overlap : false ,
                        category_id : "{{$registry->category_id}}" ,
                        project_id : "{{$registry->project_id}}" ,
                        comment : "{{$registry->comment}}"
                    },
                    @endforeach
                ]
            });

            calendar.render();

            console.log(calendar.getEvents());

            function trimEventAPI(id , canFinished) {
                let currentEventAPI = calendar.getEventById(id) ;

                for(let i = 0 ; i < calendar.getEvents().length ; i++){
                    let eventAPI = calendar.getEvents()[i] ;

                    if(currentEventAPI.endStr === eventAPI.startStr){
                        if(currentEventAPI.title === eventAPI.title ){

                            let tmpEventAPI = {
                                id  : currentEventAPI.id ,
                                title : currentEventAPI.title ,
                                start : currentEventAPI.start ,
                                startStr : currentEventAPI.startStr ,
                                endStr : eventAPI.endStr ,
                                end : eventAPI.end ,
                                color : eventAPI.backgroundColor ,
                                overlap : false ,
                                category_id : currentEventAPI.extendedProps.category_id ,
                                project_id : currentEventAPI.extendedProps.project_id ,
                                comment : currentEventAPI.extendedProps.comment
                            }

                            eventAPI.remove() ;
                            currentEventAPI.remove() ;

                            calendar.addEvent(tmpEventAPI) ;

                            if(!canFinished) trimEventAPI(tmpEventAPI.id , true) ;

                            break ;
                        }
                    }
                    if(currentEventAPI.startStr === eventAPI.endStr){

                        if(currentEventAPI.title === eventAPI.title ) {
                            let tmpEventAPI = {
                                id  : currentEventAPI.id ,
                                title : currentEventAPI.title ,
                                start : eventAPI.start ,
                                startStr : eventAPI.startStr,
                                end : currentEventAPI.end ,
                                endStr : currentEventAPI.endStr ,
                                color : eventAPI.backgroundColor ,
                                overlap : false ,
                                category_id : currentEventAPI.extendedProps.category_id ,
                                project_id : currentEventAPI.extendedProps.project_id ,
                                comment : currentEventAPI.extendedProps.comment
                            }

                            eventAPI.remove() ;
                            currentEventAPI.remove() ;

                            calendar.addEvent(tmpEventAPI) ;

                            if(!canFinished) trimEventAPI(tmpEventAPI.id , true) ;

                            break ;
                        }
                    }
                }
            }


            function isEmptyStatus() {
                let datetime_start = new Date( $("#date_start").val() + " 00:00:00" ) ;
                let datetime_end = new Date(new Date( $("#date_end").val() + " 00:00:00" ).getTime() + 24*60*60*1000 ) ;

                // if current start date is equal with user 's created date
                if( datetime_start.getTime() == new Date( new String("{{$user_created_at}}").substring(0 , 10) + " 00:00:00" ).getTime() ) {
                    datetime_start = new Date("{{$user_created_at}}")  ;
                    // console.log("1");
                }

                // if current end date is equal with current date
                // last datetime is user 's last registry datetime.

                let today = new Date().toLocaleDateString("en-US", {timeZone: "Asia/Tokyo"}) ;

                if( new Date( $("#date_end").val() + " 12:00:00" ).getTime() == new Date( today + ' 12:00:00').getTime()){
                    datetime_end = new Date( "{{$last_dateTime}}" ) ;
                    // console.log("2");
                }

                let periodTime = datetime_end.getTime() - datetime_start.getTime() ;

                let totalTime = 0 ;

                for(  let i = 0 ; i < calendar.getEvents().length ; i++){
                    let startDate = calendar.getEvents()[i]['start'] ;
                    let endDate = calendar.getEvents()[i]['end'] ;

                    if(endDate.getTime() <= new Date("{{$last_dateTime}}").getTime()){
                        totalTime += endDate.getTime() - startDate.getTime() ;
                    }
                }

                // console.log(periodTime) ;
                // console.log(totalTime) ;

                if(periodTime != totalTime) {
                    return true ;
                }
                return false ;
            }
            function isValidStatus(){
                let previousIDArray = [] ;
                for(  let i = 0 ; i < calendar.getEvents().length ; i++){
                    var startDate = calendar.getEvents()[i]['start'] ;

                    if(startDate.getTime() < new Date("{{$user_created_at}}").getTime()){
                        previousIDArray.push(calendar.getEvents()[i]['id']) ;
                    }
                }

                if(previousIDArray.length != 0) {
                    previousIDArray.map(function(element){
                        let event = calendar.getEventById(element) ;

                        //if end of event is inside of valid range
                        if(event.end.getTime() > new Date("{{$user_created_at}}").getTime()){
                            let tmpEventAPI = {
                                id  : event.id ,
                                title : event.title ,
                                start : event.start ,
                                end : event.end ,
                                color : event.backgroundColor ,
                                overlap : false,
                                category_id : event.extendedProps.category_id ,
                                project_id : event.extendedProps.project_id ,
                                comment : event.extendedProps.comment
                            }

                            tmpEventAPI.start = new Date("{{$user_created_at}}");

                            calendar.getEventById(element).remove() ;
                            calendar.addEvent(tmpEventAPI) ;

                        } else calendar.getEventById(element).remove() ;
                    })
                    return  false ;
                }
                return true ;
            }

            function isOverflowStatus(){

                let overIDArray = [] ;

                for(  let i = 0 ; i < calendar.getEvents().length ; i++){
                    var endDate = calendar.getEvents()[i]['end'] ;

                    if( endDate.getTime() >  new Date("{{$last_dateTime}}").getTime() ){
                        overIDArray.push(calendar.getEvents()[i].id) ;
                    }
                }

                if(overIDArray.length != 0) {

                    overIDArray.map( function(element){
                        let event = calendar.getEventById(element) ;

                        // if start of event is inside valid range.
                        if(event.start.getTime() < new Date("{{$last_dateTime}}").getTime()){

                            let tmpEventAPI = {
                                id  : event.id ,
                                title : event.title ,
                                start : event.start ,
                                end : event.end ,
                                color : event.backgroundColor ,
                                overlap : false ,
                                category_id : event.extendedProps.category_id ,
                                project_id : event.extendedProps.project_id ,
                                comment : event.extendedProps.comment
                            }

                            tmpEventAPI.end = new Date("{{$last_dateTime}}");

                            calendar.getEventById(element).remove() ;
                            calendar.addEvent(tmpEventAPI) ;
                        } else calendar.getEventById(element).remove() ;
                    })
                    return true ;
                }
                return false ;
            }

            $('.fc-scroller-liquid-absolute').on( "mousewheel" , function(event){
                if (event.originalEvent.wheelDelta >= 0)
                    this.scrollTop = this.scrollTop - event.originalEvent.wheelDelta ;
                else
                    this.scrollTop = this.scrollTop - event.originalEvent.wheelDelta ;
            });


            $("#category_id").on('change' , function (e) {
                $('input[name=categoryId]').val(e.target.id) ;
            });

            $("#project_id").on('change' , function (e) {
                $('input[name=projectId]').val(e.target.id) ;
            });

            $("#btn_save").on('click' , function(){

                if(!isValidStatus()){
                    return alert("{{$user_name}}" + " registered at " + "{{$user_created_at}}" + ". so you can't edit status before " + "{{$user_created_at}}") ;
                }

                if(isOverflowStatus()){
                    return alert("You can't edit after " + "{{$last_dateTime}}") ;
                }

                if(isEmptyStatus()){
                    return alert("exist empty status") ;
                }

                let eventAPI = [] ;

                for( let i = 0 ; i < calendar.getEvents().length ; i++){
                    let startDate = calendar.getEvents()[i]['startStr'] ;
                    let endDate = calendar.getEvents()[i]['endStr'] ;
                    eventAPI.push({
                        title : calendar.getEvents()[i]['title'] ,
                        project_id : calendar.getEvents()[i].extendedProps.project_id ,
                        category_id: calendar.getEvents()[i].extendedProps.category_id ,
                        comment: calendar.getEvents()[i].extendedProps.comment ,
                        startStr : startDate ,
                        endStr : endDate,
                    })
                }

                console.log(eventAPI) ;

                if(eventAPI.length == 0) {
                    return alert("No Datas!!!") ;
                }

                $.ajax({
                    url : '{{route('status.save_registries')}}' ,
                    method : 'post' ,
                    headers : {
                        'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content')
                    },
                    data : {
                        eventApi: eventAPI ,
                        first_date : $("#date_start").val(),
                        last_date : $("#date_end").val() ,
                        user_id : "{{$user_id}}"
                    },
                    success : function(resp){
                        alert(resp.status) ;
                    },
                    complete : {

                    },
                    error : function (e) {
                        console.log(e);
                    }
                });
            });

            $("#btn_insert").on('click' , async function () {
                $("#exampleModal").hide() ;
                if($("#canInsert").val() == "0" ){
                    alert("You can't insert because selected layer is overlaped ! OR Overflow time !") ;
                } else {
                    await calendar.addEvent({
                        id : maxID++ ,
                        title : $('select[name=category_id] option:selected').text() + " : " + $('select[name=project_id]').val() + " - " + $("#comment").val(),
                        color : $("select[name=category_id]").val(),
                        start :  new Date( $("#startStr").val() ),
                        end :  new Date( $("#endStr").val() ),
                        overlap : false ,
                        category_id : Number ($('select[name=category_id]').prop('selectedIndex')) + 1 ,
                        project_id : Number( $('select[name=project_id]').prop('selectedIndex') ) + 1 ,
                        comment : $("#comment").val()
                    });
                    await trimEventAPI( maxID-1 , false) ;

                    console.log(calendar.getEvents());
                }
            });
        });

        $("#btn_close , .close").on('click' , function () {
            $("#exampleModal").hide();
        });
    </script>
@endpush
