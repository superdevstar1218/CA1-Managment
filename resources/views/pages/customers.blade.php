
@extends('layouts.app', ['activePage' => 'customers', 'titlePage' => __('Customers')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Customer Logs</h4>
                        <p class="card-customer"> Here you can manage customers</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddCustomerModal">
                                    Add Customer
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
                                            First Name
                                        </th>
                                        <th>
                                            Last Name
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th class="text-right">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td>{{$customer->id}}</td>
                                            <td>{{$customer->firstname}}</td>
                                            <td>{{$customer->lastname}}</td>
                                            <td>{{$customer->email}}</td>
                                            <td class="td-actions text-right">
                                                <a onclick="setedit({{$customer}})" data-toggle="modal" data-target="#EditCustomerModal" class="btn btn-primary" href="#" data-original-title="" title="">
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
    <div class="modal fade" id="AddCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <div class="modal-body">
                    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
                    <span class="error" style="color:red; margin-top:10px; margin-bottom: 10px;"></span>

                    <form class="form" id="customerCreate">
                        @csrf

                        <div class=" card-login card-hidden mb-3">
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                First Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" name="firstname" class="form-control" placeholder="{{ __('First Name...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Last Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" name="lastname" class="form-control" placeholder="{{ __('Last Name...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                E-mail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" name="email" class="form-control" placeholder="{{ __('E-mail...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="justify-content-center">
                                <button type="button" class="btn btn-primary btn-link btn-lg save-data">{{ __('Create Customer') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Customer</h5>
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
                                                First Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" id="edit_firstname" class="form-control" placeholder="{{ __('First Name...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Last Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" id="edit_lastname" class="form-control" placeholder="{{ __('Last Name...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                E-mail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <input type="text" id="edit_email" class="form-control" placeholder="{{ __('E-mail...') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class=" justify-content-center">
                                <button type="button" class="btn btn-primary btn-link btn-lg edit-data">{{ __('Update Category') }}</button>
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
        function setedit(data) {
            $("#edit_id").val(data.id) ;
            $("#edit_firstname").val(data.firstname) ;
            $("#edit_lastname").val(data.lastname) ;
            $('#edit_email').val(data.email) ;
        }

        $(".edit-data").click(function(event){
            event.preventDefault();

            let id = $("input[name=edit_id]").val() ;
            let firstname = $("#edit_firstname").val() ;
            let lastname = $("#edit_lastname").val() ;
            let email = $("#edit_email").val() ;
            let _token = $('meta[name="csrf-token"]').attr('content') ;

            $.ajax({
                url: "/customer/" + id,
                type:"PUT",
                data: {
                    firstname: firstname,
                    lastname: lastname,
                    email : email ,
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

            let firstname = $("input[name=firstname]").val();
            let lastname = $("input[name=lastname]").val();
            let email = $("input[name=email]").val();
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "/customer",
                type:"POST",
                data:{
                    firstname: firstname,
                    lastname: lastname,
                    email : email ,
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

