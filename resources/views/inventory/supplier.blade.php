@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">{{$title}}</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">{{$title}}</li>
	</ul>
</div>
<div class="col-sm-5 col">
	@isset($viewSupplier)
	<a href="{{route('new-supplier')}}" class="btn btn-primary float-right mt-2">Add New</a>
	@endisset
	@isset($createSupplier)
	<a href="{{route('supplier')}}" class="btn btn-primary float-right mt-2">View Suppliers</a>
	@endisset
</div>

@endpush

@section('content')

@isset($viewSupplier)
{{-- Invoice List --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="item-list-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>No:</th>
                                <th>Name</th>
								<th>Description</th>
                                <th>Creator</th>
                                <th>Date Created</th>
                                <th class="text-center action-btn">Action</th>
							</tr>
						</thead>
						<tbody>
                            @php
                              $index=1;  
                            @endphp
                            @foreach ($suppliers as $supplier)
								{{-- <option value="{{$supplier->supplier_id}}">{{$supplier->name}}</option> --}}
                                <tr>
                                    <td>{{$index}}</td>
                                    <td>{{$supplier->name}}</td>
                                    <td>{{$supplier->description}}</td>
                                    <td>@foreach($users as $user)
                                        @if($user->id == $supplier->created_by) {{$user->name}} @endif
                                        @endforeach</td>
                                    <td>{{$supplier->date_created}}</td>
                                    <td class="text-center">
                                        <div class="actions">
                                            <a class="btn btn-sm bg-success-light editbtn " href="{{route('edit-supplier',$supplier->supplier_id)}}">
                                                <i class="fe fe-pencil"></i> Edit
                                            </a>
                                            <a data-id="{{$supplier->supplier_id}}" data-toggle="modal" href="javascript:void(0)" class="btn btn-sm bg-danger-light deletebtn" title= "Retire this Supplier">
                                                <i class="fe fe-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @php
                              $index++;  
                            @endphp
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>			
</div>
{{-- Invoice List --}}
@endisset

@isset($createSupplier)
{{-- create Supplier --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="update_service" action="{{route('new-supplier')}}">
					@csrf
					<div class="service-fields">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Supplier Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Description </label>
									<input class="form-control" type="text" name="Description">
								</div>
							</div>
						</div>
					</div>
                    <hr>
					<div class="service-fields">
						<div class="row">
							@foreach ($attributeType as $type)
							<div class="col-md-3">
								<div class="form-group">
									<label>{{$type->name}} <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="attributeType[{{$type->type_id}}]">
								</div>
							</div>
							@endforeach
						</div>
						{{-- <div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>TIN NUMBER <span class="text-danger">*</span></label>
									<input class="form-control" type="text" id="tinNumber">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Address <span class="text-danger">*</span></label>
									<input class="form-control" type="text" id="pAddress">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>OwnerShip <span class="text-danger">*</span></label>
									<input class="form-control" type="text" id="OwnerShip">
								</div>
							</div>
						</div> --}}
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="submit-section">
								<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="addSupplier">Submit</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- create Supplier --}}

@endisset
<!-- Delete Modal -->
<x-modals.delete :route="'supplier'" :title="'Retire Supplier'" />
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush
