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


@isset($binCardSearch)
<div class="row">
    <div class="col-lg-12">
        <form action="{{route('binCards')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Item Name<span class="text-danger">*</span></label>
                        <select class="select2 form-select form-control" id="itemName" name="itemUuid" required> 
                            <option value="">select Item</option> 
                            @foreach ($items as $item)
                                <option value="{{$item->uuid}}" data-item_id="{{$item->inv_item_id}}" data-item_name="{{$item->name}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Store</label>
                        <select class="select2 form-select form-control" id="storeName" name="storeUuid"> 
                            <option value="">select Store</option> 
                            @foreach ($stores as $store)
                                <option value="{{$store->uuid}}" data-store_id="{{$store->store_id}}" data-store_name="{{$store->name}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Month</label>
                        <input type="month" name="monthYear" id="" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="submit-section">
                        <button class="btn btn-secondary submit-btn" type="submit" name="form_submit" value="SearchBin" >Search</button>
                    </div>
                </div>
            </div>
            <hr>
        </form>
    </div>
</div> 
@endisset

@isset($binCard)
{{-- Bin Card List --}}
<div class="row">
	<div class="col-lg-12">
		<div class="row">
            <div class="col-md-3" style="float: left;">
				<img src="assets/img/1600415460_avatar2.jpg" alt="" height="90px" width="90px" srcset="" class="rounded-circle">
			</div>
			<div class="col-md-5" style="float: left;text-align:center;">
				<h2>{{env('APP_INSTITUTION')}}</h2>
				<p>{{env('APP_INSTITUTION_ADDRESS')}}</p>
				<p>Contacts:{{env('APP_INSTITUTION_CONTACTS')}}</p>
				<p><i>Email:{{env('APP_INSTITUTION_EMAIL')}}</i></p>
				
			</div>
			<div class="col-md-4 text-right" style="float: left;">
				<p><h4 style="text-decoration-line: underline; text-decoration-style: dotted;">BIN CARD</h4></p>
			</div>
		</div>
        <div class="row"></div><br>
        <div class="row">
            <div class="col-md-8">
                <span>INSTITUTION:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;">{{env('APP_INSTITUTION')}}</span>
            </div>
            <div class="col-md-4">
                <span>Item Code:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"></span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <span>Name of Item:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"></span>
            </div>
            <div class="col-md-4">
                <span>Year:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"><?=date('Y')?></span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <span>Unit of issue:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"></span>
            </div>
            <div class="col-md-2">
                <span>Strength:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"></span>
            </div>
            <div class="col-md-4">
                <span>Dosage Form:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"></span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <span>Minimum stock level:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"></span>
            </div>
            <div class="col-md-4">
                <span>Maximum stock level:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"></span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <span>Average month Consumption:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"><?="Average month Consumption"?></span>
            </div>
            <div class="col-md-4">
                <span>Re-order level:  </span><span style="text-decoration-line: underline; text-decoration-style: dotted;"><?='Re-order level'?></span>
            </div>
        </div>
        <br>
        <div class="row">
            {{-- {{$binItems}} --}}
            <table class="table table-condensed table-hover column-bordered-table thead" id="plist">
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="20%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="20%">
                </colgroup>
                <thead bgcolor="#CCC9C9">
                    <tr>
                        <th rowspan="2">Date</th>
                        <th rowspan="2">Receipt/Issue Voucher</th>
                        <th rowspan="2">Receipt from/Issued to</th>
                        <th rowspan="2">Batch No</th>
                        <th rowspan="2">Expire date</th>
                        <th colspan="3" class="text-center">Quantity</th>
                        <th rowspan="2" class="text-center">signature</th>
                    </tr>
                    <tr>
                        <th>Received</th>
                        <th>Issued</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($binItems as $item)
                        <tr>
                            <td>{{$item->date_created}}</td>
                            <td>{{$item->voucherNumber}}</td>
                            <td>{{$item->fromOrTo}}</td>
                            <td>{{$item->batchNo}}</td>
                            <td>{{$item->expireDate}}</td>
                            <td>{{$item->quantityReceived}}</td>
                            <td>{{$item->quantityIssued}}</td>
                            <td>{{$item->quantityBalance}}</td>
                            <td>{{$item->creator}}</td>
                        </tr>
                    @endforeach
                </tbody> 
            </table>
        </div>
	</div>		
</div>
{{-- Bin Card List --}}
@endisset


<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>	
@endpush
