@extends('admin.layouts.master')
@section('title', " ".config('app.name'). " :: Admin Dashboard")
@section('content')
    <section class="content-header">
      <h1>
        Dashboard
        <small>Dashboard panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <!-- Main content -->
      <section class="content">
          @include('admin.dashboard.includes.count-block')
          <div class="row">
            <div class="col-md-4">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Users</h3>
                  <div class="box-tools pull-right">
                    <span class="label label-danger">{{count($latesUser)}} New Users</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="users-list ulist clearfix">
                  @if(count($latesUser) > 0)
                    @foreach($latesUser as $list)
                    <li>
                      <img src="{{$list->PicturePath}}" alt="User Image" title="{{ucfirst($list->name)}}">
                      <a class="users-list-name" href="{{route('users.edit',$list->username)}}">{{ucfirst($list->name)}}</a>
                      <span class="users-list-date">{{ucfirst($list->created_at->diffForHumans())}}</span>
                    </li>
                    @endforeach
                  @endif
                  </ul>
                </div>
                <div class="box-footer text-center">
                  <a href="{{route('users.index')}}" class="uppercase">View All Users</a>
                </div>
              </div>
            </div>
            <?php /*
            <div class="col-md-4">
                <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Recently Added Products</h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="box-body">
                    <ul class="products-list product-list-in-box">
                      <li class="item">
                        <div class="product-img">
                          <img src="{{URL::to('img/default-50x50.gif')}}" alt="Product Image">
                        </div>
                        <div class="product-info">
                          <a href="javascript:void(0)" class="product-title">Samsung TV
                            <span class="label label-warning pull-right">$1800</span></a>
                              <span class="product-description">
                                Samsung 32" 1080p 60Hz LED Smart HDTV.
                              </span>
                        </div>
                      </li>
                      <!-- /.item -->
                      <li class="item">
                        <div class="product-img">
                          <img src="{{URL::to('img/default-50x50.gif')}}" alt="Product Image">
                        </div>
                        <div class="product-info">
                          <a href="javascript:void(0)" class="product-title">Bicycle
                            <span class="label label-info pull-right">$700</span></a>
                              <span class="product-description">
                                26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                              </span>
                        </div>
                      </li>
                      <!-- /.item -->
                      <li class="item">
                        <div class="product-img">
                          <img src="{{URL::to('img/default-50x50.gif')}}" alt="Product Image">
                        </div>
                        <div class="product-info">
                          <a href="javascript:void(0)" class="product-title">Xbox One <span class="label label-danger pull-right">$350</span></a>
                              <span class="product-description">
                                Xbox One Console Bundle with Halo Master Chief Collection.
                              </span>
                        </div>
                      </li>
                      <!-- /.item -->
                      <li class="item">
                        <div class="product-img">
                          <img src="{{URL::to('img/default-50x50.gif')}}" alt="Product Image">
                        </div>
                        <div class="product-info">
                          <a href="javascript:void(0)" class="product-title">PlayStation 4
                            <span class="label label-success pull-right">$399</span></a>
                              <span class="product-description">
                                PlayStation 4 500GB Console (PS4)
                              </span>
                        </div>
                      </li>
                      <!-- /.item -->
                    </ul>
                  </div>
                  <div class="box-footer text-center">
                    <a href="javascript:void(0)" class="uppercase">View All Products</a>
                  </div>
                </div>
            </div> */ ?>
          </div>

          <?php /* <div class="row">

           <section class="col-lg-7 connectedSortable">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                  <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
                  <li><a href="#sales-chart" data-toggle="tab">Donut</a></li>
                  <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
                </ul>
                <div class="tab-content no-padding">
                  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
                </div>
              </div>
            </section>
          </div> */ ?>
      </section>
@endsection
@section('uniquePageScript')
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<!-- <script src="{{url('js/dashboard.js')}}"></script> -->
@endsection

