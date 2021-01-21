@extends('emails/layouts/default')

@section('content')
<div style="width: 100%;">
<p>Dear Mam/Sir,</p>
@if($type != '')
	<p>Your customer bill of {{$project->bill_date}} is not generated yet</p>
@else
	<p>Generate customer Bill for {{$project->bill_date}}</p>
@endif
<div class="row"> 
	<div class="col-md-12">
		<table class="table" border="2">
			<thead>
				<th colspan="2">Project Detail</th>
			</thead>
			<tbody>
				<tr>
					<td>Id</td>
					<td>{{$project->project_detail_id}}</td>
				</tr>
				<tr>
					<td>Customer</td>
					<td>{{$project->project->company['name']}}</td>
				</tr>
				<tr>
					<td>Project Name</td>
					<td>{{$project->project['project_name']}}</td>
				</tr>
				<tr>
					<td>Location</td>
					<td>{{$project->project->location['name']}}</td>
				</tr>
				<tr>
					<td>PO No.</td>
					<td>{{$project->project['po_wo_no']}}</td>
				</tr>
				<tr>
					<td>PO Date</td>
					<td>{{date('d-M-Y', strtotime($project->project['po_date']))}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<p>Thank you,</p>
<p>Microlink</p>
</div>
@stop