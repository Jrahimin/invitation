@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.invitations.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.invitations.store']]) !!}

    <div class="panel panel-info">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('event_id', trans('global.invitations.fields.event').'', ['class' => 'control-label']) !!}
                    {!! Form::select('event_id', $events, old('event_id'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('event_id'))
                        <p class="help-block">
                            {{ $errors->first('event_id') }}
                        </p>
                    @endif
                </div>
            </div>

            @include('elements.input_field', ["field" => 'name'])
            @include('elements.input_field', ["field" => 'mobile_number'])
            @include('elements.input_field', ["field" => 'email', 'type' => 'email'])
            @include('elements.input_field', ["field" => 'address', 'type' => 'textArea'])
            @include('elements.input_field', ["field" => 'relation'])
            @include('elements.input_field', ["field" => 'people_count', 'type' => 'number', 'value' => '1'])
            @include('elements.input_field', ["field" => 'remark'])
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-success pull-right']) !!}
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent
    <script src="{{ url('adminlte/js') }}/timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script>
        $('.datetime').datetimepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}",
            timeFormat: "HH:mm:ss"
        });
    </script>

@stop