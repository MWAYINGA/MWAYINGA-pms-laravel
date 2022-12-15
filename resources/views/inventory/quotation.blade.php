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
	@isset($viewInvoice)
	<a href="{{route('add-invoice')}}" class="btn btn-primary float-right mt-2">Create New</a>
	@endisset
	@isset($createInvoice)
	<a href="{{route('invoices')}}" class="btn btn-primary float-right mt-2">View Invoices</a>
	@endisset
</div>

@endpush

@section('content')

@isset($viewInvoice)
{{-- Quotations List --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				{{-- {{$invoiceItems}} --}}
				<div class="table-responsive">
					<table id="item-list-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>#</th>
								<th>No:</th>
                                <th>Invoice No.</th>
								<th>Supplier</th>
								<th>Store Name</th>
                                <th>Creator</th>
                                <th>Date Created</th>
								<th>Authorizer</th>
                                <th>Status</th>
							</tr>
						</thead>
						<tbody>
							@php
								$index=1;
							@endphp
							@foreach ($invoices as $invoice)
								<tr>
									<td>
									<a class="" data-toggle="collapse" data-target="#demo{{$invoice->invoice_id}}" class="accordion-toggle"><span class='icon-field'><i class="fa fa-eye"></i></span></a>
									</td>
									<td class="">{{$index}}</td>
									<td class="">{{$invoice->invoiceNumber}}</td>
									<td class="">{{$invoice->supplierName}}</td>
									<td class="">{{$invoice->storeName}}</td>
									<td class="">
										@foreach($users as $userss)
										@if($invoice->created_by == $userss->id){{$userss->name }} @endif
										@endforeach</td>
									<td class="">{{$invoice->date_created}}</td>
									<td class="">
										@foreach($users as $userss)
										@if($invoice->completed_by == $userss->id){{$userss->name }} @endif
										@endforeach</td>
									<td class="">{{  ($invoice->completed ==1) ? "Completed" : "New" }}</td>
								</tr>
								<tr>
									<td colspan="9" class="hiddenRow">
										<div class="accordian-body collapse" id="demo{{$invoice->invoice_id}}">
											<table class="table table-striped" id="list">
												<thead>
													<tr>
														<th>Item Name</th>
														<th>UOM</th>
														<th>Batch</th>
														<th>Batch Quantity</th>
														<th>Expire Date</th>
														<th>Unit Price</th>
														<th>Amount</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($invoiceItems as $item)
														@if ($item->invoice == $invoice->invoice_id)
															<tr>
																<td>{{$item->itemName}}</td>
																<td>{{$item->uOM}}</td>
																<td>{{$item->batchNo}}</td>
																<td>{{$item->quantity}}</td>
																<td>{{$item->expireDate}}</td>
																<td>{{$item->unit_price}}</td>
																<td>{{$item->unit_price * $item->quantity}}</td>
															</tr>
														@endif
													@endforeach
												</tbody>
												<tfoot>
													@if ($invoice->completed ==0)
														<tr>
															<td colspan="7">
																<form action="{{route('invoice-approval')}}" enctype="multipart/form-data" id="update_invoice{{$invoice->invoice_id}}" method="post">
																	@csrf
																	<input type="hidden" name="invoiceId" value="{{$invoice->invoice_id}}">
																	<div class="submit-section">
																		<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="ApproveInvoice">Approve Invoice</button>
																	</div>
																</form>
															</td>
														</tr>
													@endif
												</tfoot>
											</table>
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
{{-- Quotations List --}}
@endisset

<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>	
@endpush
