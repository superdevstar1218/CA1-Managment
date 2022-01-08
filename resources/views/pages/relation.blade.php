
@extends('layouts.app', ['activePage' => 'user-relation', 'titlePage' => __('User Management')])

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
              <div class="table-responsive">
                <table class="table">
                  <thead class=" text-primary">
                    <tr><th>
                        Sub Admins
                    </th>
                    <th>
                      Users
                    </th>
                    <th class="text-right">
                      Actions
                    </th>
                  </tr></thead>
                  <tbody>
                   @foreach ($subadmins as $subadmin)
                       <tr>
                         <td>{{$subadmin->name}}</td>
                         <td>
                           @foreach ($subadmin->users as $user)
                               {{$user->name}}
                               <br/>
                           @endforeach
                         </td>
                         <td class="text-right">
                          <button type="button" onclick="preaddUser({{$subadmin->id}})" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddUserModal">
                            Add user
                          </button>
                          <button type="button" onclick="predeleteUser({{$subadmin->id}})" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#DeleteUserModal">
                            Delete user
                          </button>
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
          <h5 class="modal-title" id="exampleModalLongTitle">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" name="id" id="id" hidden>
          <form class="useradd"></form>
          </div>
            <div class="card-login card-hidden mb-3">
              <div class=" justify-content-center">
                <button type="button" onclick="addUsers()" data-dismiss="modal" class="btn btn-primary btn-link btn-lg save-data">{{ __('Add Users') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="DeleteUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Delete User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" name="id" id="id" hidden>
          <form class="userdelete">
            
          </form>
            <div class=" card-login card-hidden mb-3">
              <div class=" justify-content-center">
                <button type="button" onclick="deleteUsers()" class="btn btn-primary btn-link btn-lg save-data">{{ __('Delete Users') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          
        </div>
      </div>
    </div>
  </div>

@endsection
@push('js')
  <script>
    function preaddUser(id) {
      $("#id").val(id)
      $.ajax({
        url: "/relation/get",
        type:"get",
        success:function(response){
          $('.useradd').empty();
          console.log( response.users.length)
          for(let i = 0; i < response.users.length; i++){
            $('.useradd').append('<div class="form-check">' +
              '<label class="form-check-label">' +
                '<input class="form-check-input add-user" type="checkbox" name="addusers[]" value="'+ response.users[i].id +'">' + response.users[i].name +
                '<span class="form-check-sign">' +
                  '<span class="check"></span>' +
               ' </span>' +
             ' </label>' +
           '</div>')
          }
        },
       });
    }

    function predeleteUser(id) {
      $("#id").val(id)
      $.ajax({
        url: "/relation/cusers/" + id,
        type:"get",
        success:function(response){
          $('.userdelete').empty();
          console.log( response.users.length)
          for(let i = 0; i < response.users.length; i++){
            $('.userdelete').append('<div class="form-check">' +
              '<label class="form-check-label">' +
                '<input class="form-check-input del-user" type="checkbox" name="delusers[]" value="'+ response.users[i].id +'">' + response.users[i].name +
                '<span class="form-check-sign">' +
                  '<span class="check"></span>' +
               ' </span>' +
             ' </label>' +
           '</div>')
          }
        },
       });
    }

    function addUsers() {
      let _token   = $('meta[name="csrf-token"]').attr('content');
      let myCheckboxes = new Array();
        $(".add-user").each(function() {
          if($(this).is(':checked')) {
            myCheckboxes.push($(this).val());
          }
        });
      $.ajax({
        url: "/relation/addUsers/" + $('#id').val(),
        type:"POST",
        data:{
          addusers:  myCheckboxes,
          _token: _token
        },
        success:function(response){
          window.location.reload();
        },
       });
    }

    function deleteUsers() {
      let _token   = $('meta[name="csrf-token"]').attr('content');
      let myCheckboxes = new Array();
        $(".del-user").each(function() {
          if($(this).is(':checked')) {
            myCheckboxes.push($(this).val());
          }
        });
      $.ajax({
        url: "/relation/delUsers/" + $('#id').val(),
        type:"POST",
        data:{
          delusers:  myCheckboxes,
          _token: _token
        },
        success:function(response){
          window.location.reload();
        },
       });
    }
  </script>
@endpush

