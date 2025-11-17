@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Buscar cidade por CEP</h2>
    <label>CEP</label>
    <input type="text" id="cep" placeholder="00000-000">
    <button type="button" id="btn-cep">Buscar CEP</button>
</div>

<div class="card">
    <h2>Buscar previsão por cidade</h2>
    <form method="POST" action="{{ route('weather.search') }}">
        @csrf
        <label>Cidade</label>
        <input type="text" name="city" id="city" value="{{ old('city') }}">
        <button type="submit">Buscar previsão</button>
    </form>
</div>

@if($current)
    @php $w = $current; @endphp
    <div class="card">
        <h2>Previsão atual - {{ $w['location_name'] }}</h2>
        <p><strong>Descrição:</strong> {{ $w['description'] }}</p>
        <p><strong>Temperatura:</strong> {{ $w['temperature'] }} °C</p>
        <p><strong>Sensação térmica:</strong> {{ $w['feels_like'] }} °C</p>
        <p><strong>Umidade:</strong> {{ $w['humidity'] }} %</p>
        <p><strong>Vento:</strong> {{ $w['wind_speed'] }} km/h</p>
        <p><strong>Data/hora local:</strong> {{ $w['localtime'] }}</p>

        <form method="POST" action="{{ route('weather.saveToday') }}">
            @csrf
            <input type="hidden" name="location_id" value="{{ $currentLocId }}">
            <input type="hidden" name="temperature" value="{{ $w['temperature'] }}">
            <input type="hidden" name="description" value="{{ $w['description'] }}">
            <input type="hidden" name="feels_like" value="{{ $w['feels_like'] }}">
            <input type="hidden" name="humidity" value="{{ $w['humidity'] }}">
            <input type="hidden" name="wind_speed" value="{{ $w['wind_speed'] }}">
            <button type="submit">Salvar previsão de hoje</button>
        </form>
    </div>
@endif

<div class="flex">
    <div class="card" style="flex:1;">
        <h2>Histórico recente</h2>
        @if($histories->isEmpty())
            <p>Nenhuma busca registrada.</p>
        @else
            <ul>
                @foreach($histories as $h)
                    <li>
                        {{ $h->searched_at }} -
                        {{ $h->location->city ?? '' }} ({{ $h->location->state ?? '' }})
                    </li>
                @endforeach
            </ul>
        @endif
        <a href="{{ route('weather.history') }}">Ver histórico completo</a>
    </div>

    <div class="card" style="flex:1;">
        <h2>Previsões salvas hoje</h2>
        @if($savedToday->isEmpty())
            <p>Nenhuma previsão salva hoje.</p>
        @else
            <table>
                <tr>
                    <th>Cidade</th>
                    <th>Temp</th>
                    <th>Descrição</th>
                </tr>
                @foreach($savedToday as $rec)
                    <tr>
                        <td>{{ $rec->location->city ?? '' }}</td>
                        <td>{{ $rec->temperature }} °C</td>
                        <td>{{ $rec->description }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>

<div class="card">
    <h2>Comparar duas localidades (previsão salva de hoje)</h2>
    <form method="POST" action="{{ route('weather.compare') }}">
        @csrf
        <label>Local A</label>
        <select name="location_a">
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->city }} - {{ $loc->state }}</option>
            @endforeach
        </select>

        <label>Local B</label>
        <select name="location_b">
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->city }} - {{ $loc->state }}</option>
            @endforeach
        </select>

        <button type="submit">Comparar</button>
    </form>

    @if($comparison)
        <h3>Resultado</h3>
        <table>
            <tr>
                <th></th>
                <th>Local A</th>
                <th>Local B</th>
            </tr>
            <tr>
                <td>Cidade</td>
                <td>{{ optional($comparison['left']->location ?? null)->city }}</td>
                <td>{{ optional($comparison['right']->location ?? null)->city }}</td>
            </tr>
            <tr>
                <td>Temperatura</td>
                <td>{{ $comparison['left']->temperature ?? '-' }}</td>
                <td>{{ $comparison['right']->temperature ?? '-' }}</td>
            </tr>
            <tr>
                <td>Umidade</td>
                <td>{{ $comparison['left']->humidity ?? '-' }}</td>
                <td>{{ $comparison['right']->humidity ?? '-' }}</td>
            </tr>
        </table>
    @endif
</div>

<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $('#btn-cep').on('click', function () {
        const cep = $('#cep').val();
        $.post('{{ route('weather.fillCity') }}', {cep: cep})
            .done(function (data) {
                $('#city').val(data.city);
            })
            .fail(function () {
                alert('CEP não encontrado');
            });
    });
</script>
@endsection
