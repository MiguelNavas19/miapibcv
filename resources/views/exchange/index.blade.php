<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasas del Dólar | Venezuela</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Source+Sans+3:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-page: #f5f4f0;
            --bg-card: #ffffff;
            --border-subtle: #e8e6e1;
            --accent-gold: #b8956e;
            --accent-gold-light: #d4c4a8;
            --accent-sage: #7c8c7c;
            --text-primary: #2a2a2a;
            --text-secondary: #6b6b6b;
            --text-muted: #9a9a9a;
            --shadow-soft: 0 2px 12px rgba(0, 0, 0, 0.04);
            --shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Source Sans 3', -apple-system, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            padding: 80px 24px;
        }

        header {
            text-align: center;
            margin-bottom: 48px;
        }

        .eyebrow {
            display: inline-block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent-gold);
            margin-bottom: 12px;
        }

        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(36px, 7vw, 52px);
            font-weight: 500;
            letter-spacing: -0.5px;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 15px;
            color: var(--text-secondary);
            font-weight: 300;
        }

        .rate-limit-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 40px;
            box-shadow: var(--shadow-soft);
        }

        .rate-limit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .rate-limit-label {
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
        }

        .rate-limit-count {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .rate-limit-bar {
            height: 4px;
            background: var(--border-subtle);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .rate-limit-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent-sage));
            border-radius: 2px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .rate-limit-fill.warning {
            background: #e8a87c;
        }

        .rate-limit-fill.danger {
            background: #c97c7c;
        }

        .rate-limit-footer {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .rate-limit-footer svg {
            width: 14px;
            height: 14px;
            animation: spin 1s linear infinite;
            display: none;
        }

        .rate-limit-footer.active svg {
            display: inline-block;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .rates-grid {
            display: grid;
            gap: 16px;
        }

        .rate-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeSlideUp 0.5s ease forwards;
        }

        .rate-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-gold-light);
        }

        .rate-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .rate-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .rate-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .rate-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .rate-card-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .bank-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--bg-page), #f0efe9);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .bank-info h3 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .bank-info p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .rate-card-right {
            text-align: right;
        }

        .rate-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
            line-height: 1.2;
        }

        .rate-unit {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rate-date {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .empty-state {
            text-align: center;
            padding: 64px 24px;
            background: var(--bg-card);
            border: 1px dashed var(--border-subtle);
            border-radius: 12px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 14px;
        }

        footer {
            text-align: center;
            margin-top: 56px;
            padding-top: 24px;
            border-top: 1px solid var(--border-subtle);
        }

        footer p {
            font-size: 12px;
            color: var(--text-muted);
        }

        @media (max-width: 480px) {
            .container {
                padding: 48px 16px;
            }

            header {
                margin-bottom: 32px;
            }

            .rate-card {
                padding: 20px;
            }

            .bank-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .rate-value {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>


    <main class="container">
        <header>
            <span class="eyebrow">Venezuela</span>
            <h1>Tasas del Dólar</h1>
            <p class="subtitle">{{ now()->translatedFormat('d F Y') }}</p>
        </header>

        @if ($rates)
            <div class="rates-grid">
                @foreach ($rates as $key => $rate)
                    <div class="rate-card">
                        <div class="rate-card-left">
                            <div class="bank-icon">
                                @switch($key)
                                    @case('bcv')
                                        🏦
                                    @break

                                    @case('bnc')
                                        💳
                                    @break

                                    @case('bdv')
                                        💵
                                    @break

                                    @case('banplus')
                                        🏧
                                    @break

                                    @default
                                        📊
                                    @break
                                @endswitch
                            </div>
                            <div class="bank-info">
                                <h3>{{ strtoupper($key) }}</h3>
                                <p>
                                    @switch($key)
                                        @case('bcv')
                                            Banco Central de Venezuela
                                        @break

                                        @case('bnc')
                                            Banco Nacional de Crédito
                                        @break

                                        @case('bdv')
                                            Banco de Venezuela
                                        @break

                                        @case('banplus')
                                            Banplus Banco Universal
                                        @break

                                        @default
                                            {{ $key }}
                                        @break
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        <div class="rate-card-right">
                            <div class="rate-value">{{ number_format($rate['value'], 2) }}</div>
                            <div class="rate-unit">Bs por USD</div>
                            <div class="rate-date">{{ $rate['date'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>No hay tasas disponibles aún.</p>
            </div>
        @endif

        <footer>
            <p>Datos de BCV, BNC, BDV y Banplus</p>
        </footer>
    </main>

</body>

</html>
