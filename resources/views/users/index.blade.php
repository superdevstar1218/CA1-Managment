
@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])

@section('content')
  
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title ">Users</h4>
              <p class="card-category"> Here you can manage users</p>
            </div>
            <div class="card-body">
                              <div class="row">
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddUserModal">
                    Add user
                  </button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <thead class=" text-primary">
                    <tr><th>
                        Name
                    </th>
                    <th>
                      Email
                    </th>
                    <th>
                      User Role
                    </th>
                    <th>
                      Creation date
                    </th>
                    <th class="text-right">
                      Actions
                    </th>
                  </tr></thead>
                  <tbody>
                    @foreach ($users as $user)
                    <tr>
                      <td>
                        {{$user->name}}
                      </td>
                      <td>
                        {{$user->email}}
                      </td>
                      <td>
                        {{$user->role == 1? "Sub Admin": "Normal User" }}
                      </td>
                      <td>
                        {{$user->created_at}}
                      </td>
                      <td class="td-actions text-right">
                          <a rel="tooltip" onclick="setedit({{$user->id}}, '{{$user->name}}', '{{$user->email}}', {{$user->role}})" data-toggle="modal" data-target="#EditUserModal" class="btn btn-success btn-link" href="#" data-original-title="" title="">
                            <i class="material-icons">edit</i>
                            <div class="ripple-container"></div>
                          </a>
                          <a rel="tooltip" onclick="setedit({{$user->id}}, '{{$user->name}}', '{{$user->email}}', {{$user->role}})" data-toggle="modal" data-target="#EditUserModal" class="btn btn-success btn-link" href="#" data-original-title="" title="">
                            <i class="material-icons">lock</i>
                            <div class="ripple-container"></div>
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


  <div class="modal fade" id="AddUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add New User</h5>
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
                <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          <i class="material-icons">face</i>
                      </span>
                    </div>
                    <input type="text" name="name" class="form-control" placeholder="{{ __('Name...') }}" value="{{ old('name') }}" required>
                  </div>
                </div>
                <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }} mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">email</i>
                      </span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="{{ __('Email...') }}" value="{{ old('email') }}" required>
                  </div>
                </div>
                <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">lock_outline</i>
                      </span>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password...') }}" required>
                  </div>
                </div>
                <div class="bmd-form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }} mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">lock_outline</i>
                      </span>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password...') }}" required>
                  </div>
                </div>
                <div class="bmd-form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }} mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <label for="selectUserType">Select User Type</label>
                      </span>
                    </div>
                    <select class="form-control" id="selectUserType" name="userType">
                      <option>User</option>
                      <option>Sub Admin</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class=" justify-content-center">
                <button type="button" class="btn btn-primary btn-link btn-lg save-data">{{ __('Create account') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  
  <div class="modal fade" id="EditUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
          <span class="error" style="color:red; margin-top:10px; margin-bottom: 10px;"></span>
          <form class="form" id="userEdit">
            <input type="text" name="edit_id" id="edit_id" hidden>
            @csrf
    
            <div class=" card-login card-hidden mb-3">
              <div class="card-body ">
                <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          <i class="material-icons">face</i>
                      </span>
                    </div>
                    <input type="text" name="edit_name" id="edit_name" class="form-control" placeholder="{{ __('Name...') }}" value="{{ old('name') }}" required>
                  </div>
                </div>
                <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }} mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="material-icons">email</i>
                      </span>
                    </div>
                    <input type="edit_email" id="edit_email" name="edit_email" class="form-control" placeholder="{{ __('Email...') }}" value="{{ old('email') }}" required>
                  </div>
                </div>

                <div class="bmd-form-group mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <label for="edit_selectUserType">Select User Type</label>
                      </span>
                    </div>
                    <select class="form-control" id="edit_selectUserType" name="edit_userType">
                      <option>User</option>
                      <option>Sub Admin</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class=" justify-content-center">
                <button type="button" class="btn btn-primary btn-link btn-lg edit-data">{{ __('Edit account') }}</button>
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
    function setedit(id, name, email, role) {
      $("#edit_id").val(id)
      $("#edit_name").val(name)
      $("#edit_email").val(email)
     
      if(role == 1) {
        $("#edit_selectUserType").val('Sub Admin')
      } else {
        $("#edit_selectUserType").val('User')
      }
    }
    $(".save-data").click(function(event){
      event.preventDefault();

      let name = $("input[name=name]").val();
      let email = $("input[name=email]").val();
      let password = $("input[name=password]").val();
      let password_confirmation = $("input[name=password_confirmation]").val();
      let userType = $("#selectUserType").val();
      let _token   = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: "/user",
        type:"POST",
        data:{
          name: name,
          email: email,
          password: password,
          password_confirmation: password_confirmation,
          userType: userType,
          _token: _token
        },
        success:function(response){
          console.log(response);
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
  $(".edit-data").click(function(event){
      event.preventDefault();

      let id = $("input[name=edit_id]").val();
      let name = $("input[name=edit_name]").val();
      let email = $("input[name=edit_email]").val();
      let userType = $("#edit_selectUserType").val();
      console.log('userType', userType)
      let _token   = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: "/user/" + id,
        type:"PUT",
        data:{
          name: name,
          email: email,
          userType: userType,
          _token: _token
        },
        success:function(response){
          console.log(response);
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

