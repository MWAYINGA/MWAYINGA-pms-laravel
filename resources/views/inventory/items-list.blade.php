@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Item List</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Item List</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="{{route('add-item')}}" class="btn btn-primary float-right mt-2">Add New</a>
</div>

@endpush

@section('content')

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable-export" class="table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>No:</th>
                                <th>Category</th>
								<th>Item Name</th>
                                <th>Item Units</th>
                                <th>Decription / Brand</th>
                                <th>Require Prescription</th>
                                <th>Creator</th>
                                <th>Date Created</th>
								<th class="text-center action-btn">Actions</th>
							</tr>
						</thead>
						<tbody>
                            <?php $index=1; ?>
							@foreach ($items as $item)
							<tr>
                                <td>{{$index}}</td>								
								<td>
                                    @foreach($categories as $category)
                                    @if($item->category == $category->category_id){{$category->name }} @endif
                                    @endforeach
									{{-- <h2 class="table-avatar">{{$item->name}}</h2> --}}
								</td>
                                <td>
									<h2 class="table-avatar">{{$item->name}}</h2>
								</td>
                                <td>
                                    @foreach($units as $unit)
                                    @if($item->units == $unit->unit_id){{$unit->name }} @endif
                                    @endforeach
									{{-- <h2 class="table-avatar">{{$item->name}}</h2> --}}
								</td>
                                <td>
									<h2 class="table-avatar">{{$item->description}}</h2>
								</td>
                                <td>
                                    {{$item->prescription == 1 ? "true": "false"}}
									{{-- <h2 class="table-avatar">{{$item->prescription}}</h2> --}}
								</td>
								<td>
                                    @foreach($users as $userss)
                                    @if($item->created_by == $userss->id){{$userss->name }} @endif
                                    @endforeach
                                </td>
                                <td>{{date_format(date_create($item->date_created),"d M,Y")}}</td>
								<td class="text-center">
									<div class="actions">
										<a class="btn btn-sm bg-success-light editbtn " href="{{route('edit-item',$item->inv_item_id)}}">
											<i class="fe fe-pencil"></i> Edit
										</a>
										<a data-id="{{$item->inv_item_id}}" data-toggle="modal" href="javascript:void(0)" class="btn btn-sm bg-danger-light deletebtn" title= "Void this Item">
											<i class="fe fe-trash"></i> Delete
										</a>
									</div>
								</td>
							</tr>
                            <?php $index++?>
							@endforeach							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>			
</div>

<!-- Delete Modal -->
<x-modals.delete :route="'items'" :title="'Item List'" />
<!-- /Delete Modal -->
@endsection


@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush
