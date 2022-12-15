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
	@isset($viewStores)
	<a href="{{route('add-store')}}" class="btn btn-primary float-right mt-2">Create New</a>
	@endisset
	@isset($createStores)
	<a href="{{route('stores')}}" class="btn btn-primary float-right mt-2">View Stores</a>
	@endisset
</div>

@endpush

@section('content')

@isset($viewStores)
{{-- Invoice List --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="item-list-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>No:</th>
                                <th>Store Name</th>
								<th>Descriptions</th>
                                <th>Creator</th>
                                <th>Date Created</th>
							</tr>
						</thead>
						<tbody>
                            @php
                            $index=1;
                            @endphp
                            @foreach($stores as $store)
                            <tr>
                                <td>{{$index}}</td>
                                <td>{{$store->name}}</td>
                                <td>{{$store->description}}</td>
                                <td>@foreach($users as $user)
                                    @if($user->id == $store->created_by) {{$user->name}} @endif
                                    @endforeach</td>
                                <td>{{$store->date_created}}</td>
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

@isset($createStores)
{{-- create Invoice --}}
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" id="update_service" action="{{route('add-store')}}">
					@csrf
					<div class="service-fields">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Name </label>
									<input class="form-control" type="text" name="name">
								</div>
							</div>
                            <div class="col-md-6">
								<div class="form-group">
									<label>Description </label>
									<input class="form-control" type="text" name="description">
								</div>
							</div>
						</div>
					</div>
                    <hr>
					<div class="service-fields">
						<div class="row">
                            @foreach($attributesType as $type)
							<div class="col-lg-4">
								<div class="form-group">
									<label><b>{{$type->description}}</b></label><br>
                                    {{-- <input type="hidden" name="typeId[]" value="{{$type->type_id}}"> --}}
                                    <input type="checkbox" name="attributrType[]" id="" value="{{$type->type_id}}">
                                    {{-- <input type="radio" name="IsMainStore" id="" value="yes">Yes
                                    <input type="radio" name="IsMainStore" id="" value="no">No --}}
								</div>
							</div>
                            {{-- <div class="col-lg-4">
								<div class="form-group">
									<label><b>Is Sub Store</b></label><br>
                                    <input type="radio" name="IsSubStore" id="" value="yes">Yes
                                    <input type="radio" name="IsSubStore" id="" value="no" checked>No
								</div>
							</div>
                            <div class="col-lg-4">
								<div class="form-group">
									<label><b>Is Dispensing Point</b></label><br>
                                    <input type="radio" name="IsDispensingPoint" id="" value="yes">Yes
                                    <input type="radio" name="IsDispensingPoint" id="" value="no">No
								</div>
							</div> --}}
                            @endforeach
						</div>
					</div>
					<div class="service-fields">
						<div class="row">
							<div class="submit-section">
								<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit" id="sub">Save</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- create Invoice --}}


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
@endpush
