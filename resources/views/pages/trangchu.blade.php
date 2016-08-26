@extends('layout.index')
@section('title','Trang chủ')
@section('content')
<div class="container">

	<!-- slider -->
	@include('layout.slide')
    <!-- end slide -->

    <div class="space20"></div>


    <div class="row main-left">
		@include('layout.menu')

        <div class="col-md-9">
            <div class="panel panel-default">            
            	<div class="panel-heading" style="background-color:#337AB7; color:white;" >
            		<h2 style="margin-top:0px; margin-bottom:0px;">Laravel Tin Tức</h2>
            	</div>

            	<div class="panel-body">
            		@foreach($theloai as $tl)
	            		@if(count($tl->loaitin) > 0)
	            		<!-- item -->
					    <div class="row-item row">
		                	<h3>
		                		{{$tl->Ten}} | 	
		                		@foreach($tl->loaitin as $lt)
		                		<small><a href="loaitin/{{$lt->id}}/{{$lt->TenKhongDau}}.html"><i>{{$lt->Ten}}</i></a>/</small>
		                		@endforeach
		                	</h3>
		                	<?php
		                	$data = $tl->tintuc->where('NoiBat',1)->sortByDesc('created_at')->take(5);
		                	// shift sẽ lấy ra 1 tin( kiểu mảng) trong 5 tin ở $data và khi đó trong data còn 4 tin
		                	$tin1 = $data->shift();
		                	?>
		                	<div class="col-md-8 border-right">
		                		<div class="col-md-5">
			                        <a href="tintuc/{{$tin1['id']}}/{{$tin1['TieuDeKhongDau']}}.html">
			                            <img width="200px" height="300px" class="img-responsive" src="upload/tintuc/{{$tin1['Hinh']}}" alt="" style="margin-top: 25px;">
			                        </a>
			                    </div>

			                    <div class="col-md-7">
			                       <a href="tintuc/{{$tin1['id']}}/{{$tin1['TieuDeKhongDau']}}.html" title=""> <h3>{{$tin1['TieuDe']}}</h3></a>
			                        <p>{{$tin1['TomTat']}}</p>
			                        <a class="btn btn-primary" href="tintuc/{{$tin1['id']}}/{{$tin1['TieuDeKhongDau']}}.html">Xem Thêm<span class="glyphicon glyphicon-chevron-right"></span></a>
								</div>

		                	</div>
		                    

							<div class="col-md-4">
							@foreach($data->all() as $tintuc)
								<a href="tintuc/{{$tintuc['id']}}/{{$tintuc['TieuDeKhongDau']}}.html">
									<h4>
										<span class="glyphicon glyphicon-list-alt"></span>
										{{$tintuc['TieuDe']}}
									</h4>
								</a>
								@endforeach
							</div>
							
							<div class="break"></div>
		                </div>
		                <!-- end item -->
		                @endif
					@endforeach
				</div>
            </div>
    	</div>
    </div>
    <!-- /.row -->
</div>
@endsection