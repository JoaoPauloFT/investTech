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
            <button type="button" id="syncStocks">
                <i class="fa-solid fa-plus br"></i>
                {{ __('message.sync_action') }}
            </button>
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
    <div class="modal js-loading-bar">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div>
                        <p>{{ __('message.update_stocks') }} <span id="progress-status">(0/1000)</span></p>
                    </div>
                    <div class="progress progress-popup">
                        <div class="progress-bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

<script>
    window.addEventListener('load', function () {
        // Setup
        $('.js-loading-bar').modal({
            backdrop: 'static',
            show: false
        });
        
        var $modal = $('.js-loading-bar'),
            $bar = $modal.find('.progress-bar');

        $('#syncStocks').click(function() {

            $.ajax('{{ route('action.list_sync') }}', {
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    let actions = response.data;
                    let qtdActions = response.qtdActions;
                    let actionsOk = 0;

                    $('#progress-status').text('(' + actionsOk + '/' + qtdActions + ')');
                    $modal.modal('show');

                    actions.forEach(element => {
                        $.ajax('{{ route('action.get_action') }}', {
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                action: element
                            }),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                actionsOk++;
                                updateStatus(actionsOk, qtdActions);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log("Erro ao importar a ação: " + element);
                                $.ajax(this);
                                return;
                            }
                        });
                    });
                    
                }
            });
        });

        function updateStatus(actionsOk, qtdActions) {
            $('#progress-status').text('(' + actionsOk + '/' + qtdActions + ')');
            let percent = actionsOk / qtdActions * 100;
            $bar.css('width', percent + '%');

            if (actionsOk == qtdActions) {
                actionsOk = 0;
                $('#progress-status').text('(' + actionsOk + '/' + qtdActions + ')');
                $modal.modal('hide');
            }
        }
    });
</script>