@extends('emails/layouts/default')

@section('content')
<div style="width: 100%;">
<p>Dear Mam/Sir,</p>

<div class="row"> 
	<div class="col-md-12">
		<table border="2" style="width: 60%;">
			<thead>
				<th>Project</th>
				<th>Item Name</th>
				<th>MSPL Invoice No</th>
				<th>Shipped Qty</th>
				<th>Amount</th>
			</thead>
			<tbody>
				@foreach($detail as $key => $value)
				<tr>
					<td>{{$value['project_id']}}" - "{{$value['project_name']}}</td>
					<td>{{@$value['item_name']}}</td>
					<td>{{@$value['mspl_invoice_no']}}</td>
					<td>{{@$value['ra_qty']}}</td>
					<td>{{@$value['amount']}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<p>Thank you,</p>
<p>Microlink</p>
</div>
@stop