@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">{{$title}}</h3>
	<ul class="breadcrumb float-right">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">{{$title}}</li>
	</ul>
</div>
@endpush


@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
			<!-- Add Medicine -->
			<form method="post" enctype="multipart/form-data" id="update_service" action="{{route('add-item')}}">
				@csrf
				<div class="service-fields mb-3">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Item Category <span class="text-danger">*</span></label>
								<select class="select2 form-select form-control" name="category"> 
									@foreach ($categories as $category)
										<option value="{{$category->category_id}}">{{$category->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
                        <div class="col-lg-6">
							<div class="form-group">
								<label>Item Group <span class="text-danger">*</span></label>
								<select class="select2 form-select form-control" name="group"> 
									@foreach ($groups as $group)
										<option value="{{$group->group_id}}">{{$group->name}}</option>
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
								<input class="form-control" type="text" name="name">
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label>Item Units<span class="text-danger">*</span></label>
                                <select class="select2 form-select form-control" name="units"> 
									@foreach ($units as $unit)
										<option value="{{$unit->unit_id}}">{{$unit->name}}</option>
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
								<input class="form-control service-desc" name="strength">
							</div>
						</div>
                        <div class="col-lg-5">
							<div class="form-group">
								<label>Descriptions / Brand Name</label>
								<input class="form-control service-desc" name="description">
							</div>
						</div>
                        <div class="col-lg-3">
							<div class="form-group mt-5 ml-5">
                                <input class="form-check-input " type="checkbox" value="1" id="prescription" name="prescription" >
                                <label class="form-check-label" for="prescription">
                                    Require Prescription
                                </label>
							</div>
						</div>
						
					</div>
				</div>
				
				
				<div class="submit-section float-right">
					<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit">Submit</button>
				</div>
			</form>
			<!-- /Add Medicine -->


			</div>
		</div>
	</div>			
</div>
@endsection

@push('page-js')
	<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush

