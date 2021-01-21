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
	    	<label>Start Date</label> : <span>{{$detail['userdate'][0]}}</span> 
	    </div>
	    <div style="margin-left: 50%; padding: 1%;"> 
	    	<label>End Date</label> : <span>{{$detail['userdate'][1]}}</span>
	    </div>
	</div>

	<h3>User Detail</h3>

	<table border="2" style="width: 60%;">
		<thead>
			<th></th>
			<th>Name</th>
			<th>Phone No.</th>
		</thead>
		<tbody>
			<tr>
				<td>Project Manager</td>
				<td>{{@$detail['pm'][0]}}</td>
				<td>{{@$detail['pm'][1]}}</td>
			</tr>
			<tr>
				<td>Allotted By</td>
				<td>{{@$detail['allocate'][0]}}</td>
				<td>{{@$detail['allocate'][1]}}</td>
			</tr>
		</tbody>
	</table>
</div>
@stop