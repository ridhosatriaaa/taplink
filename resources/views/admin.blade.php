<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard & Ekspor Data Papan QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6fb; }
        .table-responsive { background: #fff; border-radius: 12px; }
    </style>
</head>
<body class="py-4">

    <div class="container" style="max-width: 1100px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Dashboard Admin Papan QR</h3>
            <div class="d-flex gap-2 flex-wrap">
                <button onclick="window.print()" class="btn btn-primary fw-bold rounded-3 px-4">
                    🖨️ Cetak Kartu QR
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-3">Buat Papan QR Baru</h5>
            <form action="{{ route('admin.generate') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-sm">
                    <input type="number" name="count" class="form-control rounded-3" placeholder="Jumlah Papan (misal: 10)" min="1" max="500" required>
                </div>
                <div class="col-sm-auto">
                    <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4 w-100">+ Generate Papan</button>
                </div>
            </form>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-4">Daftar Papan QR (Pratinjau Cetak)</h5>

            <div class="print-grid">
                @forelse($tags as $tag)
                    <div class="qr-card-item">
                        <div class="board-id">Papan #{{ $tag->id }}</div>
                        <div class="status-badge {{ $tag->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $tag->is_active ? 'Sudah Diaktivasi' : 'Belum Diaktivasi' }}
                        </div>
                        <div class="qr-code" data-url="{{ route('qr.scan', $tag->id) }}"></div>

                        <div class="no-print qr-card-link w-100">
                            <a href="{{ route('qr.scan', $tag->id) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 rounded-3 fw-bold mb-2">
                                🔗 Tes Buka Link
                            </a>
                            <button type="button" class="btn btn-sm btn-success w-100 rounded-3 fw-bold download-qr-btn" data-id="{{ $tag->id }}">
                                ⬇️ Download QR
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="w-100 text-center text-muted py-4">
                        Belum ada papan QR yang dibuat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        body { background: #f4f6fb; }

        .print-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.1rem;
            align-items: stretch;
        }

        .qr-card-item {
            position: relative;
            width: 100%;
            min-height: 220px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            padding: 16px 12px 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .board-id {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            margin-bottom: 8px;
            color: #1f2937;
            line-height: 1.2;
        }

        .status-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            padding: 5px 9px;
            border-radius: 999px;
            margin-bottom: 10px;
        }

        .status-active {
            background: #e8f9ef;
            color: #0f8943;
        }

        .status-inactive {
            background: #f3f4f6;
            color: #4b5563;
        }

        .qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
            border-radius: 10px;
            padding: 8px;
            width: 130px;
            height: 130px;
        }

        .qr-card-link {
            margin-top: 12px;
            width: 100%;
            font-size: 0.75rem;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm 8mm 8mm 8mm;
            }

            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .print-grid {
                display: grid !important;
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                gap: 4mm !important;
                align-items: stretch !important;
                width: 100% !important;
            }

            .qr-card-item {
                width: 100% !important;
                min-width: 0 !important;
                max-width: none !important;
                height: 44mm !important;
                min-height: 44mm !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                border: 1px solid #d0d7de !important;
                border-radius: 4px !important;
                box-shadow: none !important;
                padding: 2mm !important;
                margin: 0 !important;
                background: #fff !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                align-items: center !important;
                text-align: center !important;
                position: relative !important;
            }

            .board-id {
                display: block !important;
                font-size: 9pt !important;
                font-weight: 700 !important;
                letter-spacing: 0.04em !important;
                margin: 0 0 2mm 0 !important;
                line-height: 1.2 !important;
                color: #1f2937 !important;
                width: 100% !important;
                text-align: center !important;
                position: static !important;
            }

            .status-badge {
                display: none !important;
            }

            .qr-code {
                width: 30mm !important;
                height: 30mm !important;
                max-width: 30mm !important;
                max-height: 30mm !important;
                padding: 0 !important;
                margin: 0 auto !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                background: #fff !important;
                border-radius: 0 !important;
                position: relative !important;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.qr-code').forEach(function (element) {
                var url = element.getAttribute('data-url');
                new QRCode(element, {
                    text: url,
                    width: 150,
                    height: 150,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            });

            document.querySelectorAll('.download-qr-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    var card = button.closest('.qr-card-item');
                    var qr = card.querySelector('.qr-code');
                    if (!qr) return;

                    var canvas = qr.querySelector('canvas');
                    if (!canvas) {
                        canvas = qr.querySelector('img');
                    }

                    if (!canvas) return;

                    var link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = 'papan-' + button.getAttribute('data-id') + '-qr.png';
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                });
            });
        });
    </script>

</body>
</html>