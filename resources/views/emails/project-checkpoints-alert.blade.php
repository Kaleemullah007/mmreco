@extends('emails/layouts/default')

@section('content')
<p>{{ trans('mail.hello') }} {{ @$project['name'] }},</p>

<table>
	<tr> <td>Project :- </td> <td> {{ @$project['projectId'] }} - {{ @$project['projectName'] }}</td> </tr>
	<tr> <td>PO No :- </td> <td>{{ @$project['projectPoNo'] }}</td></tr>
	<tr> <td>PO Date :- </td> <td>{{ @$project['projectPoDate'] }}</td></tr>
</table>

<p>{{ @$project['message'] }} </p>


<p>{{ trans('mail.best_regards') }}</p>

<p>{{ $snipeSettings->site_name }}</p>
@stop
