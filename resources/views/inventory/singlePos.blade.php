@extends('layouts.app')

@push('page-css')
<!-- Select2 CSS -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<style>
    *,
    :after,
    :before {
        box-sizing: border-box;
        border: 0 solid #e2e8f0
    }

    .bg-gray-100 {
        --bg-opacity: 1;
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--bg-opacity))
    }

    .border-gray-400 {
        --border-opacity: 1;
        border-color: #cbd5e0;
        border-color: rgba(203, 213, 224, var(--border-opacity))
    }

    .border-r {
        border-right-width: 1px
    }

    .flex {
        display: flex
    }

    .pt-8 {
        padding-top: 2rem
    }

    .min-h-screen {
        min-height: 100%
    }

    .text-gray-500 {
        --text-opacity: 1;
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--text-opacity))
    }

</style>
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

{{-- Normal Sales --}}
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="update_service" action="{{route('pos-normal')}}">
                    @csrf
                    <div class="service-fields">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">Customer Name</label>
                                <input class="form-control" type="text" id="name" name="customerName">
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4 form-group">
                                <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
                                    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                                        <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                                            <div class="px-2 text-gray-500 border-r border-gray-400 tracking-wider">
                                                @if($lastTransaction->last())
                                                TODAY LATEST TN
                                                @endif </div>
                                            <div class="ml-2 text-gray-500 uppercase tracking-wider">
                                                {{ $lastTransaction->last()?->dated_sale_id }} </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type="text" name="sessionType" id="sessionType" value="{{$createNormalSales}}" hidden>
                            <input type="text" name="insuranceType" id="insuranceType" hidden>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item <span class="text-danger">*</span></label>
                                    <select class="select2 form-select form-control" id="itemName">
                                        <option value="">select Item</option>
                                        @foreach ($items as $item)
                                        <option value="{{$item->inv_item_id}}" data-item_id="{{$item->inv_item_id}}" data-item_name="{{$item->name}}" data-uoms="{{ $item->uoms }}">{{$item->name." | ".$item->uoms->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>UOM </label>
                                <select class="select2 form-select form-control" id="uom">
                                    <option value="">select Unit of Measure</option>
                                    @foreach ($units as $unit)
                                    <option value="{{$unit->unit_id}}" data-uom_id="{{$unit->unit_id}}" data-uom_name="{{$unit->name}}">{{$unit->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Qty <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="itemQty">
                        </div>
                    </div>
                    {{csrf_field()}}
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
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="4">Total</th>
                            <th class="text-right tamount"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="service-fields">
            <div class="row">
                <div class="submit-section col-sm-12 col-auto">
                    <a href="javascript:void(0)" class="btn btn-primary float-right mt-2" id="processPayment">Payments</a>
                </div>
            </div>
        </div>
        {{-- Modal for payment --}}
        <div class="modal fade" id="pay_modal" role='dialog'>
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="" class="control-label">Total Amount</label>
                                <input type="text" name="tamount" value="" class="form-control text-right" readonly="">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Amount Tendered</label>
                                <input type="number" name="amount_tendered" value="0" min="0" class="form-control text-right">
                            </div>
                            {{-- <div class="form-group">
											<label for="" class="control-label">Discount</label>
											<input type="number" name="amount_discounted" value="" min="0" class="form-control text-right" >
										</div> --}}
                            <div class="form-group">
                                <label for="" class="control-label">Change</label>
                                <input type="number" name="change" value="0" min="0" class="form-control text-right" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id='submitPayment'>Pay</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal for payment --}}
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
                <input type="hidden" name="item[]" value="">
                <p class="item"></p>
            </td>
            <td>
                <input type="number" min="1" step="any" name="qty[]" value="" class="text-right">
            </td>
            <td>
                <p class="UnitsName"></p>
                <input type="hidden" min="1" step="any" name="unit[]" value="" class="text-right">
            </td>
            <td>
                <input type="hidden" min="1" step="any" name="price[]" value="" class="text-right">
                <p class="price text-right">0</p>
            </td>
            <td>
                <input type="hidden" min="1" step="any" name="amount[]" value="" class="text-right">
                <p class="amount text-right"></p>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-danger" onclick="rem_list($(this))"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </table>
</div>
{{-- Table row to clone --}}

{{-- style --}}
<style type="text/css">
    #tr_clone {
        display: none;
    }

    td {
        vertical-align: middle !important;
        justify-content: center;
    }

    td>input {
        margin: auto;
    }

    td p {
        margin: unset;
    }

    td input {
        height: calc(100%);
        width: calc(100%);
        border: unset;

    }

    td input:focus {
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
{{-- @endisset --}}
<!-- Delete Modal -->
{{-- <x-modals.delete :route="'items'" :title="'Item List'" /> --}}
<!-- /Delete Modal -->
@endsection


@push('page-js')
<!-- Select2 JS -->
<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script>
    function rem_list(_this) {
        _this.closest('tr').remove()
        calculate_total()
    }

    function calculate_total() {
        var total = 0;
        $('#list tbody').find('.item-row').each(function() {
            var _this = $(this).closest('tr')
            var amount = parseFloat(_this.find('[name="qty[]"]').val()) * parseFloat(_this.find('[name="price[]"]').val());
            amount = amount > 0 ? amount : 0;
            _this.find('p.amount').html(parseFloat(amount).toLocaleString('en-US', {
                style: 'decimal'
                , maximumFractionDigits: 2
                , minimumFractionDigits: 2
            }))
            total += parseFloat(amount);
        })
        $('[name="tamount"]').val(total)
        $('#list .tamount').html(parseFloat(total).toLocaleString('en-US', {
            style: 'decimal'
            , maximumFractionDigits: 2
            , minimumFractionDigits: 2
        }))
    }
    $(document).ready(function() {
        // change
        $('#submitPayment').click(function() {
            if ($('[name="change"]').val() < 0) {
				Snackbar.show({
                    text: "The required amount is " + tamount + ""
                    , pos: 'top-right'
                    , actionTextColor: '#fff'
                    , backgroundColor: '#e7515a'
                });
                return false;
            } else {
                this.form.submit();
            }
        });
        $('[name="amount_tendered"]').keyup(function() {
            var tendered = $(this).val();
            var tamount = $('[name="tamount"]').val();
            $('[name="change"]').val(parseFloat(tendered) - parseFloat(tamount))
            if ($('[name="change"]').val() < 0) {
				Snackbar.show({
                    text: "The required amount is " + tamount + ""
                    , pos: 'top-right'
                    , actionTextColor: '#fff'
                    , backgroundColor: '#e7515a'
                });
                return false;
            }
        });
        $('#processPayment').click(function() {
            if ($("#list .item-row").length <= 0) {
                Snackbar.show({
                    text: "The required amount is " + tamount + ""
                    , pos: 'top-right'
                    , actionTextColor: '#fff'
                    , backgroundColor: '#e7515a'
                });
                return false;
            }
            $('#pay_modal').modal('show')
        });
        $('#add-to-list').click(function() {
            event.preventDefault();
            jQuery.noConflict();
            var tr = $('#tr_clone tr.item-row').clone();
            var item = $('#itemName').val()
                , itemQty = $('#itemQty').val()
                , insuranceType = $('#insuranceType').val()
                , _token = $('input[name="_token"]').val()
                , sessionType = $('#sessionType').val();
            var uom = JSON.parse($("#itemName option[value='" + item + "']").attr('data-uoms'));
            if ($('#list').find('tr[data-item="' + item + '"]').length > 0) {
                Snackbar.show({
                    text: "Product already on the list"
                    , pos: 'top-right'
                    , actionTextColor: '#fff'
                    , backgroundColor: '#e7515a'
                });
                return false;
            }
            if (item == '' || itemQty == '') {
                Snackbar.show({
                    text: "Please complete the fields first"
                    , pos: 'top-right'
                    , actionTextColor: '#fff'
                    , backgroundColor: '#e7515a'
                });
                return false;
            }
            $.ajax({
                type: "POST"
                , url: "{{ route('item-price-stock-available') }}"
                , method: 'POST'
                , data: {
                    itemId: item
                    , sessionType: sessionType
                    , insuranceType: insuranceType
                    , _token: _token
                }
                , success: function(resp) {
                    resp = JSON.parse(resp);
                    if (resp.available >= itemQty) {
                        if (resp.price == null) {
                            Snackbar.show({
                                text: "Price for This Item not Settled " + resp.price
                                , pos: 'top-right'
                                , actionTextColor: '#fff'
                                , backgroundColor: '#e7515a'
                            });
                            return false;
                        }
                        tr.attr('data-item', item)
                        tr.find('.item').html($("#itemName option[value='" + item + "']").attr('data-item_name'))
                        tr.find('.UnitsName').html(uom.name)
                        tr.find('.qty').html(itemQty)
                        tr.find('.price').html(resp.price)

                        tr.find('[name="price[]"]').val(resp.price)
                        var amount = parseFloat(resp.price) * parseFloat(itemQty)
                        tr.find('.amount').html(parseFloat(amount).toLocaleString('en-US', {
                            style: 'decimal'
                            , maximumFractionDigits: 2
                            , minimumFractionDigits: 2
                        }))
                        tr.find('[name="amount[]"]').val(amount)
                        tr.find('[name="item[]"]').val(item)
                        tr.find('[name="unit[]"]').val(uom.unit_id)
                        tr.find('[name="qty[]"]').val(itemQty)

                        $('#list tbody').append(tr)
                        calculate_total();
                        $('#itemName').val('').change()
                        $('#itemQty').val('')
                        $('[name="qty[]"],[name="price[]"]').keyup(function() {
                            calculate_total()
                        });
                    } else {
                        Snackbar.show({
                            text: "quantity is greater than available stock " + resp.available
                            , pos: 'top-right'
                            , actionTextColor: '#fff'
                            , backgroundColor: '#e7515a'
                        });
                        return false;
                    }
                }
                , error: function(resp) {
                    Snackbar.show({
                        text: resp.message
                        , pos: 'top-right'
                        , actionTextColor: '#fff'
                        , backgroundColor: '#e7515a'
                    });
                }
            });
        });
    });

</script>


@endpush

