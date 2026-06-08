<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download QR Codes - {{ $company->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f59e0b;
            --secondary: #3b82f6;
            --dark: #0f172a;
            --slate: #64748b;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--dark);
        }
        .container {
            max-width: 900px;
            width: 100%;
            background: white;
            padding: 60px;
            border-radius: 3rem;
            box-shadow: 0 40px 100px rgba(15, 23, 42, 0.08);
            text-align: center;
            border: 1px solid rgba(241, 245, 249, 1);
        }
        .header {
            margin-bottom: 4rem;
        }
        h1 {
            color: var(--dark);
            font-weight: 900;
            margin: 0;
            font-size: 2.5rem;
            letter-spacing: -0.04em;
        }
        p.subtitle {
            color: var(--slate);
            margin-top: 0.5rem;
            font-size: 1.1rem;
            font-weight: 500;
        }
        .qr-grid {
            display: grid;
            grid-template-columns: 1fr;
            max-width: 450px;
            margin: 0 auto 4rem auto;
            margin-bottom: 4rem;
        }
        .qr-card {
            position: relative;
            background: #fff;
            border: 2px solid #f1f5f9;
            padding: 3rem 2rem;
            border-radius: 2.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden; /* contain the glow */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --glow-color: rgba(245, 158, 11, 0.08);
        }
        .qr-card.checkout {
            --glow-color: rgba(59, 130, 246, 0.08);
        }
        .qr-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(
                350px circle at var(--mouse-x, 0px) var(--mouse-y, 0px),
                var(--glow-color),
                transparent 80%
            );
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .qr-card:hover::before {
            opacity: 1;
        }
        .qr-card > * {
            position: relative;
            z-index: 2;
        }
        .qr-card:hover {
            border-color: var(--primary);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
        }
        .qr-card.checkout:hover {
            border-color: var(--secondary);
        }
        .tag {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 2rem;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
        }
        .tag.checkin { background: rgba(245, 158, 11, 0.1); color: var(--primary); }
        .tag.checkout { background: rgba(59, 130, 246, 0.1); color: var(--secondary); }

        .qr-image {
            width: 300px;
            height: 300px;
            background: white;
            padding: 1.5rem;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
            border: 1px solid #f1f5f9;
        }
        .qr-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .code-text {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--slate);
            margin-bottom: 2rem;
            border: 1px solid #f1f5f9;
        }
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 3rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            border: none;
            padding: 1rem 2rem;
            border-radius: 1.25rem;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-print {
            background: var(--dark);
            color: white;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        }
        .btn-print:hover {
            background: #1e293b;
            transform: translateY(-2px);
        }
        .btn-download {
            background: white;
            color: var(--dark);
            border: 2px solid #f1f5f9;
        }
        .btn-download:hover {
            border-color: var(--dark);
            transform: translateY(-2px);
        }
        
        .card-download {
            font-size: 0.875rem;
            color: var(--slate);
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
        }
        .card-download:hover {
            background: #f8fafc;
            color: var(--dark);
        }

        @media print {
            .actions, .card-download { display: none !important; }
            body { background: white; padding: 0; }
            .container { box-shadow: none; border: none; padding: 20px; max-width: 100%; }
            .qr-card { border: 1px solid #eee !important; break-inside: avoid; }
            .qr-grid { gap: 2rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Attendance QR Codes</h1>
            <p class="subtitle">{{ $company->name }} — {{ $company->address }}</p>
        </div>

        <div class="qr-grid" style="{{ $type ? 'grid-template-columns: 1fr; max-width: 450px; margin-left: auto; margin-right: auto;' : '' }}">
            {{-- Presensi --}}
            @if(!$type || $type === 'check-in')
                <div class="qr-card">
                    <div class="tag checkin">PRESENSI</div>
                    <div class="qr-image">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode($company->check_in_code) }}" alt="QR Presensi">
                    </div>
                    <div class="code-text">{{ $company->check_in_code }}</div>
                    <a href="{{ route('admin.download-qr-image', ['code' => $company->check_in_code, 'type' => 'check-in', 'company' => $company->name]) }}" class="card-download">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Image
                    </a>
                </div>
            @endif
        </div>

        <div class="actions">
            <button class="btn btn-print" onclick="window.print()">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print / Save PDF
            </button>
        </div>
    </div>
    <script>
        document.querySelectorAll('.qr-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    </script>
</body>
</html>
