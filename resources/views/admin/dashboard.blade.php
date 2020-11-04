@extends('layouts.default')

@section('content')
<style type="text/css">
    .dasboard_div{
        cursor: pointer !important;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>DASHBOARD</h2>
        </div>
        <!-- Widgets -->
        <div class="row clearfix">
            <a href="{{url('/admin')}}">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect dasboard_div">
                        <div class="icon">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="content">
                            <div class="text">Users</div>
                            <div class="number count-to" data-from="0" data-to="{{$user_count}}" data-speed="15" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{url('admin/stores')}}">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-teal dasboard_div">
                        <div class="icon">
                            <i class="material-icons">format_color_fill</i>
                        </div>
                        <div class="content">
                            <div class="text">Color</div>
                            <div class="number count-to" data-from="0" data-to="256" data-speed="1000" data-fresh-interval="20">125</div>
                        </div>
                    </div>
                </div>
            </a>
           
        </div> 
    </div>
</section>
@endsection