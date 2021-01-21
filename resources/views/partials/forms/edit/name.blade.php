<!-- Name -->
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="name" class="col-md-3 control-label">{{ $translated_name }}</label>
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'name')) ? ' required' : '' }}">
        <input class="form-control" type="text" name="name" id="name" value="{{ Input::old('name', $item->name) }}" oninput=""/>
        {!! $errors->first('name', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
    </div>
    <div class="col-md-7 col-sm-11 col-md-offset-3">
        <p class="help-block">{{ @$Assettext }}</p>
    </div>
</div>