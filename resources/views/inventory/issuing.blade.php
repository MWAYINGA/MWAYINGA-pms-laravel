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
{{-- Issuing List --}}
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
								@if ($requestStatus->where('request',$request->request_id)->where('completedStatus',0)->isEmpty()){{$status="New"}}@endif
								@if (!($requestStatus->where('request',$request->request_id)->where('completedStatus',0)->isEmpty())){{$status="Issued"}}@endif
								@if (!($requestStatus->where('request',$request->request_id)->where('completedStatus',1)->isEmpty())){{$status="Completed"}}@endif
							{{-- <form action="" method="post" enctype="multipart/form-data" id="form-request{{$request->request_id}}">
							</form> --}}
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
												<form action="{{route('issuing')}}" method="post" enctype="multipart/form-data" id="request{{$request->request_id}}">
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
																	<td
																	@if ($qtyIssued<=0)
																		data-toggle="collapse" data-target="#issue{{$item->request_item_id}}" class="accordion-toggle"
																	@endif
																	>{{ ($qtyIssued > 0) ? $qtyIssued : '-'}}</td>
																	<td>
																		{{ ($qtyIssued > 0) ? $status : 'New'}}
																	</td>
																</tr>
																<tr>
																	<td colspan="9" class="hiddenRow">
																		<div class="accordian-body collapse" id="issue{{$item->request_item_id}}">
																			@if ($onHandItemBatch->where('item',$item->itemId)->isEmpty())
																				{{"No available Batches"}}
																			@else
																				<div class="row">
																					<div class="col-md-3">Batch</div>
																					<div class="col-md-3">Expire Date</div>
																					<div class="col-md-3">Available Quantity</div>
																					<div class="col-md-3">issue</div>
																				</div>
																				<hr>																				
																				@foreach ($onHandItemBatch->where('item',$item->itemId) as $batch)
																				<div class="row">
																					<div class="col-md-3">{{$batch->batchNo}}</div>
																					<div class="col-md-3">{{$batch->expireDate}}</div>
																					<div class="col-md-3">{{$batch->quantity}}</div>
																					<div class="col-md-3">
																						<input type="hidden" name="requestId" id="request{{$item->request}}" value="{{$item->request}}">
																						<input type="text" name="qtyIssued[{{$item->request_item_id}}][{{$batch->batchId}}]" value="0">
																					</div>
																				</div>
																				@endforeach

																				<div class="row">
																					<div class="submit-section">
																						<button class="btn btn-secondary submit-btn" type="submit" name="form_submit" value="Issue" id="issueB{{$item->request}}">Issue</button>
																					</div>
																				</div>
																			@endif
																		</div>
																	</td>
																</tr>
															@endif
														@endforeach
													</tbody>
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
{{-- Issuing List --}}
@endisset

<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush
