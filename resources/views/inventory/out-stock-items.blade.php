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

@endsection


@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>	
@endpush
