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
{{-- Invoice List --}}
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
{{-- Invoice List --}}
@endisset

@isset($createInvoice)
{{-- create Invoice --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="update_service" action="{{route('add-invoice')}}">
					@csrf
					<div class="service-fields">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Supplier <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="supplier"> 
										@foreach ($suppliers as $supplier)
											<option value="{{$supplier->supplier_id}}">{{$supplier->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Custom Invoice Number </label>
									<input class="form-control" type="text" name="invoiceNumber">
								</div>
							</div>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Item <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" id="itemName"> 
										@foreach ($items as $item)
											<option value="{{$item->inv_item_id}}" data-item_id="{{$item->inv_item_id}}" data-item_name="{{$item->name}}">{{$item->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>UOM </label>
									<select class="select2 form-select form-control" id="uom"> 
										@foreach ($units as $unit)
											<option value="{{$unit->unit_id}}" data-uom_id="{{$unit->unit_id}}" data-uom_name="{{$unit->name}}">{{$unit->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Batch No</label>
									<input class="form-control" type="text" id="batchNo">
								</div>
							</div>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Batch Qty <span class="text-danger">*</span></label>
									<input class="form-control" type="text" id="batchQty">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Unit Price </label>
									<input class="form-control" type="text" id="unitPrice">
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<label>Expire Date</label>
									<input class="form-control" type="date" id="expDate">
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<a href="javascript:void(0)" class="btn btn-primary float-right mt-2" id="add-to-list">Add Item</a>
								</div>
							</div>
						</div>
					</div>
					<div class="service-fileds">
						<div class="row">
							<table class="table table-bordered" id="list">
								<colgroup>
									<col width="20%">
									<col width="15%">
									<col width="20%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="5%">
								</colgroup>
								<thead>
									<tr>
										<th class="text-center">Item Name</th>
										<th class="text-center">Units</th>
										<th class="text-center">Batch No.</th>
										<th class="text-center">Expiry Date</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Unit Price</th>
										<th class="text-center">Amount</th>
										<th class="text-center"></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
								{{-- <tfoot> --}}
									
								{{-- </tfoot> --}}
							</table>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="submit-section">
								<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="processInvoice">Process Invoice</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- create Invoice --}}

{{-- Table row to clone --}}
<div id="tr_clone">
	<table>
	<tr class="item-row">
		<td>
			<input type="hidden" name="item[]" value="">
			<p class="item"></p>
		</td>
		<td>
			<p class="unit"></p>
			<input type="hidden" name="unit[]">
		</td>
		<td>
			<p class="batch"></p>
			<input type="hidden" name="batch[]" >
		</td>
		<td>
			<p class="expiryDate"></p>
			<input type="hidden" name="expiryDate[]" required>
		</td>
		<td>
			<p class="qty"></p>
			<input type="hidden" min="1" step="any" name="qty[]" >
		</td>
		<td>
			<p class="uprice"></p>
			<input type="hidden" min="1" step="any" name="uprice[]">
		</td>		
		<td>
			<p class="amount text-right"></p>
		</td>
		<td class="text-center">
			<a class="btn btn-sm btn-danger" onclick = "rem_list($(this))"><i class="fa fa-trash"></i></a>
		</td>
	</tr>
	</table>
</div>
{{-- Table row to clone --}}

{{-- style --}}
<style type="text/css">
	#tr_clone{
		display: none;
	}
	td{
		vertical-align: middle !important;
		justify-content: center;
	}
	td>input{
		margin:auto;
	}
	td p {
		margin: unset;
	}
	td input{
		height: calc(100%);
		width: calc(100%);
		border: unset;

	}
	td input:focus{
		border: unset;    
		outline-width: inherit;
	}
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
	  -webkit-appearance: none; 
	  margin: 0; 
	}
</style>
{{-- style --}}
@endisset
<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
	<script>
		
		function rem_list(_this){
				_this.closest('tr').remove()
				// calculate_total()
			}
		$(document).ready(function() {

			$('#add-to-list').click(function(){
				event.preventDefault();
				jQuery.noConflict();
				var tr = $('#tr_clone tr.item-row').clone();
				var item = $('#itemName').val(),
					batchNo=$('#batchNo').val(),
					batchQty = $('#batchQty').val(),
					uom=$('#uom').val(),
					unitPrice = $('#unitPrice').val(),
					expireDate=$('#expDate').val();

				if($('#list').find('tr[data-item="'+item+'"]').length > 0){
					// ("Product already on the list",'danger')
					Snackbar.show({
						text: "Product already on the list",
						pos: 'top-right',
						actionTextColor: '#fff',
						backgroundColor: '#e7515a'
					});
					return false;
				}
				if(item == '' || batchNo == '' || unitPrice ==''){
					alert_toast("Please complete the fields first",'danger')
					return false;
				}

				tr.attr('data-item',item)
				tr.attr('data-batch',batchNo)
				tr.find('.item').html($("#itemName option[value='"+item+"']").attr('data-item_name'))
				tr.find('.unit').html($("#uom option[value='"+uom+"']").attr('data-uom_name'))
				tr.find('.batch').html(batchNo)
				tr.find('.expiryDate').html(expireDate)
				tr.find('.qty').html(batchQty)
				tr.find('.uprice').html(unitPrice)


				tr.find('[name="item[]"]').val(item)
				tr.find('[name="unit[]"]').val(uom)
				tr.find('[name="batch[]"]').val(batchNo)
				tr.find('[name="expiryDate[]"]').val(expireDate)
				tr.find('[name="qty[]"]').val(batchQty)
				tr.find('[name="uprice[]"]').val(unitPrice)
				
				var amount = parseFloat(unitPrice) * parseFloat(batchQty);
				tr.find('.amount').html(parseFloat(amount).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2,minimumFractionDigits:2}))
				$('#list tbody').append(tr)
		 			$('#itemName').val('')
					$('#batchNo').val('')
					$('#batchQty').val('')
					$('#uom').val('')
					$('#unitPrice').val('')
					$('#expDate').val('')
			});
			//
		});
	</script>

	
@endpush
