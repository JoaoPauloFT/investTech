@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stop

@section('title', __('message.subsectors'))

@section('content_header')
    <div class="title mb-3">
        <h1>{{ __('message.subsectors') }}</h1>
        <p>{{ __('message.subsectors_description') }}</p>
    </div>
@stop

@section('content')
    <div class="body">
        <x-forms.table :dropdownFilter="[2]">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('message.name') }}</th>
                <th>{{ __('message.sector') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subsectors as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->name }}</td>
                    <td>{{ $s->sector->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </x-forms.table>
    </div>
@stop
