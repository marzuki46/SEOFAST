<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $content->target_keyword ?? 'Segera Hadir' }} — Coming Soon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }
        .badge {
            display: inline-block;
            background: rgba(99,102,241,0.2);
            color: #a5b4fc;
            border: 1px solid rgba(99,102,241,0.4);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 0.4rem 1rem;
            border-radius: 100px;
            margin-bottom: 1.5rem;
        }
        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.8rem, 5vw, 3rem);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 1rem;
        }
        p {
            color: rgba(255,255,255,0.6);
            font-size: 1rem;
            line-height: 1.7;
        }
        .pulse {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s ease infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="badge"><span class="pulse" style="margin-right:6px"></span>Segera Terbit</div>
        <h1>{{ ucwords(str_replace('-', ' ', $content->slug)) }}</h1>
        <p>
            Halaman ini sedang dalam proses produksi konten AI kami.<br>
            Konten berkualitas tinggi untuk topik <strong style="color:white">{{ $content->target_keyword }}</strong> akan segera tersedia.
        </p>
    </div>
</body>
</html>
