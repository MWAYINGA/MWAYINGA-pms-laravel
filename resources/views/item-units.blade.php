@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Item Units</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Item Units</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#add_item_units" data-toggle="modal" class="btn btn-primary float-right mt-2">Add Item Units</a>
</div>

@endpush

@section('content')

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="item-unit-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>Name</th>
                                <th>creator</th>
								<th>Created date</th>
								<th class="text-center action-btn">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($units as $itemunit)
							<tr>								
								<td>
									<h2 class="table-avatar">	
										{{$itemunit->name}}
									</h2>
								</td>
								<td>
									{{-- {{$itemunit->creator}} --}}
									{{-- {{in_array($users->id,[$itemunit->created_by])?
										$users->name:"";
									}} --}}
                                    @foreach($users as $userss)
                                    
                                    @if($itemunit->created_by == $userss->id){{$userss->name }} @endif
                                    @endforeach
                                </td>
								<td>{{date_format(date_create($itemunit->date_created),"d M,Y")}}</td>

								<td class="text-center">
									<div class="actions">
										<a data-id="{{$itemunit->unit_id}}" data-name="{{$itemunit->name}}" class="btn btn-sm bg-success-light editbtn " data-toggle="modal" href="javascript:void(0)">
											<i class="fe fe-pencil"></i> Edit
										</a>
										<a data-id="{{$itemunit->unit_id}}" data-toggle="modal" href="javascript:void(0)" class="btn btn-sm bg-danger-light deletebtn">
											<i class="fe fe-trash"></i> Delete
										</a>
									</div>
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

<!-- Add Modal -->
<div class="modal fade" id="add_item_units" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Item Unit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{route('itemUnits')}}">
					@csrf
					<div class="row form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Item Unit Name</label>
								<input type="text" name="name" class="form-control">
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">Save Changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /ADD Modal -->

<!-- Edit Details Modal -->
<div class="modal fade" id="edit_item_units" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Item Units</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('itemUnits')}}">
					@csrf
					@method("PUT")
					<div class="row form-row">
						<div class="col-12">
							<input type="hidden" name="unit_id" id="edit_unit_id">
							<div class="form-group">
								<label>Name</label>
								<input type="text" class="form-control edit_name" name="name">
							</div>
						</div>
						
					</div>
					<button type="submit" class="btn btn-primary btn-block">Save Changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Edit Details Modal -->

<!-- Delete Modal -->
<x-modals.delete :route="'itemUnits'" :title="'Item Units'" />
<!-- /Delete Modal -->
@endsection


@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
	<script>
		$(document).ready(function() {
			$('#item-unit-table').on('click','.editbtn',function (){
				event.preventDefault();
				jQuery.noConflict();
				$('#edit_item_units').modal('show');
				var id = $(this).data('unit_id');
				var name = $(this).data('name');
				$('#edit_unit_id').val(id);
				$('.edit_name').val(name);
			});
			//
		});
	</script>
	
@endpush
