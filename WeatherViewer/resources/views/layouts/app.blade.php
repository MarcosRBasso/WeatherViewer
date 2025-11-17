<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Weather Viewer</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
        .success { color: green; }
        table { border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ccc; padding: 4px 8px; }
        .flex { display: flex; gap: 20px; }
        .card { border: 1px solid #ddd; padding: 10px; margin-bottom: 15px; }
        label { display: block; margin-top: 5px; }
        input, select, button { margin-top: 3px; }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <h1>Weather Viewer</h1>

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif

    @if(session('saved'))
        <div class="success">Previsão de hoje salva com sucesso.</div>
    @endif

    @yield('content')
</body>
</html>
