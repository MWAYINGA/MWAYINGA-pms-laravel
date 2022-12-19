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

@endpush

@section('content')

@isset($stockStatus)
{{-- Stock Status List --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				{{-- {{$invoiceItems}} --}}
				<div class="table-responsive">
					<table id="datatable" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>Item Name</th>
								<th>UOM</th>
                                <th>Quantity</th>
								<th>Store</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($stockOnHand as $status)
								<tr>
									<td class="">{{$status->itemName}}</td>
									<td class="">{{$status->uOM}}</td>
									<td class="">{{$status->quantity}}</td>
									<td class="">{{$store->name}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>			
</div>
{{-- Stock Status List --}}

@endisset



<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>	
@endpush
