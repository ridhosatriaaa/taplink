<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Ulasan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg1: #eef4ff;
            --bg2: #dfe8ff;
            --glass: rgba(255,255,255,0.52);
            --text: #151d2b;
            --muted: #5a6476;
            --primary: #0a84ff;
            --warning: #ffb703;
            --warning-strong: #f5a623;
            --success: #12b76a;
            --success-strong: #0c8d52;
            --shadow: rgba(21, 37, 72, 0.18);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,0.85), transparent 28%),
                linear-gradient(135deg, var(--bg1), var(--bg2));
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
            color: var(--text);
        }

        .glass-card {
            width: min(100%, 440px);
            padding: 26px 20px 18px;
            background: linear-gradient(180deg, rgba(255,255,255,0.56), rgba(255,255,255,0.30));
            border: 1px solid rgba(255,255,255,0.62);
            border-radius: 30px;
            box-shadow: 0 18px 42px var(--shadow), inset 0 1px 0 rgba(255,255,255,0.75);
            backdrop-filter: blur(18px) saturate(150%);
            -webkit-backdrop-filter: blur(18px) saturate(150%);
            text-align: center;
        }

        .success-icon {
            color: var(--success);
            margin-bottom: 8px;
        }

        .title {
            margin: 0 0 10px;
            font-weight: 800;
            letter-spacing: -0.05em;
            font-size: clamp(1.8rem, 5vw, 2.25rem);
        }

        .subtitle {
            margin: 0 0 16px;
            color: var(--muted);
            font-size: 0.96rem;
            letter-spacing: -0.01em;
        }

        .board-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            padding: 10px 16px;
            border-radius: 14px;
            background: rgba(148,163,184,0.15);
            border: 1px solid rgba(148,163,184,0.20);
            margin-bottom: 18px;
            font-weight: 700;
            color: var(--text);
        }

        .stars {
            display: flex;
            justify-content: center;
            flex-direction: row-reverse;
            gap: 6px;
            margin: 6px 0 20px;
        }

        .stars label {
            font-size: clamp(2.1rem, 8vw, 3rem);
            color: rgba(148,163,184,0.7);
            cursor: pointer;
            transition: transform 0.18s ease, color 0.18s ease, text-shadow 0.18s ease;
            user-select: none;
            line-height: 1;
        }

        .stars label:hover,
        .stars label:hover ~ label,
        .stars label.active {
            color: var(--warning);
            text-shadow: 0 10px 24px rgba(255,183,3,0.32);
            transform: scale(1.05);
        }

        .info-link {
            display: inline-block;
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid rgba(148,163,184,0.2);
            color: var(--muted);
            font-size: 0.83rem;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .info-link:hover {
            color: var(--primary);
        }

        .alert-success {
            border: 1px solid rgba(18,183,106,0.2);
            background: rgba(18,183,106,0.08);
            color: #0e6c3b;
            border-radius: 18px;
            padding: 14px 16px;
            text-align: left;
            font-size: 0.8rem;
            line-height: 1.6;
        }

        .alert-success strong { font-weight: 700; }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="glass-card">
            @if(request('status') == 'sukses')
                <div class="success-icon mb-2">
                    <svg width="52" height="52" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l4.992-6.344a.75.75 0 0 0-.012-1.05z"/>
                    </svg>
                </div>
                <h4 class="title text-success">Aktivasi Sukses!</h4>
                <div class="board-pill">Papan ID: {{ $qrTag->id }}</div>
                <div class="alert-success">
                    <strong>Langkah selanjutnya:</strong><br>
                    Silakan tutup halaman browser ini. Saat pelanggan memindai papan ini nanti, mereka akan otomatis diarahkan ke halaman ulasan.
                </div>
            @else
                <h4 class="title">Bagaimana pengalaman Anda?</h4>
                <p class="subtitle">Pilih bintang untuk memberikan ulasan:</p>

                <div class="stars" aria-label="Rating">
                    <label onclick="handleRating(5)" aria-label="5 bintang">★</label>
                    <label onclick="handleRating(4)" aria-label="4 bintang">★</label>
                    <label onclick="handleRating(3)" aria-label="3 bintang">★</label>
                    <label onclick="handleRating(2)" aria-label="2 bintang">★</label>
                    <label onclick="handleRating(1)" aria-label="1 bintang">★</label>
                </div>
            @endif

            <div>
                <a href="{{ route('qr.edit', $qrTag->id) }}" class="info-link">Edit Informasi Toko</a>
            </div>
        </div>
    </div>

    <script>
        function handleRating(stars) {
            const placeId = "{{ $qrTag->place_id }}";
            let waNumber = "{{ $qrTag->whatsapp_number }}";

            if (waNumber.startsWith('0')) {
                waNumber = '62' + waNumber.substring(1);
            }

            if (stars <= 3) {
                const pesan = encodeURIComponent("Halo, saya pengunjung toko Anda dan ingin memberikan sedikit masukan mengenai pelayanan/produk tadi...");
                window.location.href = `https://wa.me/${waNumber}?text=${pesan}`;
            } else {
                window.location.href = `https://search.google.com/local/writereview?placeid=${placeId}`;
            }
        }
    </script>

</body>
</html>