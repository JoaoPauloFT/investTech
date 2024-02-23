@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detailAction.css') }}">
@stop

@section('title', __('message.actions'))

@section('content_header')
    <div class="title mb-3">
        <h1>{{ $a->name }} - {{ $a->ticker }}</h1>
        <p>{{ $a->subsector->name }} - {{ $a->subsector->sector->name }}</p>
    </div>
@stop

@section('content')
    <div class="content-detail">
        <div class="cards">
            <div>
                <p class="title">{{ __('message.cotation') }}</p>
                <p class="value">R$ {{ number_format($ai->cotation, 2, ',', '.') }}</p>
            </div>
            <div>
                <p class="title">{{ __('message.dividend_yield') }}</p>
                <p class="value">{{ $ai->dividend_yield / 100 }}%</p>
            </div>
            <div>
                <p class="title">{{ __('message.price_profit') }}</p>
                <p class="value">{{ number_format($ai->pl, 2, ',', '.') }}</p>
            </div>
            <div>
                <p class="title">{{ __('message.price_heritage_value') }}</p>
                <p class="value">{{ number_format($ai->pvp, 2, ',', '.') }}</p>
            </div>
            <div>
                <p class="title">{{ __('message.valorization') }}</p>
                <p class="value">
                    {{ number_format($valorization, 2, ',', '.') }}%
                    @if ($valorization < 0)
                        <i class="fa-solid fa-caret-down" style="color: #D0181E;"></i>
                    @elseif ($valorization > 0)
                        <i class="fa-solid fa-caret-up" style="color: #2E7D32;"></i>
                    @endif
                </p>
            </div>
        </div>
        <div class="cards">
            <div>
                <h3>{{ __('message.cotation') }}</h3>
                <canvas id="cotationWeekChart" style="position: relative; height:100%; width:100%"></canvas>
            </div>
            <div>
                <h3>{{ __('message.cotation_week') }}</h3>
                <canvas id="cotationChart" style="position: relative; height:100%; width:100%"></canvas>
            </div>
        </div>
        <div class="body">
            <div class="title m-3">
                <h1>{{ __('message.indicator_history') }}</h1>
            </div>
            <x-forms.table :dropdownFilter="[]" options='"columnDefs": [{ "type": "numeric-comma", "targets": 3 }],'>
                <thead>
                <tr>
                    <th>{{ __('message.cotation') }}</th>
                    <th>{{ __('message.pl') }}</th>
                    <th>{{ __('message.pvp') }}</th>
                    <th>{{ __('message.psr') }}</th>
                    <th>{{ __('message.div_yield') }}</th>
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
                @foreach($ha as $ah)
                    <tr>
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
                        <td>R$ {{ number_format($ah->liquidation_old_months, 0, ',', '.') }}</td>
                        <td>R$ {{ number_format($ah->net_worth, 0, ',', '.') }}</td>
                        <td>{{ number_format($ah->gross_debt_patrimony, 2, ',', '.') }}</td>
                        <td>{{ $ah->recurring_growth / 100 }}%</td>
                        <td>{{ date("d/m/Y H:i", strtotime($ah->created_at)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </x-forms.table>
        </div>
    </div>
    <script>
        window.onload = function (){
            const ctxWeek = document.getElementById('cotationWeekChart');

            const dataWeek = {
                labels: [
                    @foreach($cotations['date'] as $date)
                        {!! "'".$date."'," !!}
                    @endforeach
                ],
                datasets: [
                    {
                        label: '{{ __('message.cotation') }}',
                        data: [
                            @foreach($cotations['cotation'] as $cotation)
                                {!! $cotation."," !!}
                            @endforeach
                        ],
                        backgroundColor: '#3c6e71',
                        borderColor: '#3c6e71',
                        lineTension: 0.4,  
                        order: 1
                    }
                ]
            };

            const cotationWeekChart = new Chart (ctxWeek, {
                type: 'line',
                data: dataWeek,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#7E7E7E',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        title: {
                            display: false,
                        }
                    },
                    elements: {
                        point:{
                            radius: 0
                        }
                    },
                    scales: {
                        x: {
                            circular: true,
                            ticks: {
                                color: '#7E7E7E',
                                font: {
                                    size: 12,
                                }
                            }
                        },
                        y: {
                            ticks: {
                                color: '#7E7E7E',
                                font: {
                                    size: 12,
                                }
                            }
                        }
                    }
                },
            });

            const ctx = document.getElementById('cotationChart');

            const data = {
                labels: [
                    @for($i = (count($cotations['date'])-5); $i < count($cotations['date']); $i++)
                        {!! "'".$cotations['date'][$i]."'," !!}
                    @endfor
                ],
                datasets: [
                    {
                        label: '{{ __('message.cotation') }}',
                        data: [
                            @for($i = (count($cotations['cotation'])-5); $i < count($cotations['cotation']); $i++)
                                {!! "'".$cotations['cotation'][$i]."'," !!}
                            @endfor
                        ],
                        backgroundColor: '#3c6e71',
                        borderColor: '#3c6e71',
                        lineTension: 0.4,  
                        order: 1
                    }
                ]
            };

            const cotationChart = new Chart (ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#7E7E7E',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        title: {
                            display: false,
                        }
                    },
                    elements: {
                        point:{
                            radius: 0
                        }
                    },
                    scales: {
                        x: {
                            circular: true,
                            ticks: {
                                color: '#7E7E7E',
                                font: {
                                    size: 12,
                                }
                            }
                        },
                        y: {
                            ticks: {
                                color: '#7E7E7E',
                                font: {
                                    size: 12,
                                }
                            }
                        }
                    }
                },
            });
        }
    </script>
@stop