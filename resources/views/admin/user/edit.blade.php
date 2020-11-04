@extends('layouts.default')
@section('content')

<section class="content">
	<div class="container-fluid">
		<div class="block-header">
            <ol class="breadcrumb breadcrumb-col-red">
                <li><a href="javascript:void(0);"><i class="material-icons">home</i> Home</a></li>
                <li><a href="{{url('/admin/users')}}"><i class="material-icons">person</i> User</a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">person</i> Update</a></li>
            </ol>      
        </div>
		<div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>UPDATE USER PROFILE</h2>
                        <ul class="header-dropdown m-r--5"></ul>
                    </div>
                    <div class="body">
                        <form action="{{url('/admin/users/update')}}" id="form_validation" method="POST" class="dropzone" enctype="multipart/form-data">
                        	 @csrf
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="name" required value="{{$user->name}}">
                                    <label class="form-label">Name</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line" id="bs_datepicker_container">
                                    <input type="date" class="form-control" value="{{$user->dob}}" name="dob" required>
                                    <label class="form-label">Date of Birth</label>
                                </div>
                            </div>

                            <div class="form-group form-float ">
                                <div class="form-line ">
                                    <select class="form-control show-tick" name="gender">
                                        <option>Female</option>
                                        <option>Male</option>
                                    </select>
                                    <label class="form-label">Gender</label>
                                </div>
                            </div>
                            
                            
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>       
	</div>
</section>
@endsection