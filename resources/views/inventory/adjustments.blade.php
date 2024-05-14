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
						<colgroup>
							<col width="5%">
							<col width="5%">
							<col width="20%">
							<col width="20%">
							<col width="20%">
							<col width="20%">
							<col width="10%">
						</colgroup>
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
							@php
								$index=1;
							@endphp
							@foreach ($adjustments as $adj)
								<tr>
									<td>
										<a class="" data-toggle="collapse" data-target="#demo{{$adj->adjustment_id}}" class="accordion-toggle"><span class='icon-field'><i class="fa fa-eye"></i></span></a>
									</td>
									<td class="">{{$index}}</td>
									<td class="">{{$adj->adjustmentNumber}}</td>
									<td class="">{{$adj->storeName}}</td>
									<td class="">{{$adj->date_created}}</td>
									<td class="">
										@foreach($users as $userss)
										@if($adj->created_by == $userss->id){{$userss->name }} @endif
										@endforeach
									</td>
									<td class="">{{  ($adj->approved ==1) ? "Completed" : "New" }}</td>
								</tr>
								<tr>
									<td colspan="7" class="hiddenRow">
										<div class="accordian-body collapse table-responsive" id="demo{{$adj->adjustment_id}}">
											<table class="" id="list" style="width: 100%; table-layout:auto">
												<colgroup>
													<col width="25%">
													<col width="5%">
													<col width="5%">
													<col width="15%">
													<col width="15%">
													<col width="5%">
													<col width="10%">
												</colgroup>
												<thead>
													<tr>
														<th>Description</th>
														<th>Batch/Serial no.</th>
														<th>Reconciled Qty</th>
														<th>Factor</th>
														<th>Remark</th>
														<th>Date Approved</th>
														<th>Approved By</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($adjustmentBatches as $batch)
														@if ($batch->adjustment == $adj->adjustment_id)
															<tr>
																<td>{{$batch->itemName}}</td>
																<td>{{$batch->batchNo}}</td>
																<td>{{$batch->quantity}}</td>
																<td>{{$batch->factorName}}</td>
																<td>{{$batch->remarks}}</td>
																<td>{{$adj->date_approved}}</td>
																<td>
																	@foreach($users as $userss)
																	@if($adj->approved_by == $userss->id){{$userss->name }} @endif
																	@endforeach
																</td>
															</tr>
														@endif
													@endforeach
												</tbody>
												<tfoot>
													@if ($adj->approved ==0)
														<tr>
															<td colspan="5"></td>
															<td>
																<form action="{{route('adjustment-approve')}}" enctype="multipart/form-data" id="update_adjustments{{$adj->adjustment_id}}" method="post">
																	@csrf
																	<input type="hidden" name="adjustmentId" value="{{$adj->adjustment_id}}">
																	<div class="submit-section">
																		<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="ApproveReconsiliation">Approve</button>
																	</div>
																</form>
															</td>
															<td>
																<form action="{{route('adjustment-reject')}}" enctype="multipart/form-data" id="reject_adjustments{{$adj->adjustment_id}}" method="post">
																	@csrf
																	<input type="hidden" name="adjustmentId" value="{{$adj->adjustment_id}}">
																	<div class="submit-section">
																		<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="RejectReconsiliation">Reject</button>
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
{{-- Adjustments List --}}
@endisset

