@extends('layouts.default')
@section('content')

<section class="content">
	<div class="container-fluid">
		<div class="block-header">
            
            <ol class="breadcrumb breadcrumb-col-red">
                <li><a href="{{url('/admin')}}"><i class="material-icons">home</i> Home</a></li>
                <li><a href="{{url('/admin/colors')}}"><i class="material-icons">format_color_fill</i> Colors</a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">format_color_fill</i> Update</a></li>
            </ol>      
        </div>
		<div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>UPDATE COLOR</h2>
                        <ul class="header-dropdown m-r--5"></ul>
                    </div>
                    <div class="body">
                        <form action="{{url('/admin/colors/update')}}" id="form_validation" method="POST" class="dropzone" enctype="multipart/form-data">
                        	 @csrf
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="color_code" required value="{{$color->color_code}}">
                                    <label class="form-label">Color Code</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="rgb" required value="{{$color->rgb}}">
                                    <label class="form-label">RGB</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="hsb" required value="{{$color->hsb}}">
                                    <label class="form-label">HSB</label>
                                </div>
                            </div>
                            

                            
                            
                            
                            <input type="hidden" name="color_id" value="{{$color->id}}">
                            <button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>       
	</div>
</section>
@endsection