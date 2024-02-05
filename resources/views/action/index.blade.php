@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stop

@section('title', __('message.actions'))

@section('content_header')
    <div class="title mb-3">
        <h1>{{ __('message.actions') }}</h1>
        <p>{{ __('message.actions_description') }}</p>
    </div>
    @can('sync_action')
        <div class="actions mb-3">
            <a id="createButton" href="{{ route('action.sync') }}">
                <i class="fa-solid fa-plus br"></i>
                {{ __('message.sync_action') }}
            </a>
        </div>
    @endcan
@stop

@section('content')
    <div class="body">
        <x-forms.table :dropdownFilter="[3,4]">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('message.ticker') }}</th>
                <th>{{ __('message.name') }}</th>
                <th>{{ __('message.sector') }}</th>
                <th>{{ __('message.subsector') }}</th>
                <th>{{ __('message.type') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($actions as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->ticker }}</td>
                    <td>{{ $a->name }}</td>
                    <td>{{ $a->subsector->sector->name }}</td>
                    <td>{{ $a->subsector->name }}</td>
                    <td>{{ $a->type }}</td>
                </tr>
            @endforeach
            </tbody>
        </x-forms.table>
    </div>
@stop
