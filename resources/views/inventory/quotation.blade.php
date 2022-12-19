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

{{-- Quotations List --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				{{-- {{$invoiceItems}} --}}
				<div class="table-responsive">
					<table id="quotations-list-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
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
									<td class="">{{$quotation->customerName}}</td>
									<td class="">{{$quotation->total_quote}}</td>
									<td class="">{{$quotation->payable_amount}}</td>
									<td class="">{{$quotation->quoteStatus }} </td>
									<td class="">
										@foreach($users as $userss)
										@if($quotation->created_by == $userss->id){{$userss->name }} @endif
										@endforeach</td>
								</tr>
								<tr>
									<td colspan="9" class="hiddenRow">
										<div class="accordian-body collapse" id="demo{{$quotation->quote_id}}">
											<form action="{{route('pos-quotations')}}" enctype="multipart/form-data" id="update_invoice{{$quotation->quote_id}}" method="post">
												@csrf
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
																	<td>{{$line->itemName}}</td>
																	<td>{{$line->quantity}}</td>
																	<td>{{$line->uOM}}</td>
																	<td>{{(($line->quoted_amount)/($line->quantity))}}</td>
																	<td>{{$line->quoted_amount}}</td>
																	<td>{{$line->payable_amount}}</td>
																	<td>{{$line->lineStatus}}</td>
																	<td>
																		@if ($line->status == 5)
																		<input type="checkbox" name="quoteLine[]" id="" value="{{$line->quote_line_id}}">
																		@endif
																	</td>
																</tr>
																@php
																	$index++;
																@endphp
															@endif
														@endforeach
													</tbody>
													<tfoot>
														@if ($quotation->status ==1 ||$quotation->status ==2)
															<tr>
																<td colspan="7"></td>
																<td colspan="2">
																		<input type="hidden" name="quote" value="{{$quotation->quote_id}}">
																		<button class="btn btn-sm btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="quoteSubmit">Submit</button>
																</td>
															</tr>
														@endif
													</tfoot>
												</table>
											</form>
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


<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>	
@endpush
