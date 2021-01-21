@extends('emails/layouts/default')

@section('content')
<div style="width: 100%;">
	<h3>Project Detail</h3>

	<div style="width: 100%; overflow: hidden; border: 1px solid gray;">
	    <div style="width: 50%; float: left; padding: 1%;"> 
	    	<label>Project Id</label> : <span>{{$detail['project']['id']}}</span> 
	    </div>
	    <div style="margin-left: 50%; padding: 1%;"> 
	    	<label>Project Name</label> : <span>{{$detail['project']['project_name']}}</span> 
	    </div>

	    <div style="width: 50%; float: left; padding: 1%;"> 
	    	<label>Client Name</label> : <span>{{$detail['client']['name']}}</span> 
	    </div>
	    <div style="margin-left: 50%; padding: 1%;"> 
	    	<label>Location</label> : <span>{{$detail['location']['name']}}</span> 
	    </div>

	    <div style="width: 50%; float: left; padding: 1%;"> 
	    	<label>PO Date</label> : <span>{{$detail['project']['po_date']}}</span> 
	    </div>
	    <div style="margin-left: 50%; padding: 1%;"> 
	    	<label>PO Number</label> : <span>{{$detail['project']['po_wo_no']}}</span>
	    </div>

	    <div style="width: 50%; float: left; padding: 1%;"> 
	    	<label>Start Date</label> : <span>{{$detail['reqdate'][0]}}</span> 
	    </div>
	    <div style="margin-left: 50%; padding: 1%;"> 
	    	<label>End Date</label> : <span>{{$detail['reqdate'][1]}}</span>
	    </div>
	</div>

	<h3>Employee Detail</h3>

	<table border="2" style="width: 60%;">
		<thead>
			<th>Name</th>
			<th>Code</th>
			<th>Start Date</th>
			<th>End Date</th>
		</thead>
		<tbody>
		@foreach($detail['user'] as $key => $value)
			<tr>
				<td>{{$value['first_name'].' '.$value['last_name']}}</td>
				<td>{{$value['employee_num']}}</td>
				<td>{{$detail['userdate'][0]}}</td>
				<td>{{$detail['userdate'][1]}}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
@stop