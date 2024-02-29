@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stop

@section('title', __('message.stock_indicators'))

@section('content_header')
    <div class="title mb-3">
        <h1>{{ __('message.stock_indicators') }}</h1>
        <p>{{ __('message.stock_indicators_description') }}</p>
    </div>
    <div class="actions mb-3">
        @can('sync_action_history')
            <a id="createButton" href="{{ route('indicator.sync') }}">
                <i class="fa-solid fa-plus br"></i>
                {{ __('message.get_indicators') }}
            </a>
        @endcan
        <a id="seeFilter" href="#" data-toggle="modal" data-target="#modalForm">
            <i class="fa-solid fa-plus br"></i>
            {{ __('message.see_filters') }}
        </a>
    </div>
@stop

@section('content')
    <div class="body">
        <x-forms.table :dropdownFilter="[]">
            <thead>
            <tr>
                <th>{{ __('message.action') }}</th>
                <th>{{ __('message.cotation') }}</th>
                <th>{{ __('message.pl') }}</th>
                <th>{{ __('message.pvp') }}</th>
                <th>{{ __('message.psr') }}</th>
                <th>{{ __('message.dividend_yield') }}</th>
                <th>{{ __('message.price_active') }}</th>
                <th>{{ __('message.price_working_capital') }}</th>
                <th>{{ __('message.price_ebit') }}</th>
                <th>{{ __('message.price_active_circ') }}</th>
                <th>{{ __('message.ev_ebit') }}</th>
                <th>{{ __('message.ev_ebitda') }}</th>
                <th>{{ __('message.margin_ebit') }}</th>
                <th>{{ __('message.liquid_margin') }}</th>
                <th>{{ __('message.current_liquidation') }}</th>
                <th>{{ __('message.roic') }}</th>
                <th>{{ __('message.roe') }}</th>
                <th>{{ __('message.liquidation_old_months') }}</th>
                <th>{{ __('message.net_worth') }}</th>
                <th>{{ __('message.gross_debt_patrimony') }}</th>
                <th>{{ __('message.recurring_growth') }}</th>
                <th>{{ __('message.created_by') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($actionHistories as $ah)
                <tr>
                    <td><a href="{{ route('action.detail', $ah->action->id) }}">{{ $ah->action->ticker }}</a></td>
                    <td>R$ {{ number_format($ah->cotation, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->pl, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->pvp, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->psr, 3, ',', '.') }}</td>
                    <td>{{ $ah->dividend_yield / 100 }}%</td>
                    <td>{{ number_format($ah->price_active, 3, ',', '.') }}</td>
                    <td>{{ number_format($ah->price_working_capital, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->price_ebit, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->price_active_circ, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->ev_ebit, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->ev_ebitda, 2, ',', '.') }}</td>
                    <td>{{ $ah->margin_ebit / 100 }}%</td>
                    <td>{{ $ah->liquid_margin / 100 }}%</td>
                    <td>{{ number_format($ah->current_liquidation, 2, ',', '.') }}</td>
                    <td>{{ $ah->roic / 100 }}%</td>
                    <td>{{ $ah->roe / 100 }}%</td>
                    <td>R$ {{ number_format($ah->liquidation_old_months, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($ah->net_worth, 2, ',', '.') }}</td>
                    <td>{{ number_format($ah->gross_debt_patrimony, 2, ',', '.') }}</td>
                    <td>{{ $ah->recurring_growth / 100 }}%</td>
                    <td>{{ date("d/m/Y H:i", strtotime($ah->created_at)) }}</td>
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
    {{ \App\Http\Controllers\FilterController::index() }}
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