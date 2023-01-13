@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">{{$tittle}}</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">{{$tittle}}</li>
	</ul>
</div>
<div class="col-sm-5 col">
	@isset($viewAdjustments)
	<a href="{{route('add-adjustments')}}" class="btn btn-primary float-right mt-2">New</a>
	@endisset
	@isset($createAdjustments)
	<a href="{{route('adjustments')}}" class="btn btn-primary float-right mt-2">View</a>
	@endisset
</div>

@endpush

@section('content')
@isset($viewAdjustments)
{{-- Adjustments List --}}
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
                                <th>Reconciliation No.</th>
								<th>Store Name</th>
                                <th>Date Created</th>
								<th>Created By</th>
                                <th>Status</th>
							</tr>
						</thead>
						<tbody>
							{{-- @php
								$index=1;
							@endphp
							@foreach ($requests as $request)
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
								<tr>
									<td colspan="9" class="hiddenRow">
										<div class="accordian-body collapse" id="demo{{$request->request_id}}">
											<table class="table table-striped" id="list">
												<form action="{{route('adjustment-approval')}}" method="post" enctype="multipart/form-data">
													@csrf
													<thead>
														<tr>
															<th>Description</th>
															<th>Batch</th>
															<th>Current Stock</th>
															<th>Physical Stock</th>
                                                            <th>Difference</th>
															<th>Factor</th>
                                                            <th>Remark</th>
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
																<tr>
																	<td colspan="9" class="hiddenRow">
																		<div class="accordian-body collapse" id="issue{{$item->request_item_id}}">
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
							@endforeach --}}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>			
</div>
{{-- Adjustments List --}}
@endisset

@isset($createAdjustments)
{{-- create Adjustments --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="update_service__" action="{{route('new-request')}}">
					@csrf
					<div class="service-fields">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Custom Request Number </label>
									<input class="form-control" type="text" name="requestNumber">
								</div>
							</div>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="col-md-2">
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
							<div class="col-md-2">
								<div class="form-group">
									<label>Batch </label>
									<select class="select2 form-select form-control" id="batch">
										<option value="">select Batch / Serial No</option> 
										{{-- @foreach ($units as $unit)
											<option value="{{$unit->unit_id}}" data-uom_id="{{$unit->unit_id}}" data-uom_name="{{$unit->name}}">{{$unit->name}}</option>
										@endforeach --}}
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Current Stock</label>
									<input class="form-control" type="text" id="currentStock">
								</div>
							</div>
                            <div class="col-md-2">
								<div class="form-group">
									<label>Physical Count</label>
									<input class="form-control" type="text" id="physicalStock">
								</div>
							</div>
                            <div class="col-md-2">
								<div class="form-group">
									{{-- <label>Physical Count</label> --}}
                                    <select class="select2 form-select form-control" id="factor" aria-placeholder="Select Factor">
										<option value="">select Batch / Serial No</option> 
										{{-- @foreach ($units as $unit)
											<option value="{{$unit->unit_id}}" data-uom_id="{{$unit->unit_id}}" data-uom_name="{{$unit->name}}">{{$unit->name}}</option>
										@endforeach --}}
									</select>
								</div>
							</div>
                            <div class="col-md-2">
								<div class="form-group">
									<label>Remarks</label>
									<input class="form-control" type="text" id="remarks">
								</div>
							</div>
                            <div class="col-md-1">
								<div class="form-group">
									<a href="javascript:void(0)" class="btn btn-primary float-right mt-2" id="add-to-list">Add Item</a>
								</div>
							</div>
						</div>
					</div>
					<div class="service-fileds">
						<div class="row">
							<table class="table table-bordered" id="list">
								<thead>
									<tr>
										<th>DESCRIPTION</th>
										<th>BATCH/SERIAL NO.</th>
										<th>BATCH QTY</th>
										<th>PHYSICAL COUNT</th>
                                        <th>DIFFERENCE</th>
                                        <th>CATEGORY</th>
                                        <th>REMARKS</th>
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
{{-- create Adjustments --}}

{{-- Table row to clone --}}
<div id="tr_clone">
	<table>
	<tr class="item-row">
		<td>
            <a class="btnbtn-danger" onclick = "rem_list($(this))"><i class="fa fa-trash"></i></a>
            <input type="hidden" name="item[]">
			<p class="item"></p>
		</td>
		<td>
			<input type="hidden" name="batchNo[]">
			<p class="batchNo"></p>
		</td>
        <td>
			<input type="hidden" name="batchQty[]">
			<p class="batchQty"></p>
		</td>
		<td>
            <input type="hidden" name="pcount[]">
			<p class="pcount"></p>
		</td>
        <td>
            <input type="hidden" name="difference[]">
			<p class="difference"></p>
        </td>
		<td>
            <input type="hidden" name="category[]">
			<p class="category"></p>
        </td>
		<td>
            <input type="hidden" name="factor[]">
			<p class="factor"></p>
        </td>
        <td>
            <input type="hidden" name="remark[]">
			<p class="remark"></p>
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
