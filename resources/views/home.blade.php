@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info col-md-3" style="margin-right: 2%">
                <div class="panel-heading"><h4>Total Guests</h4></div>
                <div class="panel-body">
                    <a href="{{ route('admin.invitations.index') }}">
                        <label class="badge" style="font-size: 24px; background-color: darkblue; cursor: pointer">{{ $totalGuest }}</label>
                    </a>
                </div>
            </div>

            <div class="panel panel-info col-md-3" style="margin-right: 2%">
                <div class="panel-heading"><h4>Total Invited</h4></div>
                <div class="panel-body">
                    <a href="{{ route('admin.invitations.index') }}?show_invited=1">
                        <label class="badge" style="font-size: 24px; background-color: green; cursor: pointer">{{ $totalInvited }}</label>
                    </a>
                </div>
            </div>

            <div class="panel panel-info col-md-3" style="margin-right: 2%">
                <div class="panel-heading"><h4>Yet to Invite</h4></div>
                <div class="panel-body">
                    <a href="{{ route('admin.invitations.index') }}?show_uninvited=1">
                        <label class="badge" style="font-size: 24px; background-color: brown; cursor: pointer">{{ $totalUninvited }}</label>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
