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
	{{-- <a href="#add_item_units" data-toggle="modal" class="btn btn-primary float-right mt-2">Add Item Units</a> --}}
</div>

@endpush

@section('content')

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>Item Name</th>
								<th>Batch</th>
                                <th>Quantity</th>
								<th>Expire Date</th>
								<th>Store</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($expiredBatches as $status)
								<tr>
									<td class="">{{$status->itemName}}</td>
									<td class="">{{$status->batchNo}}</td>
									<td class="">{{$status->quantity}}</td>
									<td class="">{{$status->expireDate}}</td>
									<td class="">{{$status->storeName}}</td>
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
