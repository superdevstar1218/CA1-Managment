
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

                        @foreach($categories as $category)
                            <input type="hidden" id="{{$category->name}}" value="{{$category->bgcolor}}" />
                        @endforeach

                    <div class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Category</span>
                            </div>
                            <select name="category_id" class="form-control" >
                                @foreach($categories as $category)
                                    <option id="{{$category->id}}" value="{{$category->name}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
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
                dayCount : 4,
                selectable: true,
                select: function(arg) {
                    var today = new Date().toLocaleString("en-US", {timeZone: "Asia/Tokyo"}) ;

                    const d = new Date(today) ;
                    const e = new Date(arg.end.toLocaleString()) ;

                    const diff = d.getTime() - e.getTime() ;

                    console.log(diff) ;

                    if(diff < 0 || arg.jsEvent.toElement.className != "fc-highlight"){
                        $("#canInsert").val('0') ;
                    } else {
                        $("#canInsert").val('1') ;
                    }

                    // if(){
                    //     $("#canInsert").val('0') ;
                    // } else {
                    //     $("#canInsert").val('1') ;
                    // }

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
                                title : '{{$registry->category->name}}' ,
                                {{--url : '{{$registry->category_id}}' ,--}}
                                start : '{{str_replace(" " , "T" , $registry->start)."-08:00"}}' ,
                                end : '{{str_replace(" " , "T" , $registry->end)."-08:00"}}' ,
                                color : '{{$registry->category->bgcolor}}' ,
                                overlap : false
                              },
                          @endforeach
                ]
            });

            calendar.render();

            console.log(calendar.getEvents());

            function trimEventAPI(id , canFinished) {
                currentEventAPI = calendar.getEventById(id) ;

                for(let i = 0 ; i < calendar.getEvents().length ; i++){
                    eventAPI = calendar.getEvents()[i] ;

                    if(currentEventAPI.endStr === eventAPI.startStr){
                        if(currentEventAPI.title === eventAPI.title){
                            let tmpEventAPI = {
                                id  : currentEventAPI.id ,
                                title : currentEventAPI.title ,
                                start : currentEventAPI.start ,
                                startStr : currentEventAPI.startStr ,
                                endStr : eventAPI.endStr ,
                                end : eventAPI.end ,
                                color : eventAPI.backgroundColor ,
                                overlap : false
                            }

                            eventAPI.remove() ;
                            currentEventAPI.remove() ;

                            calendar.addEvent(tmpEventAPI) ;

                            if(!canFinished) trimEventAPI(tmpEventAPI.id , true) ;

                            break ;
                        }
                    }
                    if(currentEventAPI.startStr === eventAPI.endStr){

                        if(currentEventAPI.title === eventAPI.title) {
                            let tmpEventAPI = {
                                id  : currentEventAPI.id ,
                                title : currentEventAPI.title ,
                                start : eventAPI.start ,
                                startStr : eventAPI.startStr,
                                end : currentEventAPI.end ,
                                endStr : currentEventAPI.endStr ,
                                color : eventAPI.backgroundColor ,
                                overlap : false
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

            $('.fc-scroller-liquid-absolute').on( "mousewheel" , function(event){
                if (event.originalEvent.wheelDelta >= 0)
                    this.scrollTop = this.scrollTop - event.originalEvent.wheelDelta ;
                else
                    this.scrollTop = this.scrollTop - event.originalEvent.wheelDelta ;
            });

            $("#btn_save").on('click' , function(){

                var sum_time = 0 ;
                var diff_time = 0 ;

                for( i = 0 ; i < calendar.getEvents().length ; i++){
                    var startDate = calendar.getEvents()[i]['start'] ;
                    var endDate = calendar.getEvents()[i]['end'] ;

                    var d = endDate.getTime() - startDate.getTime() ;

                    sum_time += d;
                }

                var today = new Date().toLocaleString("en-US", {timeZone: "Asia/Tokyo"}) ;
                const todayTime = new Date(today).getTime() ;

                today = today.split("/") ;
                today = today[2].split(",")[0] + "-" + ( Number(today[0]) < 10 ? "0" + today[0] : today[0]) + "-" + ( Number(today[1]) < 10 ? "0" + today[1] : today[1]) ;

                const e = new Date(today + " 00:00:00").getTime() ;

                const diff = todayTime - e ;

                if( $("#date_start").val() == $("#date_end").val() ) {
                    if( today == $("#date_end").val()) {
                        diff_time = diff ;
                    } else {
                        diff_time = 24*60*60*1000 ;
                        // const f = new Date($("#date_start").val() + " 00:00:00").getTime() ;
                        //
                        // diff_time = diff_time + diff + ( e - f );
                    }
                } else {
                    if( today == $("#date_end").val()) {
                        const f = new Date($("#date_end").val() + " 00:00:00").getTime() ;
                        const g = new Date($("#date_start").val() + " 00:00:00").getTime() ;

                        diff_time = diff_time + diff + ( f - g );
                    } else {
                        diff_time = 24*60*60*1000 ;

                        const f = new Date($("#date_end").val() + " 00:00:00").getTime() ;
                        const g = new Date($("#date_start").val() + " 00:00:00").getTime() ;

                        diff_time = diff_time + ( f - g );
                        // const f = new Date($("#date_start").val() + " 00:00:00").getTime() ;
                        //
                        // diff_time = diff_time + diff + ( e - f );
                    }
                }

                console.log(diff_time) ;

                if(diff_time != sum_time){
                    return alert("You can't save data.") ;
                }

                var eventAPI = [] ;
                var i = 0 ;

                for( i = 0 ; i < calendar.getEvents().length ; i++){
                    var startDate = calendar.getEvents()[i]['startStr'] ;
                    var endDate = calendar.getEvents()[i]['endStr'] ;
                    eventAPI.push({
                      title : calendar.getEvents()[i]['title'] ,
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
                        title : $('select[name=category_id]').val() ,
                        color :  $( "#" + $("select[name=category_id]").val() ).val(),
                        start :  new Date( $("#startStr").val() ),
                        end :  new Date( $("#endStr").val() ),
                        overlap : false
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
