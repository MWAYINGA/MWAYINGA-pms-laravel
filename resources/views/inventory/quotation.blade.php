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
								<th></th>
								<th>Date Created</th>
								<th>Quote No:</th>
                                <th>Customer name</th>
								<th>Quoted Amount</th>
								<th>Payable Amount</th>
								<th>Status</th>
                                <th>Creator</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($quotations as $quotation)
								<tr>
									<td>
									<a class="" data-toggle="collapse" data-target="#demo{{$quotation->quote_id}}" class="accordion-toggle"><span class='icon-field'><i class="fa fa-eye"></i></span></a>
									</td>
									<td class="">{{$quotation->date_created}}</td>
									<td class="">{{$quotation->quote_id}}</td>
									<td class="">{{$quotation->customer}}</td>
									<td class="">{{$quotation->quoted_amount}}</td>
									<td class="">{{$quotation->payable_amount}}</td>
									<td class="">
										@foreach($quoteStatuses as $status)
										@if($quotation->status == $status->status_id){{$status->name }} @endif
										@endforeach</td>
									<td class="">
										@foreach($users as $userss)
										@if($quotation->created_by == $userss->id){{$userss->name }} @endif
										@endforeach</td>
								</tr>
								<tr>
									<td colspan="9" class="hiddenRow">
										<div class="accordian-body collapse" id="demo{{$quotation->quote_id}}">
											<table class="table table-striped" id="list">
												<thead>
													<tr>
														<th>SN.</th>
														<th>Item Name</th>
														<th>Quantity</th>
														<th>Units</th>
														<th>Price @ unit</th>
														<th>Quoted Amount</th>
														<th>Payable Amount</th>
														<th>Status</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													@foreach ($quotationLines as $line)
														@if ($line->quote == $quotation->quote_id)
														@php
															$index=1;
														@endphp
															<tr>
																<td>{{$index}}</td>
																<td>{{$line->uOM}}</td>
																<td>{{$line->batchNo}}</td>
																<td>{{$line->quantity}}</td>
																<td>{{$line->expireDate}}</td>
																<td>{{$line->unit_price}}</td>
																<td>{{$line->unit_price * $item->quantity}}</td>
															</tr>
															@php
																$index++;
															@endphp
														@endif
													@endforeach
												</tbody>
												<tfoot>
													@if ($quotation->status ==0)
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
