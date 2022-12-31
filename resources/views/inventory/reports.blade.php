@extends('layouts.app')

@push('page-css')
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title"> {{$title}}</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item active"></li>
		{{-- <li>{{Illuminate\Support\Facades\Session::get('storeUuid')}}</li> --}}
		{{-- <li>{{url()->current()}}</li> --}}
		{{-- <li>{{dd(Session::all())}}</li> --}}
	</ul>
</div>
@endpush

@section('content')
	@php
        $reportJson=json_decode($reportJson);
    @endphp
    {{-- {{$reportJson}} --}}
	<div class="row">
        @foreach ($reportJson as $report)
            {{-- {{$report->reportsId}} --}}
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-info">
                            <a href="{{route($report->route)}}">
                                <h6 class="text-muted"><i class="{{$report->class}}"></i>{{$report->name}}</h6>
                            </a>  
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
	</div>

	<div class="row">
		<div class="col-md-12">
		
			<!-- Latest Customers -->
			
			<!-- /Latest Customers -->
			
		</div>
	</div>
@endsection

@push('page-js')
@endpush

