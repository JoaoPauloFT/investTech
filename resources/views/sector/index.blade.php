@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stop

@section('title', __('message.sectors'))

@section('content_header')
    <div class="title mb-3">
        <h1>{{ __('message.sectors') }}</h1>
        <p>{{ __('message.sectors_description') }}</p>
    </div>
    @can('sync_sector')
        <div class="actions mb-3">
            <a id="createButton" href="{{ route('sector.sync') }}">
                <i class="fa-solid fa-plus br"></i>
                {{ __('message.sync_sector') }}
            </a>
        </div>
    @endcan
@stop

@section('content')
    <div class="body">
        <x-forms.table :dropdownFilter="[]">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('message.name') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sectors as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </x-forms.table>
    </div>
@stop
