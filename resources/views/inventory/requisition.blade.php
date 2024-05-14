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
	@isset($viewRequest)
	<a href="{{route('new-request')}}" class="btn btn-primary float-right mt-2">Create New</a>
	@endisset
	@isset($createRequest)
	<a href="{{route('requisition')}}" class="btn btn-primary float-right mt-2">View Invoices</a>
	@endisset
</div>

@endpush

@section('content')

@isset($viewRequest)
{{-- Request List --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="item-list-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th></th>
								<th>#</th>
                                <th>Request No.</th>
								<th>Store Name</th>
                                <th>Date Requested</th>
								<th>Requested By</th>
                                <th>Status</th>
							</tr>
						</thead>
						<tbody>
							@php
								$index=1;
							@endphp
							@foreach ($requests as $request)
							@if ($requestStatus->where('request',$request->request_id)->where('completedStatus',0)->isEmpty()){{$status="Requested"}}@endif
							@if (!($requestStatus->where('request',$request->request_id)->where('completedStatus',0)->isEmpty())){{$status="Issued"}}@endif
							@if (!($requestStatus->where('request',$request->request_id)->where('completedStatus',1)->isEmpty())){{$status="Completed"}}@endif
								<tr>
									<td>
									<a class="" data-toggle="collapse" data-target="#demo{{$request->request_id}}" class="accordion-toggle"><span class='icon-field'><i class="fa fa-eye"></i></span></a>
									</td>
									<td class="">{{$index}}</td>
									<td class="">{{$request->requestNumber}}</td>
									<td class="">{{$request->storeName}}</td>
									<td class="">{{$request->date_created}}</td>
									<td class="">
										@foreach($users as $userss)
										@if($request->created_by == $userss->id){{$userss->name }} @endif
										@endforeach</td>
									<td class="">
										{{$status}}
										</td>
								</tr>
								<tr  class="hiddenRow accordian-body collapse" id="demo{{$request->request_id}}">
									<td colspan="9">
										<div class="">
											<table class="table table-striped" id="list">
												<form action="{{route('requisition-approval')}}" method="post" enctype="multipart/form-data">
													@csrf
													<thead>
														<tr>
															<th>Description Item</th>
															<th>Unit</th>
															<th>Quantity Requested</th>
															<th>Quantity Issued</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
														@foreach ($requestItems as $item)
															@if ($item->request == $request->request_id)
															@php
																$qtyIssued= $requestStatus->where('requestItem',$item->request_item_id)->sum('batchQuantity')
															@endphp
																<tr>
																	<td>{{$item->itemName}}</td>
																	<td>{{$item->uOM}}</td>
																	<td>{{$item->quantity}}</td>
																	<td @if ($qtyIssued > 0)
																	data-toggle="collapse" data-target="#issue{{$item->request_item_id}}" class="accordion-toggle"
																	@endif>{{ ($qtyIssued > 0) ? $qtyIssued : '-'}}</td>
																	<td>{{ ($qtyIssued > 0) ? $status : 'Requested'}}</td>
																</tr>
																<tr  class="hiddenRow accordian-body collapse" id="issue{{$item->request_item_id}}">
																	<td colspan="9">
																		<div class="">
																			<input type="radio" name="approval[{{$item->request_item_id}}]" value="Accepted" id="Accepted" required>Accepted
																			<input type="radio" name="approval[{{$item->request_item_id}}]" value="Rejected"  id="Rejected">Rejected
																		</div>
																	</td>
																</tr>
															@endif
														@endforeach
													</tbody>
													@if ($status == "Issued")
													<tfoot>
														<tr>
															<td colspan="5">
																<div class="">
																	<button class="btn btn-secondary submit-btn" type="submit" name="form_submit" value="Submit" id="issueB{{$item->request}}">Submit</button>
																</div>
															</td>
														</tr>
													</tfoot>
													@endif
												</form>
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
{{-- Request List --}}
@endisset

@isset($createRequest)
{{-- create Request --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="update_service__" action="{{route('new-request')}}">
					@csrf
					<div class="service-fields">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Store <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="store">
										<option value="">select Requesting Store</option>  
										@foreach ($stores as $store)
											<option value="{{$store->store_id}}">{{$store->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Custom Request Number </label>
									<input class="form-control" type="text" name="requestNumber">
								</div>
							</div>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Item <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" id="itemName"> 
										<option value="">select Item</option> 
										@foreach ($items as $item)
											<option value="{{$item->inv_item_id}}" data-item_id="{{$item->inv_item_id}}" data-item_name="{{$item->name}}">{{$item->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>UOM </label>
									<select class="select2 form-select form-control" id="uom">
										<option value="">select Unit of Measure</option> 
										@foreach ($units as $unit)
											<option value="{{$unit->unit_id}}" data-uom_id="{{$unit->unit_id}}" data-uom_name="{{$unit->name}}">{{$unit->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Qty <span class="text-danger">*</span></label>
									<input class="form-control" type="text" id="itemQty">
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
									<col width="35%">
									<col width="10%">
									<col width="13%">
									<col width="13%">
									<col width="13%">
									<col width="13%">
									<col width="3%">
								</colgroup>
								<thead>
									<tr>
										<th rowspan="2">Description Item</th>
										<th rowspan="2">Units</th>
										<th colspan="2" class="text-center">Quantity</th>
										<th colspan="2" class="text-center">Ledger Folio</th>
									</tr>
									<tr>
										<th>Requested</th>
										<th>Issued</th>
										<th>Receiver</th>
										<th>Authorized</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="submit-section">
								<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="processRequest">Process Request</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- create Request --}}

{{-- Table row to clone --}}
<div id="tr_clone">
	<table>
	<tr class="item-row">
		<td>
            <a class="btnbtn-danger" onclick = "rem_list($(this))"><i class="fa fa-trash"></i></a>
			<input type="hidden" name="item[]">
			{{-- <span><a class="btnbtn-danger" onclick = "rem_list($(this))"><i class="fa fa-trash"></i></a> <span class="item"></span></span> --}}
			<p class="item"></p>
		</td>
		<td>
			<p class="unit"></p>
			<input type="hidden" name="unit[]">
		</td>
		<td>
            <p class="qty"></p>
			<input type="hidden" min="1" step="any" name="qty[]" >
		</td>
        <td></td>
		<td></td>
		<td></td>
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
					itemQty = $('#itemQty').val(),
					uom=$('#uom').val();


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
				if(item == '' || itemQty == '' || uom ==''){
                    Snackbar.show({
						text: "Please complete the fields first",
						pos: 'top-right',
						actionTextColor: '#fff',
						backgroundColor: '#e7515a'
					});
					// alert_toast("Please complete the fields first",'danger')
					return false;
				}

				tr.attr('data-item',item)
				tr.find('.item').html($("#itemName option[value='"+item+"']").attr('data-item_name'))
				tr.find('.unit').html($("#uom option[value='"+uom+"']").attr('data-uom_name'))
				tr.find('.qty').html(itemQty)



				tr.find('[name="item[]"]').val(item)
				tr.find('[name="unit[]"]').val(uom)
				tr.find('[name="qty[]"]').val(itemQty)

				
				$('#list tbody').append(tr)
		 			$('#itemName').val('').change()
					$('#itemQty').val('')
					$('#uom').val('').change()
			});
			//
		});
	</script>

	
@endpush
