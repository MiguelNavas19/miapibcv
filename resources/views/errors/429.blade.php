<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Too Many Requests</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Source+Sans+3:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg-page: #f5f4f0;
            --bg-card: #ffffff;
            --border-subtle: #e8e6e1;
            --accent-gold: #b8956e;
            --accent-gold-light: #d4c4a8;
            --text-primary: #2a2a2a;
            --text-secondary: #6b6b6b;
            --text-muted: #9a9a9a;
            --shadow-soft: 0 2px 12px rgba(0, 0, 0, 0.04);
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }

        .error-container {
            text-align: center;
            max-width: 420px;
        }

        .error-code {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(80px, 20vw, 140px);
            font-weight: 500;
            color: var(--accent-gold);
            line-height: 1;
            margin-bottom: 8px;
            opacity: 0;
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 28px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 12px;
            opacity: 0;
            animation: fadeIn 0.6s ease 0.1s forwards;
        }

        .description {
            color: var(--text-secondary);
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 32px;
            opacity: 0;
            animation: fadeIn 0.6s ease 0.2s forwards;
        }

        .countdown-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-soft);
            opacity: 0;
            animation: fadeIn 0.6s ease 0.3s forwards;
        }

        .countdown-label {
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .countdown-value {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 48px;
            font-weight: 500;
            color: var(--accent-gold);
            line-height: 1;
        }

        .countdown-value span {
            font-size: 18px;
            color: var(--text-muted);
            font-family: 'Source Sans 3', sans-serif;
            font-weight: 400;
        }

        .retry-btn {
            display: inline-block;
            background: var(--accent-gold);
            color: #fff;
            border: none;
            padding: 14px 32px;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 0.6s ease 0.4s forwards;
            text-decoration: none;
        }

        .retry-btn:hover {
            background: var(--accent-gold-light);
            transform: translateY(-1px);
        }

        .retry-btn:disabled {
            background: var(--border-subtle);
            color: var(--text-muted);
            cursor: not-allowed;
            transform: none;
        }

        .retry-btn:not(:disabled) {
            animation: fadeIn 0.6s ease 0.4s forwards, pulse 2s ease-in-out infinite 1s;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(184, 149, 110, 0.4);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(184, 149, 110, 0);
            }
        }

        .loading-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        .retry-btn.loading .loading-spinner {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hint {
            margin-top: 16px;
            font-size: 12px;
            color: var(--text-muted);
            opacity: 0;
            animation: fadeIn 0.6s ease 0.5s forwards;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <h1>Demasiadas solicitudes</h1>
        <p class="description">
            Has excedido el límite de peticiones por minuto.<br>
            Por favor, espera un momento e intenta de nuevo.
        </p>

        <div class="countdown-card">
            <div class="countdown-label">Reintentando en</div>
            <div class="countdown-value">
                <span id="countdown">60</span><span>s</span>
            </div>
        </div>

        <button id="retryBtn" class="retry-btn" disabled onclick="location.reload()">
            <span class="loading-spinner"></span>
            Reintentar ahora
        </button>
    </div>

    <script>
        (function() {
            const countdownEl = document.getElementById('countdown');
            const retryBtn = document.getElementById('retryBtn');
            let seconds = 60;

            const interval = setInterval(() => {
                seconds--;
                countdownEl.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(interval);
                    retryBtn.disabled = false;
                    retryBtn.classList.remove('loading');
                }
            }, 1000);
        })();
    </script>
</body>

</html>
