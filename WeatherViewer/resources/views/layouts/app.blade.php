<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Weather Viewer' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --bg: #0f172a;
            --bg-soft: #111827;
            --card-bg: #020617;
            --card-border: #1f2937;
            --accent: #38bdf8;
            --accent-soft: rgba(56, 189, 248, 0.15);
            --accent-strong: #0ea5e9;
            --text: #e5e7eb;
            --text-muted: #9ca3af;
            --danger: #f97373;
            --success: #4ade80;
            --radius-xl: 16px;
            --radius-lg: 12px;
            --shadow-soft: 0 18px 40px rgba(15, 23, 42, 0.65);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #1e293b 0, #020617 55%, #000 100%);
            color: var(--text);
        }

        .app-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 16px 40px;
        }

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }

        .app-title {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .app-title h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .app-title h1::before {
            content: "☁️";
            font-size: 22px;
        }

        .app-title span {
            font-size: 13px;
            color: var(--text-muted);
        }

        .app-badge {
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(15, 23, 42, 0.8);
        }

        .app-badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--success);
            box-shadow: 0 0 0 6px rgba(74, 222, 128, 0.15);
        }

        /* ===== LAYOUT PRINCIPAL ===== */

        .layout-grid {
            display: grid;
            grid-template-columns: 1.4fr 1.1fr;
            grid-template-areas:
                "left right"
                "compare compare";
            gap: 24px;
        }

        .column-left {
            grid-area: left;
        }

        .column-right {
            grid-area: right;
        }

        .compare-full {
            grid-area: compare;
        }

        /* Espaçamento vertical entre cards dentro das colunas */
        .column-left .card + .card,
        .column-right .card + .card {
            margin-top: 24px;
        }

        @media (max-width: 768px) {
            .layout-grid {
                grid-template-columns: 1fr;
                grid-template-areas:
                    "left"
                    "right"
                    "compare";
            }

            .column-left,
            .column-right,
            .compare-full {
                width: 100%;
            }

            .app-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card {
                margin-bottom: 18px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .field-row {
                display: flex;
                flex-direction: column;
                gap: 14px;
                align-items: stretch;
            }

            .field {
                width: 100%;
                max-width: 100% !important;
            }

            .field button,
            .field input,
            .field select {
                width: 100%;
            }

            .metric-row {
                grid-template-columns: 1fr 1fr;
            }

            .compare-grid {
                display: grid;
                grid-template-columns: 1fr;  /* lista vertical */
                row-gap: 10px;
            }

            .compare-header {
                font-size: 14px;
                margin-top: 8px;
            }

            .compare-label {
                font-weight: 600;
                margin-top: 10px;
            }
        }

        .card {
            background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.12), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(56, 189, 248, 0.08), transparent 60%),
                        var(--card-bg);
            border-radius: var(--radius-xl);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-soft);
            padding: 18px 18px 16px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .card-subtitle {
            font-size: 12px;
            color: var(--text-muted);
        }

        .pill {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            color: var(--text-muted);
        }

        .card-body {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .section-label {
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .field-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .field {
            flex: 1;
            min-width: 130px;
        }

        label {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: rgba(15, 23, 42, 0.8);
            color: var(--text);
            font-size: 13px;
            outline: none;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.6);
        }

        button,
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-radius: 999px;
            border: none;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            color: #0b1220;
            box-shadow: 0 12px 25px rgba(56, 189, 248, 0.35);
            text-decoration: none;
        }

        button.btn-ghost,
        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid rgba(148, 163, 184, 0.5);
            box-shadow: none;
        }

        button:hover,
        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        button:active,
        .btn:active {
            transform: translateY(0);
            filter: brightness(0.97);
        }

        .alert-error {
            margin-bottom: 12px;
            padding: 8px 10px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(248, 113, 113, 0.6);
            background: rgba(127, 29, 29, 0.25);
            color: #fecaca;
            font-size: 12px;
        }

        .alert-success {
            margin-bottom: 12px;
            padding: 8px 10px;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(74, 222, 128, 0.6);
            background: rgba(22, 101, 52, 0.35);
            color: #bbf7d0;
            font-size: 12px;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--accent-strong);
        }

        .metric-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        @media (max-width: 960px) {
            .metric-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .metric {
            background: rgba(15, 23, 42, 0.9);
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            padding: 8px 10px;
        }

        .metric-label {
            font-size: 11px;
            color: var(--text-muted);
        }

        .metric-value {
            font-size: 16px;
            font-weight: 600;
            margin-top: 2px;
        }

        .metric-sub {
            font-size: 11px;
            color: var(--text-muted);
        }

        .history-list {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 12px;
            max-height: 210px;
            overflow-y: auto;
        }

        .history-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 6px 0;
            border-bottom: 1px solid rgba(30, 64, 175, 0.25);
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-main {
            display: flex;
            flex-direction: column;
        }

        .history-city {
            font-size: 12px;
        }

        .history-meta {
            font-size: 11px;
            color: var(--text-muted);
        }

        .compare-grid {
            display: grid;
            grid-template-columns: 130px minmax(0, 1fr) minmax(0, 1fr);
            gap: 8px;
            font-size: 12px;
        }

        @media (max-width: 900px) {
            .compare-grid {
                grid-template-columns: 1fr;
            }
        }

        .compare-header {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .compare-label {
            color: var(--text-muted);
        }

        .compare-cell {
            background: rgba(15, 23, 42, 0.9);
            border-radius: 12px;
            padding: 6px 10px;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 7px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.4);
            font-size: 11px;
            color: var(--text-muted);
        }

        .muted {
            color: var(--text-muted);
            font-size: 12px;
        }

        .section-divider {
            border-top: 1px dashed rgba(148, 163, 184, 0.35);
            margin: 8px 0;
        }

        .chip-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 6px;
        }

        .chip {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.35);
        }

    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="app-shell">
    <header class="app-header">
        <div class="app-title">
            <h1>Weather Viewer</h1>
            <span>Consulte, salve e compare previsões em diferentes localidades.</span>
        </div>
    </header>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif

    @if(session('saved'))
        <div class="alert-success">
            Previsão de hoje salva com sucesso!
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
