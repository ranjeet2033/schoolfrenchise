<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre Director ID Card - {{ $centre->director_name }} ({{ $centre->centre_code }})</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Montserrat:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 25px;
            color: #1e293b;
        }

        .id-card-wrapper {
            width: 86mm;
            min-height: 54mm;
            max-width: 380px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid #cbd5e1;
            position: relative;
        }

        .id-card-header {
            background: linear-gradient(135deg, #0b1f3a 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 10px 14px;
            text-align: center;
            border-bottom: 3px solid #f59e0b;
        }

        .id-card-header h2 {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0;
            color: #ffffff;
            text-transform: uppercase;
        }

        .id-card-header p {
            font-size: 0.62rem;
            color: #cbd5e1;
            margin: 1px 0 0 0;
        }

        .auth-ribbon {
            background: #f59e0b;
            color: #000;
            font-weight: 900;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 6px;
            text-align: center;
        }

        .id-card-body {
            padding: 14px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .director-photo-frame {
            width: 85px;
            height: 105px;
            border: 2px solid #0284c7;
            border-radius: 8px;
            overflow: hidden;
            background: #f8fafc;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .director-photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .director-details {
            flex-grow: 1;
            font-size: 0.72rem;
            line-height: 1.35;
        }

        .director-name {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .designation-badge {
            display: inline-block;
            background: #0284c7;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.65rem;
            padding: 1px 8px;
            border-radius: 4px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .detail-line {
            margin-bottom: 3px;
        }

        .detail-lbl {
            font-weight: 600;
            color: #64748b;
        }

        .detail-val {
            font-weight: 700;
            color: #0f172a;
        }

        .id-card-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 8px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qr-box {
            width: 44px;
            height: 44px;
            background: #fff;
            padding: 2px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .qr-box img {
            width: 100%;
            height: 100%;
        }

        .seal-sign-box {
            text-align: right;
            font-size: 0.6rem;
            font-weight: 700;
            color: #475569;
        }

        .seal-sign-box img {
            max-height: 25px;
            max-width: 90px;
            object-fit: contain;
            display: block;
            margin: 0 0 2px auto;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .id-card-wrapper {
                box-shadow: none;
                border: 1px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary px-4 rounded-pill shadow-sm fw-bold">
            <i class="fa-solid fa-print me-1"></i> Print Director ID Card
        </button>
    </div>

    <div class="id-card-wrapper">
        <!-- Header -->
        <div class="id-card-header">
            <h2>{{ $instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER' }}</h2>
            <p>{{ $instituteSettings['institute_tagline'] ?? 'Building Digital Skills for the Future' }}</p>
        </div>

        <!-- Authorized by Tech era computer -->
        <div class="auth-ribbon">
            <i class="fa-solid fa-shield-check me-1"></i> Authorized by Tech era computer
        </div>

        <!-- Body -->
        <div class="id-card-body">
            <div class="director-photo-frame">
                @if($centre->director_photo)
                    <img src="{{ $centre->director_photo_url ?? asset('storage/' . $centre->director_photo) }}" alt="{{ $centre->director_name }}" onerror="this.onerror=null;this.src='https://placehold.co/100x120?text=Director';">
                @else
                    <i class="fa-solid fa-user-tie text-secondary fs-1"></i>
                @endif
            </div>

            <div class="director-details">
                <div class="director-name">{{ $centre->director_name }}</div>
                <div class="designation-badge">Centre Director / Head</div>

                <div class="detail-line">
                    <span class="detail-lbl">Centre:</span>
                    <span class="detail-val">{{ $centre->centre_name }}</span>
                </div>
                <div class="detail-line">
                    <span class="detail-lbl">Centre Code:</span>
                    <span class="detail-val font-monospace text-primary">{{ $centre->centre_code }}</span>
                </div>
                <div class="detail-line">
                    <span class="detail-lbl">Mobile:</span>
                    <span class="detail-val">{{ $centre->mobile ?? $centre->contact_phone }}</span>
                </div>
                <div class="detail-line">
                    <span class="detail-lbl">Location:</span>
                    <span class="detail-val">{{ $centre->city }}, {{ $centre->state?->name ?? 'Bihar' }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="id-card-footer">
            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('franchise.search', ['centre_code' => $centre->centre_code])) }}" alt="QR">
            </div>
            <div class="seal-sign-box">
                <img src="{{ asset('images/deepak-kumar-signature.png') }}" alt="Signature">
                <div>Issuing Authority</div>
                <div class="text-muted" style="font-size: 0.55rem;">TECH ERA COMPUTER</div>
            </div>
        </div>
    </div>

</body>
</html>
