<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasas del Dólar | Venezuela</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Source+Sans+3:wght@300;400;500;600&family=Montserrat+Alternates:wght@600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-page: #f5f4f0;
            --bg-card: #fff;
            --border-subtle: #e8e6e1;
            --accent-gold: #b8956e;
            --accent-gold-light: #d4c4a8;
            --accent-sage: #7c8c7c;
            --text-primary: #2a2a2a;
            --text-secondary: #6b6b6b;
            --text-muted: #9a9a9a;
            --shadow-soft: 0 2px 12px rgba(0, 0, 0, 0.04);
            --shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.08);
            --brand-highlight: linear-gradient(102deg, #c5ae87 10%, #e7d8ba 74%);
        }

        html,
        body {
            background: var(--bg-page);
            min-height: 100vh;
            font-family: 'Source Sans 3', sans-serif;
        }

        body {
            color: var(--text-primary);
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 38px 8px 32px 8px;
        }

        header.hero-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-bottom: 20px;
        }

        .dollar-logo {
            width: 82px;
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        /* SVG special style for dollar arc */
        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.35rem;
            font-weight: 700;
            color: #392e19;
            letter-spacing: -1.1px;
            line-height: 1.14;
            display: flex;
            align-items: baseline;
            gap: 12px;
        }

        .badge-ve {
            font-family: 'Montserrat Alternates', sans-serif;
            font-size: 0.97rem;
            background: var(--brand-highlight);
            color: var(--accent-gold);
            border-radius: 5.5px;
            padding: 0.5px 6.5px;
            font-weight: 700;
            letter-spacing: 2px;
            box-shadow: 0 2px 8px 0 rgba(200, 180, 120, .08);
            margin-left: 7px;
        }

        .subheader-row {
            color: var(--accent-sage);
            font-family: 'Montserrat Alternates', sans-serif;
            font-weight: 600;
            font-size: 14.2px;
            margin: 12px 0 2px 0;
            display: flex;
            gap: 16px;
            align-items: center;
            justify-content: center;
        }

        .live-clock {
            font-size: 20px;
            font-family: 'Montserrat Alternates', sans-serif;
            letter-spacing: 0.14em;
            color: var(--text-secondary);
            font-weight: 700;
        }

        .rates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(218px, 1fr));
            gap: 28px;
            margin-top: 18px;
            margin-bottom: 12px;
        }

        .rate-card {
            background: linear-gradient(112deg, #fffbe9 60%, #f4eee3 100%);
            border: 1.2px solid var(--accent-gold-light);
            border-radius: 19px;
            position: relative;
            box-shadow: var(--shadow-soft);
            padding: 28px 18px 16px 18px;
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 178px;
            transition: box-shadow 0.25s, border 0.22s, transform 0.22s;
            cursor: pointer;
            overflow: hidden;
        }

        .rate-card:after {
            content: '';
            position: absolute;
            bottom: -13px;
            right: -13px;
            width: 58px;
            height: 58px;
            background: radial-gradient(ellipse at center, #e1cdb1 26%, transparent 74%);
            opacity: .14;
            z-index: 1;
            pointer-events: none;
        }

        .rate-card:hover {
            box-shadow: 0 10px 30px rgba(185, 149, 110, 0.16);
            border-color: var(--accent-gold);
            transform: translateY(-3px) scale(1.02);
        }

        .rc-flex {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
            margin-bottom: 14px;
        }

        .bank-icon {
            width: 39px;
            height: 39px;
            background: var(--bg-card);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 1px solid var(--accent-gold-light);
        }

        .bank-info h3 {
            font-family: 'Montserrat Alternates', 'Playfair Display', Georgia, serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #3a3935;
            margin-bottom: 2px;
            margin-top: 0;
        }

        .bank-info p {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
        }

        .rc-val {
            margin-left: auto;
            text-align: right;
        }

        .rate-value {
            font-size: 26.5px;
            font-weight: 800;
            color: var(--accent-gold);
            font-family: 'Montserrat Alternates', sans-serif;
            letter-spacing: -2px;
            line-height: 1.06;
        }

        .rate-unit {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.1px;
        }

        .rate-date {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 56px 20px;
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
            padding: 10px;
            border: 1.3px solid var(--border-subtle);
            background: var(--bg-card);
            border-radius: 19px 19px 19px 19px;
            box-shadow: var(--shadow-soft);
        }



        @media (max-width:900px) {
            .rates-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:600px) {
            .container {
                padding: 19px 2px 12px;
            }

            .rates-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <header class="hero-block">
            <div class="brand-title">Tasas del Dólar <span class="badge-ve">VE</span></div>
            <div class="subheader-row">
                <span id="mainDate">{{ now()->translatedFormat('d F Y') }}</span><span class="live-clock"
                    id="mainHour"></span>
            </div>
        </header>
        <script>
            // Reloj digital en vivo
            function updateTime() {
                const d = new Date();
                const locale = 'es-VE';
                let opts = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('mainHour').textContent = d.toLocaleTimeString(locale, opts);
            }
            setInterval(updateTime, 1000);
            window.addEventListener('DOMContentLoaded', updateTime);
        </script>
        @if ($rates)
            <div class="rates-grid">
                @foreach ($rates as $key => $rate)
                    <div class="rate-card">
                        <div class="rc-flex">
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
                            <div class="rc-val">
                                <div class="rate-value">{{ number_format($rate['value'], 2) }}</div>
                                <div class="rate-unit">Bs por USD</div>
                                <div class="rate-date">{{ $rate['date'] }}</div>
                            </div>
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
            <p style="font-size:13px; color:var(--text-muted);line-height:1.4;">&copy; {{ date('Y') }}
            </p>
        </footer>
    </main>
</body>

</html>
