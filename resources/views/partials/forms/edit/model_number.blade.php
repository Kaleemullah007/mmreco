<!-- Model Number -->
<div class="form-group {{ $errors->has('model_number') ? ' has-error' : '' }}">
    <label for="model_number" class="col-md-3 control-label">{{ trans('admin/models/table.modelnumber') }}</label>
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'model_number')) ? ' required' : '' }}">
    <textarea class="col-md-6 form-control" id="model_number" name="model_number">{{ Input::old('model_number', $item->model_number) }}</textarea>
        {!! $errors->first('model_number', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
</div>