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

@isset($detailedStockStatus)
{{-- Detailed Stock Status List --}}

@php
	$items=(!empty($items)) ? json_decode($items) : array();
@endphp
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable" class="datatable table table-striped table-bordered table-hover table-center mb-0" cellpadding="5">
						<thead>
							<tr style="boder:1px solid black;">
								<td class="border-1 b td-w2" >SN.</td>
								<td class="border-1 b " >Description</td>
								<td class="border-1 b " >Unit(s)</td>
								<td class="border-1 b " >Batch / Serial No.</td>
								<td class="border-1 b uk-text-center" >Quantity</td>
								<td class="border-1 b " >Expiry date</td>
							</tr>
						</thead>
						<tbody>
							@php
								$index=1;
							@endphp
							@foreach ($items as $status)
								<tr>
									<td class="border-1 ">{{$index}}</td>
									<td class="border-1 ">{{$status->itemName}}</td>
									<td class="border-1 ">{{$status->units}}</td>
									<td class="border-1 p-0">
										<table class="table-center border-colapse mb-0" cellpadding="5">
											<tbody>
												@foreach ($status->stocks as $batch)
													<tr >
														<td class="border-1">{{$batch->batchNo}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</td>
									<td class="border-1 p-0">
										<table class="table-center border-colapse tb-w100" cellpadding="5">
											<tbody>
												@foreach ($status->stocks as $batch)
													<tr >
														<td class="border-1">{{$batch->quantity}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</td>
									<td class="border-1 p-0">
										<table class="table-center border-colapse tb-w100" cellpadding="5">
											<tbody>
												@foreach ($status->stocks as $batch)
													<tr >
														<td class="border-1">{{$batch->expireDate}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
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
{{-- Detailed Stock Status List --}}
@endisset



<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>	
@endpush