@isset($createAdjustments)
{{-- create Adjustments --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="create_adjustments" action="{{route('add-adjustments')}}">
					@csrf
					<div class="service-fields">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Custom Adjustments Number </label>
									<input class="form-control" type="text" name="adjustmentsNumber">
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
							<div class="col-md-1">
								<div class="form-group">
									<label>Batch </label>
									<select class="select2 form-select form-control" id="batch">
										<option value="">select Batch / Serial No</option> 
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
									<label>Factor</label>
                                    <select class="select2 form-select form-control" id="factor" aria-placeholder="Select Factor">
										<option value="">select Factor</option> 
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
									<a href="javascript:void(0)" class="btn btn-primary float-right mt-2" id="add-to-list">Add</a>
								</div>
							</div>
							{{csrf_field()}}
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
								<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="processRequest">Process Adjustments</button>
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
		{{-- <td>
            <input type="hidden" name="category[]">
			<p class="category"></p>
        </td> --}}
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
					batch = $('#batch').val(),
					currentStock = $('#currentStock').val(),
					physicalStock = $('#physicalStock').val(),
					factor = $('#factor').val(),
					remarks = $('#remarks').val(),
					difference = Math.abs((currentStock-physicalStock));


				// if($('#list').find('tr[data-item="'+item+'"]').length > 0){
				// 	// ("Product already on the list",'danger')
				// 	Snackbar.show({
				// 		text: "Product already on the list",
				// 		pos: 'top-right',
				// 		actionTextColor: '#fff',
				// 		backgroundColor: '#e7515a'
				// 	});
				// 	return false;
				// }
				// if(item == '' || itemQty == '' || uom ==''){
                //     Snackbar.show({
				// 		text: "Please complete the fields first",
				// 		pos: 'top-right',
				// 		actionTextColor: '#fff',
				// 		backgroundColor: '#e7515a'
				// 	});
				// 	// alert_toast("Please complete the fields first",'danger')
				// 	return false;
				// }

				tr.attr('data-item',item)
				tr.attr('data-batch',batch)
				tr.find('.item').html($("#itemName option[value='"+item+"']").attr('data-item_name'))
				tr.find('.batchNo').html($("#batch option[value='"+batch+"']").attr('data-batchNo'))
				tr.find('.factor').html($("#factor option[value='"+factor+"']").attr('data-name'))
				tr.find('.batchQty').html(currentStock)
				tr.find('.pcount').html(physicalStock)
				tr.find('.remark').html(remarks)
				tr.find('.difference').html(difference)

				tr.find('[name="item[]"]').val(item)
				tr.find('[name="batchNo[]"]').val(batch)
				tr.find('[name="factor[]"]').val(factor)
				tr.find('[name="batchQty[]"]').val(currentStock)
				tr.find('[name="pcount[]"]').val(physicalStock)
				tr.find('[name="difference[]"]').val(difference)
				tr.find('[name="remark[]"]').val(remarks)

				$('#list tbody').append(tr)
		 			$('#itemName').val('').change()
					$('#currentStock').val('');
					$('#physicalStock').val('');
					$("#batch").empty();
					$("#factor").empty();
					$('#remarks').val('');
			});
			//


			$("#itemName").change(function(){
				event.preventDefault();
				jQuery.noConflict();
				$('#currentStock').val('');
				$('#physicalStock').val('');
				$("#factor").empty();
                var itemId = $(this).val(),
					_token=$('input[name="_token"]').val();			
                $.ajax({
					url:"{{ route('itemBatches') }}",
                    method:'POST',
                    data:{
						itemId:itemId,
						_token:_token
					},
					dataType:'JSON',
                    success:function(response){
						// alert_toast(JSON.parse(response))
						console.log(response);
                        var len = response.length;
						$("#batch").empty();
						$("#batch").append("<option value=''>select Batch / Serial No</option> ");
                        for( var i = 0; i<len; i++){
                            var batchId = response[i]['batchId'];
                            var batchNo = response[i]['batchNo'];
							var store=response[i]['store'];
							var avilableQty = response[i]['avilableQty'];
                            $("#batch").append("<option value='"+batchId+"' data-store='"+store+"' data-batchNo='"+batchNo+"' data-avilableQty='"+avilableQty+"'>"+batchNo+"</option>");
                        }
                    }
                });
            });
			$("#batch").change(function(){
				console.log('maua');
				event.preventDefault();
				jQuery.noConflict();
				var batchId = $("#batch").val();
				console.log('batchID',batchId);
				$('#currentStock').val($("#batch option[value='"+batchId+"']").attr('data-avilableQty'));
				console.log($('#currentStock').val());
			});
			$('#physicalStock').keyup(function(){
				event.preventDefault();
				jQuery.noConflict();
				// $("#factor opttion").remove();
				var physicalStock = $('#physicalStock').val(),
					currentStock = $('#currentStock').val(),
					_token=$('input[name="_token"]').val(),
					factorType;
					if(currentStock > physicalStock){
						factorType=2;
					}
					if(physicalStock > currentStock){
						factorType=1;
					}
					$.ajax({
					url:"{{ route('adjustment-factors') }}",
                    method:'POST',
                    data:{
						factorType:factorType,
						_token:_token
					},
					dataType:'JSON',
                    success:function(response){
						// alert_toast(JSON.parse(response))
						console.log(response);
                        var len = response.length;
						$("#factor").empty();
						$("#factor").append("<option value=''>select Factor</option> ");
                        for( var i = 0; i<len; i++){
                            var factorId = response[i]['adjustment_factor_id'];
                            var name = response[i]['name'];
							var description=response[i]['description'];
							var uuid = response[i]['uuid'];
                            $("#factor").append("<option value='"+factorId+"' data-uuid='"+uuid+"' data-description='"+description+"' data-name='"+name+"'>"+name+"</option>");
                        }
                    }
                });
			});
		});
	</script>

	
@endpush
