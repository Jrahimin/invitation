@php
    $type = isset($type) ? $type : 'text';
    $value = $value ?? null;
    $value = old($field) ?? $value;
    $module = isset($module) ? $module : 'invitations';
@endphp
<div class="row">
    <div class="col-xs-12 form-group">
        {!! Form::label($field, trans("global.{$module}.fields.{$field}"), ['class' => 'control-label']) !!}
        {!! Form::$type($field, $value, ['class' => 'form-control', 'value' => 'xsdsdsad', 'rows'=>2, 'placeholder' => $placeholder ?? '', 'required' => isset($required)]) !!}
        <p class="help-block"></p>
        @if($errors->has($field))
            <p class="help-block">
                {{ $errors->first($field) }}
            </p>
        @endif
    </div>
</div>