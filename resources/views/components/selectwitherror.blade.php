<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}" style="width:200px;">
    @if($label)
        {!! Form::label($name, $label, ['class' => 'control-label']) !!}
    @endif
    {!! Form::select($name, $list, $selected, $extra) !!}
    {!! $errors->first($name, '<small class="help-block">:message</small>') !!}
</div>