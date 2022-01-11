@extends('layouts.app', ['activePage' => 'my-status', 'titlePage' => __('User Status')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title ">My Status</h4>
              <p class="card-category"> Here you can manage your status</p>
            </div>
            <div class="card-body">
              <form>
                @csrf
                <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
                <div class="col">
                  <div class="bmd-form-group pt-3 md-8">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <label for="selectUserStatus">My Current Status</label>
                        </span>
                      </div>
                      <select class="form-control" id="selectUserStatus" name="selectUserStatus">
                        @foreach ($categories as $category)
                        @if ($user->status == $category->id)
                        <option value="{{ $category->id}}" selected>{{ $category->name }}</option>
                        @else
                              <option value="{{  $category->id }}">{{  $category->name  }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="bmd-form-group pt-3 md-8">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <label for="selectProject">My Current Project</label>
                        </span>
                      </div>
                      <select class="form-control" id="selectProject" name="selectProject">
                        @foreach ($projects as $project)
                        @if ($user->project_id == $project->id)
                        <option value="{{ $project->id}}" selected>{{ $project->name }}</option>
                        @else
                              <option value="{{  $project->id }}">{{  $project->name  }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="pt-3 md-4">
                    <div class="form-group">
                      <label for="comment">Comment</label>
                      <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary save-data">
                      Submit
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>

@endsection
@push('js')
    <script>
        $(".save-data").click(function(event){
            event.preventDefault();
            {{--let before_status = {{$user->status}}--}}
            {{--let b_project = {{$user->project_id}}--}}
            {{--let status = $("#selectUserStatus").val();--}}
            {{--let project = $("#selectProject").val();--}}

            {{--// if(before_status == status && b_project == project) {--}}
            {{--if(before_status == status) {--}}
            {{--  $('.success').text('You already in this status');--}}
            {{--  return;--}}
            {{--}--}}
            {{--$('.success').hide();--}}

            let comment = $("#comment").val() ;
            let status = $("#selectUserStatus").val() ;
            let project = $("#selectProject").val() ;

            $.ajax({
                url: "{{url('save_status')}}",
                method:"post",
                headers : {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                } ,
                data:{
                    category_id: status,
                    user_id : "{{$user->id}}" ,
                    comment: comment,
                    project_id: project,
                },
                success: function(resp) {
                    console.log(resp);

                    if(resp.result){
                      $('.success').show();
                      $('.success').text(resp.success);
                      // $('.error').hide();
                    } else {
                        $('.success').text(resp.error);
                        $('.success').show();
                    }

                    setTimeout(function () {
                        $(".success").hide();
                    } , 5000);
                }
            })
        })
    </script>
@endpush

