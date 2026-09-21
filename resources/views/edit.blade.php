@php
    $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Informasi Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg1: #eef4ff;
            --bg2: #deebff;
            --glass: rgba(255,255,255,0.52);
            --panel: rgba(255,255,255,0.72);
            --text: #1a2333;
            --muted: #59657a;
            --primary: #0a84ff;
            --primary-strong: #0069d9;
            --success: #16a34a;
            --success-strong: #0f8a3d;
            --border: rgba(157,170,196,0.26);
            --shadow: rgba(22, 36, 72, 0.18);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,0.85), transparent 30%),
                linear-gradient(135deg, var(--bg1), var(--bg2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
            color: var(--text);
            padding: 32px 16px;
        }

        .glass-shell {
            width: min(100%, 540px);
            background: linear-gradient(180deg, rgba(255,255,255,0.58), rgba(255,255,255,0.30));
            border: 1px solid rgba(255,255,255,0.60);
            backdrop-filter: blur(20px) saturate(150%);
            -webkit-backdrop-filter: blur(20px) saturate(150%);
            box-shadow: 0 20px 46px var(--shadow), inset 0 1px 0 rgba(255,255,255,0.75);
            border-radius: 32px;
            overflow: hidden;
        }

        .panel-header {
            padding: 22px 24px 18px;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .panel-header h5 {
            margin: 0;
            font-size: clamp(1.2rem, 3vw, 1.45rem);
            letter-spacing: -0.03em;
            font-weight: 700;
        }

        .panel-body {
            padding: 20px 24px 24px;
        }

        .glass-box {
            border-radius: 20px;
            background: rgba(255,255,255,0.16);
            border: 1px solid var(--border);
            padding: 18px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.45);
        }

        .step-title {
            margin: 0 0 14px;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .label {
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
            border-radius: 16px;
            padding: 14px 16px;
            background: rgba(255,255,255,0.24);
            color: var(--text);
            box-shadow: inset 0 1px 6px rgba(255,255,255,0.25), inset 0 1px 2px rgba(15, 23, 42, 0.05);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-input:focus {
            border-color: rgba(10,132,255,0.8);
            box-shadow: 0 0 0 4px rgba(10,132,255,0.10), inset 0 1px 0 rgba(255,255,255,0.35);
        }

        .pin-box {
            letter-spacing: 0.35em;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .muted-small {
            display: block;
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.74rem;
            line-height: 1.5;
        }

        .primary-btn,
        .success-btn {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 14px 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .primary-btn {
            background: linear-gradient(180deg, var(--primary), var(--primary-strong));
            color: #fff;
            box-shadow: 0 12px 24px rgba(10, 132, 255, 0.25);
        }

        .success-btn {
            background: linear-gradient(180deg, var(--success), var(--success-strong));
            color: #fff;
            box-shadow: 0 12px 24px rgba(22, 163, 74, 0.22);
        }

        .primary-btn:active,
        .success-btn:active {
            transform: scale(0.985);
        }

        .alert {
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 0.86rem;
        }

        #pin-error-msg {
            min-height: 18px;
            margin-top: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="glass-shell">
        <div class="panel-header">
            <h5>Edit Informasi Toko (ID: {{ $qrTag->id }})</h5>
        </div>
        <div class="panel-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div id="pin-verification-step" class="glass-box">
                <h6 class="step-title">Masukkan PIN Aktivasi saat ini</h6>
                <div class="mb-3">
                    <label class="label">PIN Aktivasi</label>
                    <input type="password" id="input_pin" class="glass-input pin-box" maxlength="4" placeholder="••••">
                    <div id="pin-error-msg" class="text-danger"></div>
                </div>
                <button type="button" onclick="submitVerifyPin()" class="primary-btn">Verifikasi PIN</button>
            </div>

            <div id="edit-form-step" style="display: none;">
                <form action="{{ route('qr.update', $qrTag->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="current_pin" id="hidden_current_pin">

                    <div class="mb-3">
                        <label class="label">Lokasi Toko</label>
                        <input type="text" name="place_id" class="glass-input" value="" placeholder="Masukkan Google Place ID" required>
                        <small class="muted-small">Kolom ini dibuka dalam keadaan kosong untuk diisi manual.</small>
                    </div>

                    <div class="mb-3">
                        <label class="label">Nomor WhatsApp Toko</label>
                        <input type="text" name="whatsapp_number" class="glass-input" value="{{ old('whatsapp_number', $qrTag->whatsapp_number) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="label">PIN Baru (Opsional)</label>
                        <input type="password" name="new_pin" class="glass-input pin-box" maxlength="4" placeholder="••••">
                        <small class="muted-small">Isi 4 digit jika ingin mengganti PIN lama.</small>
                    </div>

                    <button type="submit" class="success-btn">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function submitVerifyPin() {
    const pin = document.getElementById('input_pin').value;
    const errorMsg = document.getElementById('pin-error-msg');

    errorMsg.innerText = '';

    if (!pin) {
        errorMsg.innerText = 'PIN harus diisi!';
        return;
    }

    fetch("{{ route('qr.verify-pin', $qrTag->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ pin: pin })
    })
    .then(async response => {
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            console.error("Respon Server (Bukan JSON):", text);
            throw new Error("Terjadi kesalahan server (Error " + response.status + ")");
        }

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Verifikasi gagal');
        }

        return data;
    })
    .then(data => {
        if (data.success) {
            document.getElementById('hidden_current_pin').value = pin;
            document.getElementById('pin-verification-step').style.display = 'none';
            document.getElementById('edit-form-step').style.display = 'block';
        }
    })
    .catch(error => {
        errorMsg.innerText = error.message;
    });
}

@if(!empty($googleMapsApiKey))
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
@endif
</script>

</body>
</html>