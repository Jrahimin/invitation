@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.events.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.events.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            @include('elements.input_field', ["module" => "events", "field" => 'name', 'required' => true])
            @include('elements.input_field', ["module" => "events", "field" => 'venue_name'])
            @include('elements.input_field', ["module" => "events", "field" => 'venue_address', 'type' => 'textArea'])
            @include('elements.input_field', ["module" => "events", "field" => 'description', 'type' => 'textArea'])
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('event_date', trans('global.events.fields.event-date').'', ['class' => 'control-label']) !!}
                    {!! Form::text('event_date', old('event_date'), ['class' => 'form-control date', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('event_date'))
                        <p class="help-block">
                            {{ $errors->first('event_date') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-success pull-right']) !!}
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent
    <script>
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });
    </script>

@stop