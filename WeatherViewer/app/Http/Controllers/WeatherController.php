<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\WeatherRecord;
use App\Models\SearchHistory;
use App\Presenters\WeatherPresenter;
use App\Services\ViaCepService;
use App\Services\WeatherstackService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct(
        private ViaCepService $viaCep,
        private WeatherstackService $weatherstack,
        private WeatherPresenter $presenter
    ) {}

    public function index()
    {
        $histories = SearchHistory::with('location')
            ->orderByDesc('searched_at')
            ->limit(10)
            ->get();

        $savedToday = WeatherRecord::with('location')
            ->whereDate('date', Carbon::today())
            ->get();

        $locations = Location::orderBy('city')->get();

        return view('weather.index', [
            'histories'     => $histories,
            'savedToday'    => $savedToday,
            'locations'     => $locations,
            'current'       => session('currentWeather'),
            'currentLocId'  => session('currentLocationId'),
            'comparison'    => session('comparison'),
        ]);
    }

    public function fillCityByCep(Request $request)
    {
        $request->validate(['cep' => 'required']);

        $data = $this->viaCep->getAddressByCep($request->cep);

        if (!$data) {
            return response()->json(['error' => 'CEP não encontrado'], 404);
        }

        return response()->json([
            'city'  => $data['localidade'] ?? '',
            'state' => $data['uf'] ?? '',
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
        ]);

        $apiData = $this->weatherstack->getCurrentWeather($request->city);

        if (!$apiData) {
            return back()->withErrors('Não foi possível obter a previsão para essa cidade.');
        }

        $formatted = $this->presenter->formatCurrent($apiData);

        $location = Location::firstOrCreate([
            'city'    => $formatted['location_name'],
            'state'   => $formatted['region'],
            'country' => $formatted['country'] ?? 'Brazil',
        ]);

        SearchHistory::create([
            'location_id'     => $location->id,
            'searched_at'     => now(),
            'source'          => 'weatherstack',
            'result_snapshot' => $formatted,
        ]);

        return back()->with([
            'currentWeather'    => $formatted,
            'currentLocationId' => $location->id,
        ]);
    }

    public function saveToday(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'temperature' => 'required|numeric',
            'description' => 'nullable|string',
            'feels_like'  => 'nullable|numeric',
            'humidity'    => 'nullable|integer',
            'wind_speed'  => 'nullable|numeric',
        ]);

        WeatherRecord::create([
            'location_id' => $request->location_id,
            'date'        => Carbon::today(),
            'temperature' => $request->temperature,
            'description' => $request->description,
            'feels_like'  => $request->feels_like,
            'humidity'    => $request->humidity,
            'wind_speed'  => $request->wind_speed,
            'raw_response'=> $request->all(),
        ]);

        return back()->with('saved', true);
    }

    public function history()
    {
        $histories = SearchHistory::with('location')
            ->orderByDesc('searched_at')
            ->paginate(20);

        return view('weather.history', compact('histories'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'location_a' => 'required|integer|exists:locations,id',
            'location_b' => 'required|integer|exists:locations,id|different:location_a',
        ]);

        $lastA = $request->location_a;
        $lastB = $request->location_b;

        $locA = Location::findOrFail($lastA);
        $locB = Location::findOrFail($lastB);

        // Busca previsão SALVA HOJE para cada local
        $weatherA = WeatherRecord::with('location')
            ->where('location_id', $locA->id)
            ->whereDate('date', Carbon::today())
            ->latest('created_at')
            ->first();

        $weatherB = WeatherRecord::with('location')
            ->where('location_id', $locB->id)
            ->whereDate('date', Carbon::today())
            ->latest('created_at')
            ->first();

        return back()->with('comparison', [
            'left'   => $weatherA,
            'right'  => $weatherB,
            'lastA'  => $lastA,
            'lastB'  => $lastB,
        ]);
    }

}
