@extends('emails/layouts/default')

@section('content')
<p>Dear Team,<br>
Survey Qty updated for below line item.
</p>
<div class="row"> 
	<div class="col-md-12">
		<table class="table" border="2">
			<thead>
				<th >Item Name</th>
				<th >Old Survey Qty</th>
				<th >Survey Qty</th>
			</thead>
			<tbody>
				<tr>
					<td>{{@$material->item_name}}</td>
					<td>{{@$material->oldSurveyQty}}</td>
					<td>{{@$material->qty_per_survey}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@stop