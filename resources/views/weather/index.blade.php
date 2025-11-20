@extends('layouts.app', ['title' => 'Weather Viewer'])

@section('content')
<div class="layout-grid">

    {{-- BUSCA && PREVISÃO ATUAL --}}
    <div class="column-left">

        {{-- BUSCA (CEP || CIDADE) --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Busca de Localidade</div>
                    <div class="card-subtitle">Preencha pelo CEP ou informe a cidade diretamente.</div>
                </div>
                <span class="pill">Passo 1</span>
            </div>

            <div class="card-body"> 
                {{-- CEP --}}
                <div class="field-row">
                    <div class="field">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" placeholder="00000-000">
                    </div>
                    <div class="field" style="max-width: 130px;">
                        <label>&nbsp;</label>
                        <button type="button" id="btn-cep">
                            Buscar CEP
                        </button>
                    </div>
                </div>

                <div class="section-divider"></div>

                {{-- Cidade --}}
                <div class="section-label">Cidade</div>
                <form id="weatherSearchForm" method="POST" action="{{ route('weather.search') }}">
                    @csrf
                    <div class="field-row">
                        <div class="field">
                            <label for="city">Cidade</label>
                            <input
                                type="text"
                                name="city"
                                id="city"
                                value="{{ old('city') }}"
                                placeholder="Ex.: Chapecó, São Paulo, Rio de Janeiro..."
                            >
                        </div>
                        <div class="field" style="max-width: 170px;">
                            <label>&nbsp;</label>
                            <button type="submit">
                                Buscar previsão
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- PREVISÃO ATUAL && SALVAR --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Previsão atual</div>
                    <div class="card-subtitle">Previsão por Weatherstack.</div>
                </div>
                <span class="pill">Passo 2</span>
            </div>

            <div class="card-body">
                @if($current)
                    @php $w = $current; @endphp

                    <div class="field-row" style="align-items: center; gap: 16px;">
                        <div>
                            <div class="section-label">Localidade</div>
                            <div style="font-size: 18px; font-weight: 600;">
                                {{ $w['location_name'] ?? '—' }}
                            </div>
                            <div class="muted">
                                {{ $w['region'] ?? '' }} {{ $w['country'] ? '• '.$w['country'] : '' }}
                            </div>
                        </div>

                        <div class="metric" style="max-width: 130px;">
                            <div class="metric-label">Temperatura</div>
                            <div class="metric-value">{{ $w['temperature'] }} °C</div>
                            <div class="metric-sub">Sensação {{ $w['feels_like'] }} °C</div>
                        </div>
                    </div>

                    <div class="metric-row">
                        <div class="metric">
                            <div class="metric-label">Condição</div>
                            <div class="metric-value" style="font-size: 14px;">
                                {{ $w['description'] ?? '—' }}
                            </div>
                            <div class="metric-sub">Atual</div>
                        </div>
                        <div class="metric">
                            <div class="metric-label">Umidade</div>
                            <div class="metric-value">
                                {{ $w['humidity'] !== null ? $w['humidity'].' %' : '—' }}
                            </div>
                            <div class="metric-sub">Relativa</div>
                        </div>
                        <div class="metric">
                            <div class="metric-label">Vento</div>
                            <div class="metric-value">
                                {{ $w['wind_speed'] !== null ? $w['wind_speed'].' km/h' : '—' }}
                            </div>
                            <div class="metric-sub">Velocidade</div>
                        </div>
                        <div class="metric">
                            <div class="metric-label">Horário local</div>
                            <div class="metric-value" style="font-size: 14px;">
                                {{ $w['localtime'] ?? '—' }}
                            </div>
                            <div class="metric-sub">Zona da cidade</div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <div class="field-row" style="align-items: center; justify-content: space-between;">
                        <div class="muted">
                            Salve a previsão do dia para poder usar nos comparativos.
                        </div>
                        <form method="POST" action="{{ route('weather.saveToday') }}">
                            @csrf
                            <input type="hidden" name="location_id" value="{{ $currentLocId }}">
                            <input type="hidden" name="temperature" value="{{ $w['temperature'] }}">
                            <input type="hidden" name="description" value="{{ $w['description'] }}">
                            <input type="hidden" name="feels_like" value="{{ $w['feels_like'] }}">
                            <input type="hidden" name="humidity" value="{{ $w['humidity'] }}">
                            <input type="hidden" name="wind_speed" value="{{ $w['wind_speed'] }}">
                            <button type="submit">
                                Salvar previsão de hoje
                            </button>
                        </form>
                    </div>
                @else
                    <p class="muted">
                        Nenhuma previsão carregada. Faça uma busca por cidade para visualizar os dados atuais.
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- HISTÓRICO && SALVOS --}}
    <div class="column-right">

        {{-- HISTÓRICO RECENTE --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Histórico de pesquisas</div>
                    <div class="card-subtitle">Últimas cidades consultadas.</div>
                </div>
                <a class="btn-ghost btn" href="{{ route('weather.history') }}">
                    Ver tudo
                </a>
            </div>

            <div class="card-body">
                @if($histories->isEmpty())
                    <p class="muted">Ainda não há buscas registradas.</p>
                @else
                    <ul class="history-list">
                        @foreach($histories as $h)
                            <li class="history-item">
                                <div class="history-main">
                                    <span class="history-city">
                                        {{ $h->location->city ?? '—' }}
                                        @if($h->location?->state)
                                            <span class="muted">• {{ $h->location->state }}</span>
                                        @endif
                                    </span>
                                    <span class="history-meta">
                                        {{ $h->searched_at }} • {{ $h->source }}
                                    </span>
                                </div>
                                <span class="badge-dot"></span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- PREVISÕES SALVAS --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Previsões salvas hoje</div>
                    <div class="card-subtitle">Registros persistidos para comparação.</div>
                </div>
                <span class="pill">{{ $savedToday->count() }} registro(s)</span>
            </div>

            <div class="card-body">
                @if($savedToday->isEmpty())
                    <p class="muted">Nenhuma previsão salva hoje. Busque uma cidade e clique em “Salvar previsão de hoje”.</p>
                @else
                    <div class="chip-list">
                        @foreach($savedToday as $rec)
                            <div class="chip">
                                {{ $rec->location->city ?? '—' }}
                                @if($rec->temperature !== null)
                                    • {{ $rec->temperature }} °C
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- COMPARAÇÃO --}}
    <div class="compare-full">
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Comparar localidades</div>
                    <div class="card-subtitle">Selecione duas cidades com previsão salva para comparar lado a lado.</div>
                </div>
                <span class="pill">Passo 3</span>
            </div>

            <div class="card-body">
                @php
                    $comparisonData = $comparison ?? [];
                    $selectedA = old('location_a') ?? ($comparisonData['lastA'] ?? null);
                    $selectedB = old('location_b') ?? ($comparisonData['lastB'] ?? null);
                @endphp
                <form method="POST" action="{{ route('weather.compare') }}">
                    @csrf
                    <div class="field-row">
                        <div class="field">
                            <label for="location_a">Região A</label>
                            <select name="location_a" id="location_a">
                                <option value="">Selecione...</option>

                                @foreach($locations as $loc)
                                    <option
                                        value="{{ $loc->id }}"
                                        {{ $selectedA && (string)$selectedA === (string)$loc->id ? 'selected' : '' }}
                                    >
                                        {{ $loc->city }} - {{ $loc->state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label for="location_b">Região B</label>
                            <select name="location_b" id="location_b">
                                <option value="">Selecione...</option>

                            @foreach($locations as $loc)
                                <option
                                    value="{{ $loc->id }}"
                                    {{ $selectedB && (string)$selectedB === (string)$loc->id ? 'selected' : '' }}
                                >
                                    {{ $loc->city }} - {{ $loc->state }}
                                </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="field" style="max-width: 140px;">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn">
                                Comparar
                            </button>
                        </div>
                    </div>
                </form>

                <div class="section-divider"></div>

                @if($comparison)
                    <div class="compare-grid">
                        <div></div>
                        <div class="compare-header">
                            {{ optional($comparison['left']->location ?? null)->city ?? '—' }} 
                            -  {{ optional($comparison['left']->location ?? null)->state ?? '' }}
                        </div>
                        <div class="compare-header">
                            {{ optional($comparison['right']->location ?? null)->city ?? '—' }} 
                            - {{ optional($comparison['left']->location ?? null)->state ?? '' }}
                        </div>

                        {{-- Linha: Cidade --}}
                        <div class="compare-label">Cidade</div>
                        <div class="compare-cell">
                            {{ optional($comparison['left']->location ?? null)->city ?? '—' }}
                        </div>
                        <div class="compare-cell">
                            {{ optional($comparison['right']->location ?? null)->city ?? '—' }}
                        </div>

                        {{-- Linha: Temperatura --}}
                        <div class="compare-label">Temperatura</div>
                        <div class="compare-cell">
                            {{ $comparison['left']->temperature ?? '—' }} @if($comparison['left']) °C @endif
                        </div>
                        <div class="compare-cell">
                            {{ $comparison['right']->temperature ?? '—' }} @if($comparison['right']) °C @endif
                        </div>

                        {{-- Linha: Sensação --}}
                        <div class="compare-label">Sensação térmica</div>
                        <div class="compare-cell">
                            {{ $comparison['left']->feels_like ?? '—' }} @if($comparison['left']) °C @endif
                        </div>
                        <div class="compare-cell">
                            {{ $comparison['right']->feels_like ?? '—' }} @if($comparison['right']) °C @endif
                        </div>

                        {{-- Linha: Umidade --}}
                        <div class="compare-label">Umidade</div>
                        <div class="compare-cell">
                            {{ $comparison['left']->humidity ?? '—' }} @if($comparison['left']) % @endif
                        </div>
                        <div class="compare-cell">
                            {{ $comparison['right']->humidity ?? '—' }} @if($comparison['right']) % @endif
                        </div>

                        {{-- Linha: Vento --}}
                        <div class="compare-label">Vento</div>
                        <div class="compare-cell">
                            {{ $comparison['left']->wind_speed ?? '—' }} @if($comparison['left']) km/h @endif
                        </div>
                        <div class="compare-cell">
                            {{ $comparison['right']->wind_speed ?? '—' }} @if($comparison['right']) km/h @endif
                        </div>
                    </div>
                @else
                    <p class="muted">
                        Nenhuma comparação realizada ainda. Escolha duas localidades e clique em “Comparar”.
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>

<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
    });

    $('#btn-cep').on('click', function () {
        const cep = $('#cep').val();

        if (!cep) {
            alert('Informe um CEP.');
            return;
        }

        $.post('{{ route('weather.fillCity') }}', { cep })
            .done(function (data) {
                $('#city').val(data.city);
                document.getElementById('weatherSearchForm').submit();
            })
            .fail(function () {
                alert('CEP não encontrado ou inválido.');
            });
    });
</script>

@endsection
