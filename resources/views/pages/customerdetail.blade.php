
@extends('layouts.app', ['activePage' => 'customers', 'titlePage' => __('Customers')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{$customer->firstname}} {{$customer->lastname}}</h4>
                        <p class="card-customer"> Here you can see {{$customer->firstname}} {{$customer->lastname}}'s detail</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>First Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->firstname}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Last Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->lastname}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>E-mail:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->email}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Skype address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->skype_address}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Birthday:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->birthday}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Country:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->country}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>City:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->city}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Street:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->street}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Postal Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->postal_code}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Phone Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->phone_number}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>IP address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->ip_address}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Hobby:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->hobby}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Language:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->language}} </h3>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <h3>Other:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->other}} </h3>
                            </div>
                            </div>
                            <div class="col-12 text-center">
                                <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary" >
                                    Return
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    
@endpush

