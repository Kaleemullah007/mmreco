 <div class="row">
    <div class="col-md-6">
       <div class="form-group">
             <div class="col-md-3 text-right"><b>Project ID</b></div>
              <div class="col-md-7 col-sm-12">
                <span>{{ $item->project_detail_id }}</span>
             </div>
        </div>
    </div>
    <div class="col-md-6">
       <div class="form-group">
             <div class="col-md-3 text-right"><b>Project Name</b></div>
              <div class="col-md-7 col-sm-12">
                <span>{{ $project->project_name }}</span>
             </div>
        </div>
    </div>
    </div>
    <input type="hidden" name="ptype" value="{{ @$ptype }}">
 <div class="row">
     <div class="col-md-6">
        <div class="form-group {{ $errors->has('track_date') ? ' has-error' : '' }}">
           <label for="track_date" class="col-md-3 control-label">{{ trans('admin/projectcosts/form.track_date') }}</label>
           <div class="input-group col-md-9 {{  (\App\Helpers\Helper::checkIfRequired($item, 'companies_id')) ? ' required' : '' }}">
                <div class="input-group">
                    <input type="text" class="datepicker form-control" style="margin-top:0px;" data-date-format="dd-mm-yyyy" placeholder="{{ trans('general.select_date') }}" name="track_date" id="track_date" value="{{ Input::old('track_date', ($item->track_date)?date('d-m-Y',strtotime($item->track_date)):'') }}">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
               </div>
               {!! $errors->first('track_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('companies_id') ? ' has-error' : '' }}">
           <div class="col-md-3 control-label">{{ Form::label('company_id', trans('admin/projectcosts/form.client')) }}</div>
            <div class="col-md-8 col-sm-1o2{{  (\App\Helpers\Helper::checkIfRequired($item, 'companies_id')) ? ' required' : '' }}">
               {{ Form::select('companies_id',  $company_list, Input::old('companies_id', $item->companies_id), array('class'=>'select2 companies_id', 'style'=>'width:100%')) }}
               {!! $errors->first('companies_id', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('project_detail_id') ? ' has-error' : '' }}">
           <div class="col-md-3 control-label">{{ Form::label('project_detail_id', trans('admin/projectcosts/form.project')) }}</div>
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'project_detail_id')) ? ' required' : '' }}">
               {{ Form::select('project_detail_id',$project_list, Input::old('project_detail_id', $item->project_detail_id), array('class'=>'select2 project_detail_id', 'style'=>'width:100%')) }}
               {!! $errors->first('project_detail_id', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('location_id') ? ' has-error' : '' }}">
           <div class="col-md-3 control-label">{{ Form::label('location_id', trans('admin/projectcosts/form.location')) }}</div>
            <div class="col-md-8 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'location_id')) ? ' required' : '' }}">
               {{ Form::select('location_id',$location_list, Input::old('location_id', $item->location_id), array('class'=>'select2 location_id', 'style'=>'width:100%')) }}
               {!! $errors->first('location_id', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
           <div class="col-md-3 control-label">{{ Form::label('employee_id', trans('admin/projectcosts/form.employee')) }}</div>
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'employee_id')) ? ' required' : '' }}">
               {{ Form::select('employee_id', $employee_list, Input::old('employee_id', $item->name_of_res), array('class'=>'select2 employee_id', 'style'=>'width:100%')) }}
               {!! $errors->first('employee_id', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('emp_code') ? ' has-error' : '' }}">
             {{ Form::label('emp_code', trans('admin/projectcosts/form.employee_code'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'emp_code')) ? ' required' : '' }}"">
                {{Form::text('emp_code', Input::old('emp_code', $item->emp_code), array('class' => 'form-control emp_code','readonly'=>'true')) }}
                {!! $errors->first('emp_code', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
           <div class="col-md-3 control-label"><b>Salary Percent</b></div>
            <div class="col-md-9 col-sm-12">
                <input type="number" class="salary_percent form-control" style="margin-top:0px;"  placeholder="Salary Percent" name="salary_percent" id="salary_percent" value="{{ Input::old('salary_percent', $item->salary_percent) }}" onblur="salaryPercentCal();">

               {!! $errors->first('salary_percent', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('presence') ? ' has-error' : '' }}">
           <div class="col-md-3 control-label">{{ Form::label('presence', trans('admin/projectcosts/form.presence')) }}</div>
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'presence')) ? ' required' : '' }}">

                {{ Form::select('presence', $presence, Input::old('presence', $item->presence), array('class'=>'select2 presence', 'style'=>'width:100%','onchange'=>'checkPresence();')) }}
               {!! $errors->first('presence', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('food') ? ' has-error' : '' }}">
           <div class="col-md-3 control-label">{{ Form::label('food', trans('admin/projectcosts/form.food')) }}</div>
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'food')) ? ' required' : '' }}">
               {{ Form::select('food', $foodArray, Input::old('food', $item->food), array('class'=>'select2 food', 'style'=>'width:100%')) }}
               {!! $errors->first('food', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('food_P_D') ? ' has-error' : '' }}">
            {{ Form::label('food_P_D', trans('admin/projectcosts/form.food_per_day'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'food_P_D')) ? ' required' : '' }}"">
                {{Form::text('food_P_D', Input::old('food_P_D', $item->food_P_D), array('class' => 'form-control food_P_D','readonly'=>'true')) }}
                {!! $errors->first('food_P_D', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('petrol_cost') ? ' has-error' : '' }}">
            {{ Form::label('petrol_cost', trans('admin/projectcosts/form.petrol_cost'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'petrol_cost')) ? ' required' : '' }}"">
                {{Form::text('petrol_cost', Input::old('petrol_cost', $item->petrol_cost), array('class' => 'form-control petrol_cost','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('petrol_cost', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('sal_per_day') ? ' has-error' : '' }}">
            {{ Form::label('sal_per_day', trans('admin/projectcosts/form.salary'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'sal_per_day')) ? ' required' : '' }}"">
                {{Form::text('sal_per_day', Input::old('sal_per_day', $item->sal_per_day), array('class' => 'form-control sal_per_day','readonly'=>'true')) }}
                {!! $errors->first('sal_per_day', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
     <div class="col-md-6">
        <div class="form-group {{ $errors->has('over_time') ? ' has-error' : '' }}">
           <div class="col-md-3 control-label">{{ Form::label('over_time', trans('admin/projectcosts/form.overtime')) }}</div>
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'over_time')) ? ' required' : '' }}">

                {{ Form::select('over_time', $OtArray, Input::old('over_time', $item->over_time), array('class'=>'select2 over_time', 'style'=>'width:100%')) }}
               {!! $errors->first('over_time', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ot') ? ' has-error' : '' }}">
            {{ Form::label('ot', trans('admin/projectcosts/form.overtime_exp'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'ot')) ? ' required' : '' }}"">
                {{Form::text('ot', Input::old('ot', $item->ot), array('class' => 'form-control overtime_exp','onkeypress'=>'return allownumeric(event,this);')) }}
                {!! $errors->first('ot', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('material_cost') ? ' has-error' : '' }}">
            {{ Form::label('material_cost', trans('admin/projectcosts/form.material_cost'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'material_cost')) ? ' required' : '' }}"">
                {{Form::text('material_cost', Input::old('material_cost', $item->material_cost), array('class' => 'form-control material_cost','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('material_cost', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('travelling_exp') ? ' has-error' : '' }}">
            {{ Form::label('travelling_exp', trans('admin/projectcosts/form.travelling_cost'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'travelling_exp')) ? ' required' : '' }}"">
                {{Form::text('travelling_exp', Input::old('travelling_exp', $item->travelling_exp), array('class' => 'form-control travelling_exp','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('travelling_exp', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('phone_exp') ? ' has-error' : '' }}">
            {{ Form::label('phone_exp', trans('admin/projectcosts/form.phone_exp'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'phone_exp')) ? ' required' : '' }}"">
                {{Form::text('phone_exp', Input::old('phone_exp', $item->phone_exp), array('class' => 'form-control phone_exp','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('phone_exp', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('hotel_exp') ? ' has-error' : '' }}">
            {{ Form::label('hotel_exp', trans('admin/projectcosts/form.hotel_exp'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'hotel_exp')) ? ' required' : '' }}"">
                {{Form::text('hotel_exp', Input::old('hotel_exp', $item->hotel_exp), array('class' => 'form-control hotel_exp','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('hotel_exp', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('transport_exp') ? ' has-error' : '' }}">
            {{ Form::label('transport_exp', trans('admin/projectcosts/form.transport_exp'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'transport_exp')) ? ' required' : '' }}"">
                {{Form::text('transport_exp', Input::old('transport_exp', $item->transport_exp), array('class' => 'form-control transport_exp','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('transport_exp', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('contra_bill') ? ' has-error' : '' }}">
            {{ Form::label('contra_bill', trans('admin/projectcosts/form.contra_bill'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'contra_bill')) ? ' required' : '' }}"">
                {{Form::text('contra_bill', Input::old('contra_bill', $item->contra_bill), array('class' => 'form-control contra_bill','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('contra_bill', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('other_exp') ? ' has-error' : '' }}">
            {{ Form::label('other_exp', trans('admin/projectcosts/form.other_exp'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'other_exp')) ? ' required' : '' }}"">
                {{Form::text('other_exp', Input::old('other_exp', $item->other_exp), array('class' => 'form-control other_exp','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('other_exp', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('total_exp') ? ' has-error' : '' }}">
            {{ Form::label('total_exp', trans('admin/projectcosts/form.total_exp'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12">
               
                 {{ Form::label('','0.00', array('class' => 'col-md-3 control-label total_exp')) }}
                {!! $errors->first('total_exp', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group  {{ $errors->has('remarks') ? ' has-error' : '' }}">
            {{ Form::label('remarks', trans('admin/projectcosts/form.remarks'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'remarks')) ? ' required' : '' }}"">
                
                 {{Form::textarea('remarks', Input::old('remarks', $item->remarks), array('class' => 'form-control','rows'=>'5')) }}
                {!! $errors->first('Remarks', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('other_remarks') ? ' has-error' : '' }}">
            {{ Form::label('other_remarks', trans('admin/projectcosts/form.other_remarks'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'other_remarks')) ? ' required' : '' }}"">
                
                {{Form::textarea('other_remarks', Input::old('other_remarks', $item->other_remarks), array('class' => 'form-control','rows'=>'5')) }}
                {!! $errors->first('other_remarks', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('voucher_no') ? ' has-error' : '' }}">
            {{ Form::label('voucher_no', trans('admin/projectcosts/form.voucher_no'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'voucher_no')) ? ' required' : '' }}"">
                {{Form::text('voucher_no', Input::old('voucher_no', $item->voucher_no), array('class' => 'form-control voucher_no','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('voucher_no', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('site_id_ro_code') ? ' has-error' : '' }}">
            {{ Form::label('site_id_ro_code', trans('admin/projectcosts/form.site_id_ro_code'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'site_id_ro_code')) ? ' required' : '' }}"">
                {{Form::text('site_id_ro_code', Input::old('site_id_ro_code', $item->site_id_ro_code), array('class' => 'form-control site_id_ro_code','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('site_id_ro_code', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
           <label for="start_date" class="col-md-3 control-label">{{ trans('admin/projectcosts/form.start_date') }}</label>
           <div class="input-group col-md-9 {{  (\App\Helpers\Helper::checkIfRequired($item, 'start_date')) ? ' required' : '' }}">
                <div class="input-group">
                    <input type="text" class="datetimepicker form-control" style="margin-top:0px;"  placeholder="{{ trans('general.select_date') }}" name="start_date" id="start_date" value="{{ Input::old('start_date', ($item->start_date != '0000-00-00 00:00:00' && $item->start_date != '')?date('Y-m-d H:i',strtotime($item->start_date)):'') }}">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
               </div>
               {!! $errors->first('start_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('end_date') ? ' has-error' : '' }}">
           <label for="end_date" class="col-md-3 control-label">{{ trans('admin/projectcosts/form.end_date') }}</label>
           <div class="input-group col-md-9 {{  (\App\Helpers\Helper::checkIfRequired($item, 'end_date')) ? ' required' : '' }}">
                <div class="input-group">
                    <input type="text" class="datetimepicker form-control" style="margin-top:0px;"  placeholder="{{ trans('general.select_date') }}" name="end_date" id="end_date" value="{{ Input::old('end_date', ($item->end_date != '0000-00-00 00:00:00' && $item->end_date != '')?date('Y-m-d H:i',strtotime($item->end_date)):'') }}">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
               </div>
               {!! $errors->first('end_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
           </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('total_resource') ? ' has-error' : '' }}">
            {{ Form::label('total_resource', trans('admin/projectcosts/form.total_resource'), array('class' => 'col-md-3 control-label')) }}
            <div class="col-md-9 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'total_resource')) ? ' required' : '' }}"">
                {{Form::text('total_resource', Input::old('total_resource', $item->total_resource), array('class' => 'form-control total_resource','onkeypress'=>'return allownumeric(event);')) }}
                {!! $errors->first('total_resource', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
            </div>
        </div>
    </div>

    


</div>


<div class="form-group">
 </div>