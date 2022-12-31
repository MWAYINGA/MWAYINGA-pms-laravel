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

<div class="row">
    <div class="col-lg-12">
        <form action="{{route($route)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Start Date<span class="text-danger">*</span></label>
                        <input type="date" name="startDate" id="" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="endDate" id="" class="form-control" required>
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

@isset($creatorCollections)
{{-- Creator Collection --}}
<div class="row">
	<div class="col-lg-12">
        <div class="row">
            {{-- {{$binItems}} --}}
            <table class="datatable table table-striped table-bordered table-hover table-center mb-0" id="plist">
                <colgroup>
                    <col width="30%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                </colgroup>
                <thead bgcolor="#CCC9C9">
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>@ Price</th>
                        <th>Amount</th>
                        <th>Payment Menthods</th>
                        <th>Creator</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderQuoteLines as $item)
                        <tr>
                            <td>{{$item->itemName}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{($item->payable_amount)/($item->quantity)}}</td>
                            <td>{{$item->paid_amount}}</td>
                            <td>{{$item->paymentMethods}}</td>
                            <td>{{Auth::user()->name}}</td>
                        </tr>
                    @endforeach
                </tbody> 
            </table>
        </div>
	</div>		
</div>
{{-- Creator Collections --}}
@endisset
@isset($totalSalesCollectionPerTransactions)
    {{-- Total Collections per Transactions --}}
@php
    $orderJson=json_decode($orderJson)
@endphp
<div class="row">
	<div class="col-lg-12">
        <div class="row">
            {{-- {{($orderJson)}} --}}
            <table class="table table-striped table-bordered table-hover table-center mb-0" id="plist">
                <colgroup>
                    <col width="30%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                </colgroup>
                <thead bgcolor="#CCC9C9">
                    <tr>
                        <th>Sales Transaction Number</th>
                        <th>Customer Name</th>
                        <th>Payment Menthods</th>
                        <th>Total Amount</th>
                        <th>Creator</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderJson as $order)
                        <tr>
                            <td>{{$order->datedSaleId}}</td>
                            <td>{{$order->customer}}</td>
                            <td>{{$order->paymentMethods}}</td>
                            <td>{{$order->paidAmount}}</td>
                            <td>{{$order->createdBy}}</td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <table>
                                    <thead>
                                        <tr>
                                            <th colspan="3">Item Name</th>
                                            <th>Quantity</th>
                                            <th>@ price</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderLines as $item)
                                            <tr>
                                                <td colspan="3">{{$item->itemName}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{($item->payable_amount)/($item->quantity)}}</td>
                                                <td>{{$item->paid_amount}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody> 
            </table>
        </div>
	</div>		
</div>
{{-- Total Collections per Transactions --}}
@endisset

@isset($totalSalesCollectionByCollectors)
{{-- Collector Collection --}}
<div class="row">
	<div class="col-lg-12">
        <div class="row">
            {{-- {{$binItems}} --}}
            <table class="datatable table table-striped table-bordered table-hover table-center mb-0" id="plist">
                <colgroup>
                    <col width="50%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead bgcolor="#CCC9C9">
                    <tr>
                        <th>Collections</th>
                        <th>CASH</th>
                        <th>INSURANCE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collectorsCollections as $creator)
                        <tr>
                            <td>{{$creator->Collectors}}</td>
                            <td>{{$creator->CASH}}</td>
                            <td>{{$creator->INSURANCE}}</td>
                        </tr>
                    @endforeach
                </tbody> 
            </table>
        </div>
	</div>		
</div>
{{-- Collector Collections --}} 
@endisset

@isset($todayCollections)
{{-- Today Collection --}}
<div class="row">
	<div class="col-lg-12">
        <div class="row">
            {{-- {{$binItems}} --}}
            <table class="datatable table table-striped table-bordered table-hover table-center mb-0" id="plist">
                <colgroup>
                    <col width="50%">
                    <col width="20%">
                    <col width="10%">
                    <col width="20%">
                </colgroup>
                <thead bgcolor="#CCC9C9">
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>@ price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections->salesLines as $line)
                        <tr>
                            <td>{{$line->itemName}}</td>
                            <td>{{$item->line}}</td>
                            <td>{{($line->payable_amount)/($line->quantity)}}</td>
                            <td>{{$line->paid_amount}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
	</div>		
</div>
{{-- Today Collections --}}     
@endisset

@endsection



@push('page-js')
<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>	
@endpush
