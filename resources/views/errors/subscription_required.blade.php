<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BroadKaster — Subscription Required</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0b0e;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
        }
        /* Background glows */
        .glow {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.15), transparent 70%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }
        .glow-1 { top: -10%; right: -10%; }
        .glow-2 { bottom: -10%; left: -10%; }
        
        .container {
            position: relative;
            z-index: 10;
            max-width: 500px;
            width: 100%;
            background: rgba(15, 17, 23, 0.7);
            border: 1px solid rgba(249, 115, 22, 0.2);
            border-radius: 20px;
            padding: 40px 32px;
            text-align: center;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(249, 115, 22, 0.05);
        }
        .icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 24px;
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f97316;
            animation: pulse 2s infinite alternate;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
            100% { transform: scale(1.04); box-shadow: 0 0 20px 4px rgba(249, 115, 22, 0.1); }
        }
        h1 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 12px;
            letter-spacing: 0.02em;
        }
        p {
            font-size: 14px;
            color: #8b9ab0;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.4), 0 6px 20px rgba(249, 115, 22, 0.3);
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.6), 0 8px 30px rgba(249, 115, 22, 0.45);
        }
    </style>
</head>
<body>
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>
    
    <div class="container">
        <div class="icon">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>
        <h1>Subscription Inactive</h1>
        <p>This BroadKaster overlay stream is currently inactive or the host's subscription has expired. If you are the owner, please check your subscription status in the Maidan panel or contact support.</p>
        <a href="/" class="btn">
            Go to Homepage
        </a>
    </div>
</body>
</html>
