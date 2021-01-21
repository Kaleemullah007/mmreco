@extends('emails/layouts/default')

@section('content')
<p>Dear Team,<br>
New project has been added, with below details.
</p>
<div class="row"> 
	<div class="col-md-12">
		<table class="table" border="2">
			<thead>
				<th colspan="2">Project Detail</th>
			</thead>
			<tbody>
				<tr>
					<td>Id</td>
					<td>{{@$project->id}}</td>
				</tr>
				<tr>
					<td>Customer</td>
					<td>{{@$project->company->name}}</td>
				</tr>
				<tr>
					<td>Project Name</td>
					<td>{{@$project->project_name}}</td>
				</tr>
				<tr>
					<td>Location</td>
					<td>{{@$project->location->name}}</td>
				</tr>
				<tr>
					<td>PO No.</td>
					<td>{{$project->po_wo_no}}</td>
				</tr>
				<tr>
					<td>PO Date</td>
					<td>{{date('d-M-Y', strtotime($project->po_date))}}</td>
				</tr>
				<tr>
					<td>Sales Person</td>
					<td>{{@$project->sales_executive1->fullName()}}</td>
				</tr>
				<tr>
					<td>Service Account Manager</td>
					<td>{{@$project->project_manager1->fullName()}}</td>
				</tr>
				<tr>
					<td>Technical Team Leader</td>
					<td>{{@$ttl}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@stop