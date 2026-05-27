<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricing Plans — BroadKaster</title>
    <meta name="description" content="Choose a BroadKaster plan that fits your esports production needs. From daily passes to annual subscriptions — flexible pricing for every tournament organizer.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/symbol.png') }}">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange: #f97316;
            --orange-deep: #ea580c;
            --orange-glow: rgba(249, 115, 22, 0.35);
            --amber: #f59e0b;
            --dark: #0a0b0e;
            --dark-2: #0f1117;
            --dark-3: #161923;
            --dark-4: #1e2535;
            --slate: #8b9ab0;
            --slate-light: #c4cedc;
            --green: #22c55e;
            --red: #ef4444;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: #e2e8f0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            will-change: transform;
            animation: orbDrift linear infinite alternate;
        }
        .orb-1 { width: 700px; height: 700px; top: -15%; right: -10%; background: radial-gradient(circle, rgba(249, 115, 22, 0.15), transparent 70%); animation-duration: 22s; }
        .orb-2 { width: 600px; height: 600px; bottom: -20%; left: -12%; background: radial-gradient(circle, rgba(245, 158, 11, 0.1), transparent 70%); animation-duration: 28s; animation-delay: -8s; }

        @keyframes orbDrift {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -60px) scale(1.08); }
            100% { transform: translate(-30px, 40px) scale(0.95); }
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }

        /* ─── Navbar ─── */
        nav {
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            background: rgba(10, 11, 14, 0.85);
            backdrop-filter: blur(24px) saturate(1.5);
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .nav-logo img { height: 36px; width: auto; }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-ghost {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--slate-light);
            text-decoration: none;
            padding: 8px 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            background: transparent;
            transition: all 0.2s ease;
        }

        .btn-ghost:hover {
            color: #fff;
            border-color: rgba(249, 115, 22, 0.4);
            background: rgba(249, 115, 22, 0.06);
        }

        .btn-primary {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            padding: 9px 22px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 35px rgba(249, 115, 22, 0.5);
        }

        /* ─── Page Header ─── */
        .page-hero {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 80px 2rem 60px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--orange);
            background: rgba(249, 115, 22, 0.08);
            border: 1px solid rgba(249, 115, 22, 0.22);
            padding: 7px 16px;
            border-radius: 99px;
            margin-bottom: 28px;
        }

        .hero-eyebrow .dot {
            width: 6px; height: 6px;
            background: var(--orange);
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        .page-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1.05;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .page-title .highlight {
            background: linear-gradient(135deg, #f97316, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-sub {
            font-size: 16px;
            color: var(--slate);
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ─── Pricing Grid ─── */
        .pricing-section {
            position: relative;
            z-index: 10;
            padding: 0 2rem 100px;
        }

        .pricing-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }

        /* ─── Plan Card ─── */
        .plan-card {
            background: var(--dark-2);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: cardIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .plan-card:hover {
            border-color: rgba(249, 115, 22, 0.25);
            transform: translateY(-4px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(249, 115, 22, 0.1);
        }

        .plan-card.featured {
            background: linear-gradient(145deg, rgba(249, 115, 22, 0.07), var(--dark-2) 60%);
            border-color: rgba(249, 115, 22, 0.35);
        }

        .plan-card.featured::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--orange), transparent);
        }

        .featured-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--dark);
            background: linear-gradient(135deg, #f97316, #f59e0b);
            padding: 5px 12px;
            border-radius: 99px;
        }

        /* Card header */
        .plan-header { margin-bottom: 24px; }

        .plan-name {
            font-family: 'Rajdhani', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #f1f5f9;
            letter-spacing: 0.02em;
            margin-bottom: 6px;
        }

        .plan-description {
            font-size: 12.5px;
            color: var(--slate);
            line-height: 1.6;
        }

        /* Price block */
        .plan-price-block {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .plan-price {
            font-family: 'Rajdhani', sans-serif;
            font-size: 38px;
            font-weight: 700;
            color: var(--orange);
            line-height: 1;
            margin-bottom: 4px;
        }

        .plan-price.free {
            color: var(--green);
        }

        .plan-duration {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--slate);
        }

        /* Features list */
        .plan-features {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 28px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--slate-light);
        }

        .feature-item.disabled {
            color: rgba(139, 154, 176, 0.4);
            text-decoration: line-through;
        }

        .feature-icon {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon.check {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .feature-icon.check svg { width: 10px; height: 10px; color: var(--green); }

        .feature-icon.cross {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .feature-icon.cross svg { width: 10px; height: 10px; color: var(--red); opacity: 0.6; }

        .feature-limit {
            margin-left: auto;
            font-size: 11px;
            font-weight: 700;
            font-family: 'Rajdhani', sans-serif;
            color: var(--orange);
            background: rgba(249, 115, 22, 0.08);
            border: 1px solid rgba(249, 115, 22, 0.15);
            padding: 2px 8px;
            border-radius: 99px;
        }

        /* CTA Button */
        .plan-cta {
            display: block;
            text-align: center;
            padding: 13px 24px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .plan-cta.primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            box-shadow: 0 0 24px rgba(249, 115, 22, 0.3);
        }

        .plan-cta.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px rgba(249, 115, 22, 0.5);
        }

        .plan-cta.secondary {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--slate-light);
        }

        .plan-cta.secondary:hover {
            background: rgba(249, 115, 22, 0.07);
            border-color: rgba(249, 115, 22, 0.3);
            color: #fff;
        }

        /* ─── FAQ Note ─── */
        .note-section {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 0 2rem 80px;
        }

        .note-card {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(249, 115, 22, 0.04);
            border: 1px solid rgba(249, 115, 22, 0.12);
            border-radius: 16px;
            padding: 28px 36px;
        }

        .note-card p {
            font-size: 14px;
            color: var(--slate);
            line-height: 1.7;
        }

        .note-card a {
            color: var(--orange);
            text-decoration: none;
            font-weight: 600;
        }

        .note-card a:hover {
            text-decoration: underline;
        }

        /* ─── Footer ─── */
        footer {
            position: relative;
            z-index: 10;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding: 32px 2rem;
            text-align: center;
        }

        footer p {
            font-size: 12px;
            color: var(--slate);
            letter-spacing: 0.05em;
        }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .pricing-grid { grid-template-columns: 1fr; }
            .page-title { font-size: clamp(30px, 8vw, 48px); }
        }

        /* ─── Mobile Menu ─── */
        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 8px;
        }

        .mobile-menu {
            display: none;
            position: absolute;
            top: 72px;
            left: 0;
            right: 0;
            background: var(--dark-2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem 2rem;
            flex-direction: column;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .mobile-menu.active {
            display: flex;
        }

        .mobile-menu a {
            padding: 12px 16px;
            text-decoration: none;
            color: #f1f5f9;
            font-weight: 600;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
            text-align: center;
        }

        @media (max-width: 768px) {
            .nav-actions {
                display: none;
            }
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Navbar -->
    <nav>
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="{{ asset('img/logo.png') }}" alt="BroadKaster">
            </a>
            <div class="nav-actions">
                <a href="/" class="btn-ghost">Home</a>
                @auth
                    <a href="/maidan" class="btn-primary">Dashboard</a>
                @else
                    <a href="/maidan" class="btn-primary">Get Started</a>
                @endauth
            </div>
            <button class="mobile-menu-btn" id="mobile-menu-btn">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <div class="mobile-menu" id="mobile-menu">
            <a href="/">Home</a>
            @auth
                <a href="/maidan">Dashboard</a>
            @else
                <a href="/maidan/login" style="background: linear-gradient(135deg, #f97316, #ea580c); color: #fff;">Get Started</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Header -->
    <div class="page-hero">
        <div class="hero-eyebrow">
            <span class="dot"></span>
            Transparent Pricing
        </div>
        <h1 class="page-title">
            Plans for Every<br><span class="highlight">Tournament Scale</span>
        </h1>
        <p class="page-sub">From a single day event to a full esports season — choose the plan that fits your production needs. All plans include OBS overlays and real-time sync.</p>
    </div>

    <!-- Pricing Cards -->
    <section class="pricing-section">
        <div class="pricing-grid">
            @php
                $popular = ['1-month-plan', '3-month-plan'];
            @endphp

            @foreach($plans as $index => $plan)
            @php
                $isFeatured = in_array($plan->slug, $popular);
                $features = $plan->features ?? [];
                $maxTournaments = $features['max_tournaments'] ?? 0;
                $maxTeams = $features['max_teams'] ?? 0;
                $maxMatches = $features['max_matches'] ?? 0;
                $isFree = !$plan->price || $plan->price <= 0;
                $durationDisplay = $plan->duration_value ? ucfirst($plan->duration_period === 'days' ? $plan->duration_value . ' Day(s)' : ($plan->duration_value . ' ' . ucfirst(rtrim($plan->duration_period, 's')))) : 'Lifetime';
                $durationLabel = match($plan->duration_period) {
                    'days' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Days' : 'Day') . ' Access',
                    'weeks' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Weeks' : 'Week') . ' Access',
                    'months' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Months' : 'Month') . ' Access',
                    'years' => $plan->duration_value . ' ' . ($plan->duration_value > 1 ? 'Years' : 'Year') . ' Access',
                    default => 'Lifetime Access',
                };
            @endphp
            <div class="plan-card {{ $isFeatured ? 'featured' : '' }}" style="animation-delay: {{ $index * 0.07 }}s;">
                @if($isFeatured)
                    <div class="featured-badge">Most Popular</div>
                @endif

                <div class="plan-header">
                    <div class="plan-name">{{ $plan->name }}</div>
                    <div class="plan-description">{{ $plan->description }}</div>
                </div>

                <div class="plan-price-block">
                    <div class="plan-price {{ $isFree ? 'free' : '' }}">
                        {{ $plan->price_display }}
                    </div>
                    <div class="plan-duration">{{ $durationLabel }}</div>
                </div>

                <div class="plan-features">
                    <!-- Tournaments -->
                    <div class="feature-item">
                        <span class="feature-icon check">
                            <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        <span>Tournaments</span>
                        <span class="feature-limit">{{ $maxTournaments === -1 ? '∞' : $maxTournaments }}</span>
                    </div>
                    <!-- Teams -->
                    <div class="feature-item">
                        <span class="feature-icon check">
                            <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        <span>Teams per Tournament</span>
                        <span class="feature-limit">{{ $maxTeams === -1 ? '∞' : $maxTeams }}</span>
                    </div>
                    <!-- Matches -->
                    <div class="feature-item">
                        <span class="feature-icon check">
                            <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        <span>Matches per Tournament</span>
                        <span class="feature-limit">{{ ($features['max_matches'] ?? 0) === -1 ? '∞' : ($features['max_matches'] ?? 0) }}</span>
                    </div>
                    <!-- OBS Overlays -->
                    <div class="feature-item {{ ($features['obs_overlays'] ?? false) ? '' : 'disabled' }}">
                        <span class="feature-icon {{ ($features['obs_overlays'] ?? false) ? 'check' : 'cross' }}">
                            @if($features['obs_overlays'] ?? false)
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>OBS Overlays</span>
                    </div>
                    <!-- WebSocket Sync -->
                    <div class="feature-item {{ ($features['websocket_sync'] ?? false) ? '' : 'disabled' }}">
                        <span class="feature-icon {{ ($features['websocket_sync'] ?? false) ? 'check' : 'cross' }}">
                            @if($features['websocket_sync'] ?? false)
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Real-Time WebSocket Sync</span>
                    </div>
                    <!-- Roadmap Overlay -->
                    <div class="feature-item {{ ($features['roadmap_overlay'] ?? false) ? '' : 'disabled' }}">
                        <span class="feature-icon {{ ($features['roadmap_overlay'] ?? false) ? 'check' : 'cross' }}">
                            @if($features['roadmap_overlay'] ?? false)
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Roadmap Overlay</span>
                    </div>
                    <!-- Casters Management -->
                    <div class="feature-item {{ ($features['casters_management'] ?? false) ? '' : 'disabled' }}">
                        <span class="feature-icon {{ ($features['casters_management'] ?? false) ? 'check' : 'cross' }}">
                            @if($features['casters_management'] ?? false)
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Casters Management</span>
                    </div>
                    <!-- Player Management -->
                    <div class="feature-item {{ ($features['player_management'] ?? false) ? '' : 'disabled' }}">
                        <span class="feature-icon {{ ($features['player_management'] ?? false) ? 'check' : 'cross' }}">
                            @if($features['player_management'] ?? false)
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Player Management</span>
                    </div>
                    <!-- Custom Branding -->
                    <div class="feature-item {{ ($features['custom_branding'] ?? false) ? '' : 'disabled' }}">
                        <span class="feature-icon {{ ($features['custom_branding'] ?? false) ? 'check' : 'cross' }}">
                            @if($features['custom_branding'] ?? false)
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                            @else
                                <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l6 6M9 3l-6 6"/></svg>
                            @endif
                        </span>
                        <span>Custom Branding</span>
                    </div>
                </div>

                <a href="/maidan" class="plan-cta {{ $isFeatured ? 'primary' : 'secondary' }}">
                    Get Started
                </a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Note Section -->
    <div class="note-section">
        <div class="note-card">
            <p>
                All plans are activated manually by our team after verifying your payment. Once logged in, head to your dashboard and submit a subscription request with your transaction screenshot.
                Have questions? <a href="/">Contact us</a> and we'll help you choose the right plan.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} BroadKaster &mdash; All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('active');
        });
    </script>
</body>
</html>
