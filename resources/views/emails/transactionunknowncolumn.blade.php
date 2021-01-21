@extends('emails/layouts/default')

@section('content')
<p>{{ trans('mail.hello') }} ,</p>

<p>Please find attachment for Bank Transaction UnKnownColumn. </p>

<p>{{ trans('mail.best_regards') }}</p>

<p>MMReco</p>
@stop
