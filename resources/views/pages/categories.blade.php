
@extends('layouts.app', ['activePage' => 'categories', 'titlePage' => __('Categories')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Users</h4>
                        <p class="card-category"> Here you can manage categories</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#AddCategoryModal">
                                    Add Category
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
                                            Comment
                                        </th>
                                        <th>
                                            Color
                                        </th>
                                        <th class="text-right">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{$category->id}}</td>
                                            <td>{{$category->name}}</td>
                                            <td>{{$category->comment}}</td>
                                            <td style="background-color:{{$category->bgcolor}}">
                                                <span style="font-weight: bold; color:white">{{$category->bgcolor}}</span>
                                            </td>
                                            <td class="td-actions text-right">
                                                <a onclick="setedit({{$category->id}}, '{{$category->name}}', '{{$category->comment}}' , '{{$category->bgcolor}}')" data-toggle="modal" data-target="#EditCategoryModal" class="btn btn-primary" href="#" data-original-title="" title="">
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
    <div class="modal fade" id="AddCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Category</h5>
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
                                                Comment
                                            </span>
                                        </div>
                                        <input type="text" name="comment" class="form-control" placeholder="{{ __('Comment...') }}" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Color
                                            </span>
                                        </div>
                                        <input type="color" name="color" class="form-control" placeholder="{{__('Color...')}}" value="#ba35d4" />
                                    </div>
                                </div>
                            </div>
                            <div class="justify-content-center">
                                <button type="button" class="btn btn-primary btn-link btn-lg save-data">{{ __('Create Category') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Category</h5>
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
                                                Comment
                                            </span>
                                        </div>
                                        <input id="edit_comment" type="text" name="comment" class="form-control" placeholder="{{ __('Comment...') }}" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Color
                                            </span>
                                        </div>
                                        <input id="edit_color" type="color" name="color" class="form-control" placeholder="{{ __('Color...') }}" value="{{ old('name') }}" required>
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
            function setedit(id, name, comment , color) {
                $("#edit_id").val(id) ;
                $("#edit_name").val(name) ;
                $("#edit_comment").val(comment) ;
                $('#edit_color').val(color) ;
            }

            $(".edit-data").click(function(event){
                event.preventDefault();

                let id = $("input[name=edit_id]").val() ;
                let name = $("#edit_name").val() ;
                let comment = $("#edit_comment").val() ;
                let color = $("#edit_color").val() ;
                let _token = $('meta[name="csrf-token"]').attr('content') ;

                $.ajax({
                    url: "/category/" + id,
                    type:"PUT",
                    data: {
                        name: name,
                        comment: comment,
                        color : color ,
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

                console.log($("input[name=color]").val().toString());
                let name = $("input[name=name]").val();
                let comment = $("input[name=comment]").val();
                let color = $("input[name=color]").val().toString();
                let _token   = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "/category",
                    type:"POST",
                    data:{
                        name: name,
                        comment: comment,
                        color : color ,
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

