@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">Edit Product</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Edit Product</li>
	</ul>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
				

			<!-- Edit Medicine -->
				<form method="post" enctype="multipart/form-data" id="update_service" action="{{route('edit-item',$item->inv_item_id)}}">
					@csrf
					<div class="service-fields mb-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Item Category <span class="text-danger">*</span></label>
                                    <select class="select2 form-select form-control" name="category"> 
                                        @foreach ($categories as $category)
                                            <option @if($item->category==$category->category_id)selected @endif value="{{$category->category_id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Item Group <span class="text-danger">*</span></label>
                                    <select class="select2 form-select form-control" name="group"> 
                                        @foreach ($groups as $group)
                                            <option @if($item->group==$group->group_id)selected @endif value="{{$group->group_id}}">{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>item Name<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" value="{{$item->name}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Item Units<span class="text-danger">*</span></label>
                                    <select class="select2 form-select form-control" name="units"> 
                                        @foreach ($units as $unit)
                                            <option  @if($item->units==$unit->unit_id) selected @endif value="{{$unit->unit_id}}">{{$unit->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Strength</label>
                                    <input class="form-control service-desc" name="strength" value="{{$item->strength}}">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label>Descriptions / Brand Name</label>
                                    <input class="form-control service-desc" name="description" value="{{$item->description}}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <input class="form-check-input " type="checkbox" value="1" id="prescription" name="prescription" @if( $item->prescription == 1 ) checked @endif>
                                    <label class="form-check-label" for="prescription" >
                                        Require Prescription
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>					
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit">Submit</button>
					</div>
				</form>
			<!-- /Edit Medicine -->
			</div>
		</div>
	</div>			
</div>
@endsection


@push('page-js')
	<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush




