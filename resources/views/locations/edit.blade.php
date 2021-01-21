@extends('layouts/edit-form', [
    'createText' => trans('admin/locations/table.create') ,
    'updateText' => trans('admin/locations/table.update'),
    'helpTitle' => trans('admin/locations/table.about_locations_title'),
    'helpText' => trans('admin/locations/table.about_locations')
])

{{-- Page content --}}
@section('inputFields')
@include ('partials.forms.edit.name', ['translated_name' => trans('admin/locations/table.name')])

<!-- Parent-->






@include ('partials.forms.edit.address')
  <div class="form-group {{ $errors->has('contact_person') ? ' has-error' : '' }}">
     {{ Form::label('contact_person', trans('general.contact_person_name'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'contact_person')) ? ' required' : '' }}"">
        {{Form::text('contact_person', Input::old('contact_person', $item->contact_person), array('class' => 'form-control')) }}
        {!! $errors->first('contact_person', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_number') ? ' has-error' : '' }}">
    {{ Form::label('contact_number', trans('general.contact_no'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'contact_number')) ? ' required' : '' }}"">
        {{Form::text('contact_number', Input::old('contact_number', $item->contact_number), array('class' => 'form-control')) }}
        {!! $errors->first('contact_number', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>

@stop

@if (!$item->id)
@section('moar_scripts')
<script>

$('#create-form').validate({
        rules: {
            contact_number: {
                number: true
            }, 
           
        },
        submitHandler: function (form) { // for demo
            //alert('valid form');
            //return false;
           $("form#create-form").submit();
        }
    });

function parent_details(id) 
{
	if (id) 
	{
		//start ajax request
		$.ajax({
		    type: 'GET',
		    url: "{{config('app.url') }}/api/locations/"+id+"/check",
		//force to handle it as text
		dataType: "text",
		success: function(data) {
		    var json = $.parseJSON(data);
		    $("#city").val(json.city);
		    $("#address").val(json.address);
		    $("#address2").val(json.address2);
		    $("#state").val(json.state);
		    $("#zip").val(json.zip);
		    $(".country").select2("val",json.country);
		}
		});
	} 
	else 
	{
	    $("#city").val('');
	    $("#address").val('');
	    $("#address2").val('');
	    $("#state").val('');
	    $("#zip").val('');
	    $(".country").select2("val",'');
	}
}
</script>
@stop
@endif
