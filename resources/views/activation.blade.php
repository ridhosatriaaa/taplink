@php
    $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Papan Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg1: #eef3ff;
            --bg2: #dfe8fb;
            --panel: rgba(255, 255, 255, 0.5);
            --panel-strong: rgba(255, 255, 255, 0.72);
            --line: rgba(255,255,255,0.72);
            --text: #1d2433;
            --muted: #5f6778;
            --primary: #0a84ff;
            --primary-strong: #0066d6;
            --shadow: rgba(28, 45, 79, 0.18);
            --danger: #d92d20;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,0.9), transparent 35%),
                radial-gradient(circle at bottom right, rgba(138,170,255,0.28), transparent 30%),
                linear-gradient(135deg, var(--bg1), var(--bg2));
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
            color: var(--text);
        }

        .glass-card {
            max-width: 420px;
            width: min(100%, 420px);
            background: linear-gradient(180deg, rgba(255,255,255,0.52), rgba(255,255,255,0.30));
            border: 1px solid var(--line);
            box-shadow: 0 18px 42px var(--shadow), inset 0 1px 0 rgba(255,255,255,0.70);
            border-radius: 30px;
            padding: 22px 20px 18px;
            backdrop-filter: blur(22px) saturate(150%);
            -webkit-backdrop-filter: blur(22px) saturate(150%);
        }

        .google-mark {
            display: block;
            margin: 0 auto 10px;
            width: 50px;
            height: 50px;
        }

        .title {
            text-align: center;
            font-size: clamp(1.8rem, 5vw, 2.45rem);
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.05em;
            margin: 0 0 12px;
            color: var(--text);
        }

        .board-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            min-width: 170px;
            padding: 10px 16px;
            border-radius: 14px;
            background: rgba(148,163,184,0.16);
            border: 1px solid rgba(148,163,184,0.20);
            color: var(--text);
            font-weight: 700;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.55);
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.10em;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .glass-input {
            width: 100%;
            border: 1px solid rgba(148,163,184,0.28);
            background: rgba(255,255,255,0.23);
            border-radius: 16px;
            padding: 15px 16px;
            color: var(--text);
            font-size: 1rem;
            outline: none;
            box-shadow: inset 0 1px 6px rgba(255,255,255,0.25), inset 0 1px 2px rgba(15, 23, 42, 0.05);
            transition: all 0.2s ease;
        }

        .glass-input::placeholder {
            color: rgba(95,103,120,0.8);
        }

        .glass-input:focus {
            border-color: rgba(10,132,255,0.7);
            background: rgba(255,255,255,0.4);
            box-shadow: 0 0 0 4px rgba(10,132,255,0.10), inset 0 1px 0 rgba(255,255,255,0.35);
        }

        .pin-input {
            text-align: center;
            font-size: 1.2rem;
            letter-spacing: 0.45em;
            font-weight: 700;
        }

        .helper {
            margin-top: 8px;
            font-size: 0.72rem;
            line-height: 1.5;
            color: rgba(95,103,120,0.9);
        }

        .primary-btn {
            width: 100%;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(180deg, var(--primary), var(--primary-strong));
            color: #fff;
            padding: 15px 18px;
            font-weight: 800;
            font-size: 1.04rem;
            box-shadow: 0 12px 26px rgba(0, 102, 214, 0.24);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .primary-btn:active {
            transform: scale(0.985);
        }

        .alert {
            border-radius: 14px;
            font-size: 0.8rem;
        }

        @media (max-width: 480px) {
            .glass-card {
                padding: 18px 16px 16px;
                border-radius: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="glass-card text-center">
            <svg class="google-mark" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>

            <h4 class="title">Aktivasi Perangkat</h4>
            <div class="board-pill">Papan ID: {{ $qrTag->id }}</div>

            @if($errors->any())
                <div class="alert alert-danger text-start p-2 small mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('qr.activate', $qrTag->id) }}" method="POST" class="text-start">
                @csrf

                <div class="field">
                    <label>Lokasi Toko</label>
                    <input type="text" name="place_id" class="glass-input" placeholder="Masukkan Google Place ID secara manual" value="" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                </div>

                <div class="field">
                    <label>WhatsApp Toko</label>
                    <input type="tel" name="whatsapp_number" class="glass-input" placeholder="Contoh: 628123456789" value="{{ old('whatsapp_number') }}" required autocomplete="off">
                </div>

                <div class="field">
                    <label>Buat PIN 4 Digit</label>
                    <input type="password" name="activation_pin" maxlength="4" pattern="[0-9]*" inputmode="numeric" class="glass-input pin-input" placeholder="••••" required autocomplete="off">
                    <div class="helper">Simpan baik-baik PIN ini, digunakan untuk edit/reset data bisnis di kemudian hari.</div>
                </div>

                <button type="submit" class="primary-btn mt-2">Aktifkan Kartu</button>
            </form>
        </div>
    </div>

    @if(!empty($googleMapsApiKey))
        <script>
            function initPlaceAutocomplete() {
                const input = document.getElementById('place-search');
                const hiddenInput = document.getElementById('place_id');

                if (!input || !hiddenInput || !window.google || !google.maps || !google.maps.places) {
                    return;
                }

                const autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['establishment', 'geocode'],
                    fields: ['place_id', 'name', 'formatted_address']
                });

                autocomplete.addListener('place_changed', function () {
                    const place = autocomplete.getPlace();

                    if (!place || !place.place_id) {
                        return;
                    }

                    hiddenInput.value = place.place_id;
                    input.value = place.name || place.formatted_address || input.value;
                });
            }

            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key={{ urlencode($googleMapsApiKey) }}&libraries=places&callback=initPlaceAutocomplete';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        </script>
    @endif

</body>
</html>