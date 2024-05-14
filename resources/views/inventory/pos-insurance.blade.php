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
	@isset($createNormalSales)
	<a href="{{route('pos-insurance')}}" class="btn btn-primary float-right mt-2">Insurance</a>
	@endisset
	@isset($createInsuranceSales)
	<a href="{{route('pos-normal')}}" class="btn btn-primary float-right mt-2">Normal Sales</a>
	@endisset
</div>

@endpush

@section('content')

@isset($createInsuranceSales)
{{-- Insurance Sales --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="update_service" action="{{route('pos-insurance')}}">
					@csrf
                    <div class="service-fields">
                        <input type="text" name="sessionType" id="" value="{{$createInsuranceSales}}" hidden>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Insurance Type </label>
									<select class="select2 form-select form-control" id="insuranceType" name="insuranceType">
										<option value="">select Insurance</option> 
										@foreach ($units as $unit)
											<option value="{{$unit->unit_id}}" data-uom_id="{{$unit->unit_id}}" data-uom_name="{{$unit->name}}">{{$unit->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Folio Number <span class="text-danger">*</span></label>
									<input class="form-control" type="text" id="folioNumber" name="folioNumber">
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
									<col width="30%">
									<col width="10%">
									<col width="20%">
									<col width="10%">
									<col width="20%">
									<col width="10%">
								</colgroup>
								<thead>
									<tr>
										<th class="text-center">Product</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Dosage Form</th>
										<th class="text-center">Price</th>
										<th class="text-center">Amount</th>
										<th class="text-center"></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="submit-section col-sm-12 col-auto">
								<button class="btn btn-primary submit-btn float-right" type="submit" name="form_submit" value="submit" id="processRequest">Process Request</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- Insurance Sales --}}
@endisset

@isset($createNormalSales)
{{-- Normal Sales --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="update_service" action="{{route('pos-normal')}}">
					@csrf
					<div class="service-fields">
						<div class="row">
                            <input type="text" name="sessionType" id="" value="{{$createNormalSales}}" hidden>
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
									<col width="30%">
									<col width="10%">
									<col width="20%">
									<col width="10%">
									<col width="20%">
									<col width="10%">
								</colgroup>
								<thead>
									<tr>
										<th class="text-center">Product</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Dosage Form</th>
										<th class="text-center">Price</th>
										<th class="text-center">Amount</th>
										<th class="text-center"></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="submit-section col-sm-12 col-auto">
								<button class="btn btn-primary submit-btn float-right" type="submit" name="form_submit" value="submit" id="processRequest">Process Request</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- Normal Sales --}}

{{-- Table row to clone --}}
<div id="tr_clone">
	<table>
        <tr class="item-row">
            <td>
                <input type="hidden" name="inv_id[]" value="">
                <input type="hidden" name="product_id[]" value="">
                <input type="number" name="prescription_id[]" value="" hidden>
                <p class="item"></p>   
            </td>
            <td>
                <input type="number" min="1" step="any" name="qty[]" value="" class="text-right">
            </td>
            <td>
                <p class="UnitsName"></p>
                <input type="hidden" min="1" step="any" name="units[]" value="" class="text-right">
            </td>
            <td>
                <input type="hidden" min="1" step="any" name="price[]" value="" class="text-right" readonly="">
                <p class="price text-right">0</p>
            </td>
            <td>
                <p class="amount text-right"></p>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-danger" onclick = "rem_list($(this))"><i class="fa fa-trash"></i></button>
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
