@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Histórico de buscas</h2>

    @if($histories->isEmpty())
        <p>Nenhum registro.</p>
    @else
        <table>
            <tr>
                <th>Data/Hora</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Fonte</th>
            </tr>
            @foreach($histories as $h)
                <tr>
                    <td>{{ $h->searched_at }}</td>
                    <td>{{ $h->location->city ?? '' }}</td>
                    <td>{{ $h->location->state ?? '' }}</td>
                    <td>{{ $h->source }}</td>
                </tr>
            @endforeach
        </table>

        {{ $histories->links() }}
    @endif

    <p><a href="{{ route('weather.index') }}">Voltar</a></p>
</div>
@endsection
