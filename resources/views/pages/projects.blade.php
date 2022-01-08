
@extends('layouts.app', ['activePage' => 'projects', 'titlePage' => __('Projects')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title ">Projects</h4>
              <p class="card-category"> Here you can manage projects</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddProjectModal">
                    Add Projects
                  </button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <thead class=" text-primary">
                    <tr>
                      <th>
                        ID
                    </th>
                    <th>
                      Name
                    </th>
                    <th>
                      Status
                    </th>
                    <th>
                      Description
                    </th>
                    <th class="text-right">
                      Actions
                    </th>
                  </tr>
                </thead>
                  <tbody>
                    @foreach ($projects as $project)
                        <tr>
                          <td>{{$project->id}}</td>
                          <td>{{$project->name}}</td>
                          <td>{{$project->status}}</td>
                          <td>{{$project->description}}</td>
                          <td class="td-actions text-right">
                            <a onclick="setedit({{$project->id}}, '{{$project->name}}', '{{$project->status}}', '{{$project->description}}')" data-toggle="modal" data-target="#EditProjectModal" class="btn btn-primary" href="#" data-original-title="" title="">
                            Edit
                          </a>
                          </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="AddProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add New Project</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
          <span class="error" style="color:red; margin-top:10px; margin-bottom: 10px;"></span>
          <form class="form" id="userCreate">
            @csrf
    
            <div class=" card-login card-hidden mb-3">
              <div class="card-body ">
                <div class="bmd-form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </span>
                    </div>
                    <input type="text" name="name" class="form-control" placeholder="{{ __('Name...') }}" value="{{ old('name') }}" required>
                  </div>
                </div>
                <div class="bmd-form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          Description
                      </span>
                    </div>
                    <input type="text" name="description" class="form-control" placeholder="{{ __('Description...') }}" value="{{ old('description') }}" required>
                  </div>
                </div>
              </div>
              <div class=" justify-content-center">
                <button type="button" class="btn btn-primary btn-link btn-lg save-data">{{ __('Create Project') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="EditProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Project</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
          <span class="error" style="color:red; margin-top:10px; margin-bottom: 10px;"></span>
          <form class="form" id="userCreate">
            @csrf
            <input type="text" name="edit_id" id="edit_id" hidden>
            
            <div class=" card-login card-hidden mb-3">
              <div class="card-body ">
                <div class="bmd-form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </span>
                    </div>
                    <input id="edit_name" type="text" name="name" class="form-control" placeholder="{{ __('Name...') }}" value="{{ old('name') }}" required>
                  </div>
                </div>
                <div class="bmd-form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </span>
                    </div>
                    <input id="edit_status" type="text" name="status" class="form-control" placeholder="{{ __('Status...') }}" value="{{ old('status') }}">
                  </div>
                </div>
                <div class="bmd-form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          Description
                      </span>
                    </div>
                    <input id="edit_description" type="text" name="description" class="form-control" placeholder="{{ __('Description...') }}" value="{{ old('name') }}">
                  </div>
                </div>
              </div>
              <div class=" justify-content-center">
                <button type="button" class="btn btn-primary btn-link btn-lg edit-data">{{ __('Update Project') }}</button>
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
        function setedit(id, name, status, description) {
      $("#edit_id").val(id)
      $("#edit_name").val(name)
      $("#edit_status").val(status)
      $("#edit_description").val(description)
    }
    $(".edit-data").click(function(event){
      event.preventDefault();

      let id = $("input[name=edit_id]").val();
      let name = $("#edit_name").val();
      let status = $("#edit_status").val();
      let description = $("#edit_description").val();
      let _token   = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: "/projects/" + id,
        type:"PUT",
        data:{
          name: name,
          status: status,
          description: description,
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

      let name = $("input[name=name]").val();
      let description = $("input[name=description]").val();
      let _token   = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: "/projects",
        type:"POST",
        data:{
          name: name,
          description: description,
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
  </script>
@endpush

