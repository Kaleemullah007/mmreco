


<div class="form-group {{ $errors->has('contact_person_name') ? ' has-error' : '' }}">
     {{ Form::label('contact_person_name', trans('general.contact_person_name'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'contact_person_name')) ? ' required' : '' }}"">
        {{Form::text('contact_person_name', Input::old('contact_person_name', $item->contact_person_name), array('class' => 'form-control')) }}
        {!! $errors->first('contact_person_name', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
    {{ Form::label('contact_no', trans('general.contact_email'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'email')) ? ' required' : '' }}"">
        {{Form::text('email', Input::old('email', $item->email), array('class' => 'form-control')) }}
        {!! $errors->first('email', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_no') ? ' has-error' : '' }}">
    {{ Form::label('contact_no', trans('general.contact_no'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'contact_no')) ? ' required' : '' }}"">
        {{Form::text('contact_no', Input::old('contact_no', $item->contact_no), array('class' => 'form-control')) }}
        {!! $errors->first('contact_no', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('office_address') ? ' has-error' : '' }}">
    {{ Form::label('office_address', trans('general.office_address'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'office_address')) ? ' required' : '' }}"">
        {{Form::textarea('office_address', Input::old('office_address', $item->office_address), array('class' => 'form-control', 'size' => '30x3')) }}
        {!! $errors->first('office_address', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
    {{ Form::label('city', trans('general.city'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'city')) ? ' required' : '' }}"">
    {{Form::text('city', Input::old('city', $item->city), array('class' => 'form-control')) }}
        {!! $errors->first('city', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
    {{ Form::label('state', trans('general.state'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'state')) ? ' required' : '' }}"">
    {{Form::text('state', Input::old('state', $item->state), array('class' => 'form-control')) }}
        {!! $errors->first('state', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('zipcode') ? ' has-error' : '' }}">
    {{ Form::label('zipcode', trans('general.zip'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'zipcode')) ? ' required' : '' }}"">
    {{Form::text('zipcode', Input::old('zipcode', $item->zipcode), array('class' => 'form-control')) }}
        {!! $errors->first('zipcode', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('vat') ? ' has-error' : '' }}">
    {{ Form::label('vat', trans('admin/clients/table.vat'), array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'vat')) ? ' required' : '' }}"">
        {{Form::text('vat', Input::old('vat', $item->vat), array('class' => 'form-control')) }}
        {!! $errors->first('vat', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>
