@extends('layouts.default')

{{-- Page title --}}
@section('title')
    @if ($item->id)
        {{ $updateText }}
    @else
        {{ $createText }}
    @endif
@parent
@stop

@section('header_right')
@if(isset($backRoute))
	<a href="{{ route($backRoute) }}" class="btn btn-primary pull-right">
    {{ trans('general.back') }}</a>
@elseif(isset($projectCost) && $projectCost != '')
    <a href="{{ url('pcost/viewCost/'.$projectCost) }}" class="btn btn-primary pull-right">
    {{ trans('general.back') }}</a>
@else
	<a href="{{ URL::previous() }}" class="btn btn-primary pull-right">
    {{ trans('general.back') }}</a>
@endif
@stop



{{-- Page content --}}

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-default">
           

            <div class="box-body">
                <form id="create-form" class="form-horizontal" method="post" action="{{ \Request::url() }}" onsubmit="return validateForm()" autocomplete="off" role="form" enctype="multipart/form-data">

                 <div class="box-header with-border" style="margin-bottom: 10px;">
                    <h3 class="box-title" style="display: inline;">
                    @if ($item->id)
                    {{ $item->display_name }}
                    @endif
                    </h3>
                    @include('partials.forms.edit.submitheader')
                     
                </div><!-- /.box-header -->

                    
                    <!-- CSRF Token -->
                    {{ csrf_field() }}
                    @yield('inputFields')
                    @include('partials.forms.edit.submit')
                </form>
            </div>
        </div>
    </div>
    <div class="slideout-menu">
        <a href="#" class="slideout-menu-toggle pull-right">×</a>
        <h3>
            {{ $helpTitle}}
        </h3>
        <p>{{ $helpText }} </p>
    </div>
</div>

@stop
