@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">{{$tittle}}</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">{{$tittle}}</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#add_adjustment_factor" data-toggle="modal" class="btn btn-primary float-right mt-2">New Factor</a>
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
                                <th>Description</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>creator</th>
								<th>Created date</th>
							</tr>
						</thead>
						<tbody>
						 @foreach ($factors as $factor)
                            <tr>
                                <td>{{$factor->name}}</td>
                                <td>{{$factor->description}}</td>
                                <td>
                                    @foreach($types as $type)
                                    @if($factor->type == $type->type_id){{$type->name }} @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($categories as $category)
                                    @if($factor->category == $category->category_id){{$category->name }} @endif
                                    @endforeach
                                </td>
                                <td class="">
                                    @foreach($users as $userss)
                                    @if($factor->created_by == $userss->id){{$userss->name }} @endif
                                    @endforeach
                                </td>
                                <td>{{$factor->date_created}}</td>
                                {{-- <td>{{$factor->adjustment_factor_id}}</td> --}}
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
<div class="modal fade" id="add_adjustment_factor" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Adjustment Factor</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{route('adjFactors')}}">
					@csrf
					<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Factor Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="Description" class="form-control">
                            </div>
                        </div>
					</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="select2 form-select form-control" name="category"> 
                                    @foreach ($categories as $category)
                                        <option value=""></option>
                                        <option value="{{$category->category_id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="select2 form-select form-control" name="type"> 
                                    @foreach ($types as $type)
                                        <option value=""></option>
                                        <option value="{{$type->type_id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
					<button type="submit" class="btn btn-primary btn-block">Save</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /ADD Modal -->

<!-- Edit Details Modal -->
<div class="modal fade" id="edit_item-group" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Item Group</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('adjFactors')}}">
					@csrf
					@method("PUT")
					<div class="row form-row">
						<div class="col-12">
							<input type="hidden" name="group_id" id="edit_group_id">
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
<x-modals.delete :route="'adjFactors'" :title="'Adjustment Factor'" />
<!-- /Delete Modal -->
@endsection


@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
	<script>
		$(document).ready(function() {
			$('#item-group-table').on('click','.editbtn',function (){
				event.preventDefault();
				jQuery.noConflict();
				$('#edit_item-group').modal('show');
				var id = $(this).data('group_id');
				var name = $(this).data('name');
				$('#edit_group_id').val(id);
				$('.edit_name').val(name);
			});
			//
		});
	</script>
	
@endpush
