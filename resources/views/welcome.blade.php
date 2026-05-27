<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BroadKaster — Live Tournament Broadcasting System</title>
    <meta name="description" content="BroadKaster is a professional real-time tournament broadcasting platform with OBS-ready overlays, live stats, and dynamic rankings for PUBG Mobile esports events.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/symbol.png') }}">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

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
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: #e2e8f0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── Canvas / Background ─── */
        #bg-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            will-change: transform;
            animation: orbDrift linear infinite alternate;
        }

        .orb-1 {
            width: 700px;
            height: 700px;
            top: -15%;
            right: -10%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.18), transparent 70%);
            animation-duration: 22s;
        }

        .orb-2 {
            width: 600px;
            height: 600px;
            bottom: -20%;
            left: -12%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.12), transparent 70%);
            animation-duration: 28s;
            animation-delay: -8s;
        }

        .orb-3 {
            width: 400px;
            height: 400px;
            top: 40%;
            left: 35%;
            background: radial-gradient(circle, rgba(234, 88, 12, 0.08), transparent 70%);
            animation-duration: 35s;
            animation-delay: -15s;
        }

        @keyframes orbDrift {
            0% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(40px, -60px) scale(1.08);
            }

            100% {
                transform: translate(-30px, 40px) scale(0.95);
            }
        }

        /* ─── Noise texture overlay ─── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }

        /* ─── Layout ─── */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
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

        .nav-logo img {
            height: 36px;
            width: auto;
        }

        .nav-badge {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--orange);
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.25);
            padding: 3px 8px;
            border-radius: 99px;
        }

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

        /* ─── HERO ─── */
        .hero {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 100px 2rem 80px;
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
            margin-bottom: 36px;
        }

        .hero-eyebrow .dot {
            width: 6px;
            height: 6px;
            background: var(--orange);
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        .hero-logo-wrap {
            margin-bottom: 32px;
            animation: heroLogoIn 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes heroLogoIn {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.92);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .hero-logo {
            width: min(520px, 80vw);
            height: auto;
            filter: drop-shadow(0 0 40px rgba(249, 115, 22, 0.22));
        }

        .hero-tagline {
            font-size: clamp(14px, 2.5vw, 18px);
            font-weight: 400;
            line-height: 1.7;
            color: var(--slate);
            max-width: 580px;
            margin-bottom: 48px;
            animation: fadeUp 0.9s 0.25s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-ctas {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 80px;
            animation: fadeUp 0.9s 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            padding: 14px 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.4), 0 8px 32px rgba(249, 115, 22, 0.35);
            transition: all 0.25s ease;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.6), 0 12px 48px rgba(249, 115, 22, 0.5);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--slate-light);
            text-decoration: none;
            padding: 14px 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.25s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(249, 115, 22, 0.3);
            color: #fff;
        }

        /* ─── Stats bar ─── */
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 0;
            flex-wrap: wrap;
            margin-bottom: 100px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(12px);
            overflow: hidden;
            animation: fadeUp 0.9s 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
            max-width: 760px;
            width: 100%;
        }

        .stat-item {
            flex: 1;
            min-width: 140px;
            padding: 24px 20px;
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-num {
            font-family: 'Rajdhani', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--orange);
            display: block;
            line-height: 1;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--slate);
            margin-top: 6px;
            display: block;
        }

        /* ─── Section heading ─── */
        .section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 12px;
        }

        .section-heading {
            font-family: 'Rajdhani', sans-serif;
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .section-sub {
            font-size: 15px;
            color: var(--slate);
            max-width: 520px;
            line-height: 1.7;
        }

        /* ─── Features Grid ─── */
        .features-section {
            padding: 0 2rem 100px;
        }

        .features-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .features-header .section-sub {
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            overflow: hidden;
            max-width: 1100px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--dark-2);
            padding: 40px 36px;
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--orange), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover {
            background: var(--dark-3);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card.featured {
            background: linear-gradient(145deg, rgba(249, 115, 22, 0.08), var(--dark-2));
        }

        .feature-card.featured::before {
            opacity: 0.5;
        }

        .feature-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon-wrap {
            transform: scale(1.08);
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            color: var(--orange);
        }

        .feature-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #f1f5f9;
            letter-spacing: 0.02em;
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 13.5px;
            line-height: 1.7;
            color: var(--slate);
        }

        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--orange);
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.2);
            padding: 4px 10px;
            border-radius: 99px;
            margin-top: 18px;
        }

        /* ─── Workflow Section ─── */
        .workflow-section {
            padding: 60px 2rem 100px;
            position: relative;
            z-index: 10;
        }

        .workflow-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .workflow-steps {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .workflow-step {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .step-num {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 0 16px rgba(249, 115, 22, 0.3);
        }

        .step-content {}

        .step-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 6px;
        }

        .step-desc {
            font-size: 13.5px;
            color: var(--slate);
            line-height: 1.6;
        }

        /* ─── Dashboard mockup card ─── */
        .dashboard-mockup {
            background: var(--dark-3);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(249, 115, 22, 0.07);
        }

        .mockup-topbar {
            background: var(--dark-4);
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mockup-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .mockup-body {
            padding: 24px;
        }

        .mockup-row {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }

        .mockup-block {
            flex: 1;
            border-radius: 8px;
            padding: 14px 16px;
            background: var(--dark-2);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mockup-block-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--slate);
            margin-bottom: 6px;
        }

        .mockup-block-val {
            font-family: 'Rajdhani', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--orange);
        }

        .mockup-table-row {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .mockup-rank {
            font-family: 'Rajdhani', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--orange);
            width: 20px;
            text-align: center;
        }

        .mockup-team {
            flex: 1;
            font-size: 12px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .mockup-pts {
            font-family: 'Rajdhani', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
        }

        .mockup-bar-wrap {
            height: 4px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 99px;
            overflow: hidden;
            margin-top: 8px;
        }

        .mockup-bar {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #f97316, #f59e0b);
        }

        /* ─── Demo Form Section ─── */
        .demo-section {
            padding: 0 2rem 120px;
            position: relative;
            z-index: 10;
        }

        .demo-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.06), rgba(10, 11, 14, 0.5));
            border: 1px solid rgba(249, 115, 22, 0.15);
            border-radius: 24px;
            padding: 64px;
            overflow: hidden;
            position: relative;
        }

        .demo-inner::before {
            content: '';
            position: absolute;
            top: 0; left: 50%; transform: translateX(-50%);
            width: 40%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.4), transparent);
        }

        .demo-value-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 28px;
        }

        .demo-value-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            color: var(--slate-light);
            line-height: 1.5;
        }

        .demo-check {
            flex-shrink: 0;
            width: 20px; height: 20px;
            border-radius: 50%;
            background: rgba(249, 115, 22, 0.12);
            border: 1px solid rgba(249, 115, 22, 0.3);
            display: flex; align-items: center; justify-content: center;
            margin-top: 1px;
        }

        .demo-check svg { width: 11px; height: 11px; color: var(--orange); }

        /* ─── Form Styles ─── */
        .demo-form { display: flex; flex-direction: column; gap: 16px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }

        .form-label {
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--slate);
        }

        .form-input, .form-textarea {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 8px;
            padding: 10px 14px;
            color: #f1f5f9;
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, background 0.2s;
            outline: none;
            width: 100%;
        }

        .form-input::placeholder, .form-textarea::placeholder { color: #475569; }

        .form-input:focus, .form-textarea:focus {
            border-color: rgba(249, 115, 22, 0.5);
            background: rgba(249, 115, 22, 0.04);
        }

        .form-input.error { border-color: rgba(239, 68, 68, 0.6); }

        .form-textarea { resize: vertical; min-height: 90px; }

        .form-error { font-size: 11px; color: #f87171; margin-top: 2px; }

        .form-submit {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 15px;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: #fff;
            padding: 13px 28px;
            border: none; cursor: pointer;
            border-radius: 10px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.4), 0 6px 24px rgba(249, 115, 22, 0.3);
            transition: all 0.25s ease;
            width: 100%;
        }

        .form-submit:hover { box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.6), 0 8px 32px rgba(249, 115, 22, 0.45); }
        .form-submit:disabled { opacity: 0.6; cursor: not-allowed; }

        .success-card {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.25);
            border-radius: 12px;
            padding: 28px;
            text-align: center;
        }

        .success-card .success-icon {
            width: 48px; height: 48px;
            background: rgba(34, 197, 94, 0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }

        @media (max-width: 900px) {
            .demo-inner { grid-template-columns: 1fr; gap: 48px; padding: 40px 28px; }
            .form-row { grid-template-columns: 1fr; }
        }

        /* ─── Footer ─── */
        footer {
            position: relative;
            z-index: 10;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            background: rgba(10, 11, 14, 0.9);
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-brand img {
            height: 22px;
            opacity: 0.6;
        }

        .footer-copy {
            font-size: 11px;
            color: #334155;
            font-weight: 500;
        }

        .footer-build {
            font-family: monospace;
            font-size: 10px;
            color: #1e293b;
            letter-spacing: 0.08em;
        }

        /* ─── Responsive ─── */
        @media (max-width: 900px) {
            .features-grid {
                grid-template-columns: 1fr;
            }

            .workflow-inner {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .dashboard-mockup {
                display: none;
            }

            .stats-bar {
                max-width: 100%;
            }
        }

        @media (max-width: 600px) {
            .hero {
                padding: 64px 1.5rem 48px;
            }

            .hero-logo {
                width: 90vw;
            }

            .hero-ctas {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                text-align: center;
                justify-content: center;
            }

            .stat-item {
                min-width: 110px;
                padding: 18px 12px;
            }

            .cta-card {
                padding: 40px 24px;
            }
        }

    </style>
</head>

<body>
    <!-- Atmospheric Orbs -->
    <div class="orb orb-1" aria-hidden="true"></div>
    <div class="orb orb-2" aria-hidden="true"></div>
    <div class="orb orb-3" aria-hidden="true"></div>

    <!-- ─── NAVBAR ─── -->
    <nav>
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="{{ asset('img/logo.png') }}" alt="BroadKaster">
                <span class="nav-badge">Live Production Suite</span>
            </a>
            <div class="nav-actions">
                @auth
                    <a href="{{ url('/maidan') }}" class="btn-ghost">Dashboard</a>
                @else
                    <a href="#request-demo" class="btn-ghost">Request a Demo</a>
                    <a href="{{ url('/maidan/login') }}" class="btn-primary">Sign In</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ─── HERO ─── -->
    <section class="hero">
        <div class="hero-eyebrow">
            <span class="dot"></span>
            Professional PUBG Mobile Tournament Broadcast System
        </div>

        <div class="hero-logo-wrap">
            <img class="hero-logo" src="{{ asset('img/logo.png') }}" alt="BroadKaster">
        </div>

        <p class="hero-tagline">
            Command every match moment. Real-time OBS overlays, live rankings, and seamless
            director controls — all in one powerful broadcasting engine.
        </p>

        <div class="hero-ctas">
            <a href="{{ url('/maidan') }}" id="hero-launch-btn" class="btn-hero-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                </svg>
                Launch App Panel
            </a>
            <a href="{{ url('/admin') }}" id="hero-admin-btn" class="btn-hero-secondary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                Mukhiyas Panel
            </a>
        </div>

        <!-- Stats bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-num">16+</span>
                <span class="stat-label">OBS Overlays</span>
            </div>
            <div class="stat-item">
                <span class="stat-num">Real-Time</span>
                <span class="stat-label">WebSocket Sync</span>
            </div>
            <div class="stat-item">
                <span class="stat-num">1080p</span>
                <span class="stat-label">Overlay Quality</span>
            </div>
            <div class="stat-item">
                <span class="stat-num">v{{ $systemVersion }}</span>
                <span class="stat-label">Build</span>
            </div>
        </div>
    </section>

    <!-- ─── FEATURES GRID ─── -->
    <section class="features-section">
        <div class="features-header">
            <p class="section-label">Core Capabilities</p>
            <h2 class="section-heading">Everything your broadcast needs</h2>
            <p class="section-sub">From the first whistle to the final kill, BroadKaster keeps your stream production-ready at all times.</p>
        </div>

        <div class="features-grid">
            <!-- Card 1 -->
            <div class="feature-card featured">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <div class="feature-title">Real-Time Sync Engine</div>
                <p class="feature-desc">WebSocket-powered live data bridge instantly pushes match stat changes, score updates, and phase transitions to all connected OBS sources in under 100ms.</p>
                <span class="feature-tag">⚡ WebSockets</span>
            </div>

            <!-- Card 2 -->
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div class="feature-title">OBS Director Console</div>
                <p class="feature-desc">Centralised control panel to switch between all 16+ live overlay screens with a single click. Pre-match and post-match zones keep the director workflow crystal clear.</p>
                <span class="feature-tag">🎬 OBS Ready</span>
            </div>

            <!-- Card 3 -->
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div class="feature-title">Smart Rankings</div>
                <p class="feature-desc">Automated kill-point and placement scoring aggregates overall tournament leaderboards in real-time. Head-to-head comparisons and Top Fraggers overlays included.</p>
                <span class="feature-tag">📊 Auto-Calculated</span>
            </div>

            <!-- Card 4 -->
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                    </svg>
                </div>
                <div class="feature-title">Tournament Roadmap</div>
                <p class="feature-desc">Visual phase-by-phase tournament progression overlay with cinematic staggered entry animations. Directors update stages in Filament — the overlay refreshes automatically.</p>
                <span class="feature-tag">🗺️ Live Updates</span>
            </div>

            <!-- Card 5 -->
            <div class="feature-card featured">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <div class="feature-title">Cinematic Overlays</div>
                <p class="feature-desc">16+ professionally animated, 1920×1080 browser-source overlays — Map Pool, Point System, Post Match, Overall Ranking, Head to Head, Top Fraggers, and more.</p>
                <span class="feature-tag">🎨 Cinematic Quality</span>
            </div>

            <!-- Card 6 -->
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg class="feature-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="feature-title">Team & Player Management</div>
                <p class="feature-desc">Full Filament-powered admin panel to manage teams, slots, match rosters, casters, and tournament schedules — with a dedicated operator panel for tournament organizers.</p>
                <span class="feature-tag">👥 Full Control</span>
            </div>
        </div>
    </section>

    <!-- ─── HOW IT WORKS ─── -->
    <section class="workflow-section">
        <div class="workflow-inner">
            <div>
                <p class="section-label">Workflow</p>
                <h2 class="section-heading">Set up in minutes,<br>broadcast all day</h2>
                <p class="section-sub" style="margin-bottom: 40px;">The entire pipeline from match setup to live broadcast is designed to be intuitive and lightning-fast.</p>

                <div class="workflow-steps">
                    <div class="workflow-step">
                        <div class="step-num">1</div>
                        <div class="step-content">
                            <div class="step-title">Configure your tournament</div>
                            <p class="step-desc">Set up teams, maps, point rules, and the roadmap phases in the Maidan operator panel.</p>
                        </div>
                    </div>
                    <div class="workflow-step">
                        <div class="step-num">2</div>
                        <div class="step-content">
                            <div class="step-title">Add overlay URLs to OBS</div>
                            <p class="step-desc">Copy the unique browser-source URLs for each overlay from the OBS Hub widget and drop them into OBS Studio as Browser Sources.</p>
                        </div>
                    </div>
                    <div class="workflow-step">
                        <div class="step-num">3</div>
                        <div class="step-content">
                            <div class="step-title">Director controls the show</div>
                            <p class="step-desc">Use the OBS Director Console to switch active overlays in real-time. Stats, kills, and placements update live on air.</p>
                        </div>
                    </div>
                    <div class="workflow-step">
                        <div class="step-num">4</div>
                        <div class="step-content">
                            <div class="step-title">Post-match in one click</div>
                            <p class="step-desc">Mark the match complete — the system auto-calculates rankings, fraggers, and leaderboards instantly.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Mockup -->
            <div class="dashboard-mockup">
                <div class="mockup-topbar">
                    <div class="mockup-dot" style="background:#ff5f57;"></div>
                    <div class="mockup-dot" style="background:#febc2e;"></div>
                    <div class="mockup-dot" style="background:#28c840;"></div>
                    <span style="flex:1; text-align:center; font-size:11px; color:#334155; font-weight:600;">OBS Director Console — Match 3 of 6</span>
                </div>
                <div class="mockup-body">
                    <div class="mockup-row">
                        <div class="mockup-block">
                            <div class="mockup-block-label">Active Match</div>
                            <div class="mockup-block-val">Match 3</div>
                        </div>
                        <div class="mockup-block">
                            <div class="mockup-block-label">Live Teams</div>
                            <div class="mockup-block-val">12</div>
                        </div>
                        <div class="mockup-block">
                            <div class="mockup-block-label">Total Kills</div>
                            <div class="mockup-block-val">47</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 4px; font-size: 10px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #334155; margin-top: 8px;">Overall Standings</div>
                    @php
                    $teams = [
                    ['rank' => 1, 'name' => 'Skyline Wolves', 'pts' => 148, 'pct' => 100],
                    ['rank' => 2, 'name' => 'Iron Ghosts', 'pts' => 132, 'pct' => 89],
                    ['rank' => 3, 'name' => 'Storm Riders', 'pts' => 117, 'pct' => 79],
                    ['rank' => 4, 'name' => 'Neon Vipers', 'pts' => 103, 'pct' => 70],
                    ['rank' => 5, 'name' => 'Apex Hunters', 'pts' => 89, 'pct' => 60],
                    ];
                    @endphp
                    @foreach ($teams as $t)
                    <div class="mockup-table-row">
                        <div class="mockup-rank">#{{ $t['rank'] }}</div>
                        <div class="mockup-team" style="{{ $t['rank'] == 1 ? 'color:#f97316;' : '' }}">{{ $t['name'] }}</div>
                        <div class="mockup-pts">{{ $t['pts'] }} pts</div>
                        <div style="width:70px;">
                            <div class="mockup-bar-wrap">
                                <div class="mockup-bar" style="width:{{ $t['pct'] }}%; {{ $t['rank'] > 2 ? 'background: linear-gradient(90deg, #475569, #64748b);' : '' }}"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ─── REQUEST A DEMO ─── -->
    <section class="demo-section" id="request-demo">
        <div style="max-width:1100px;margin:0 auto;text-align:center;margin-bottom:48px;">
            <p class="section-label">Get Started</p>
            <h2 class="section-heading">Request a Demo</h2>
            <p class="section-sub" style="margin:0 auto;">Fill in the form and we'll reach out to set you up with a BroadKaster account tailored to your tournament needs.</p>
        </div>

        <div class="demo-inner">
            <!-- Left: Value props -->
            <div>
                <img src="{{ asset('img/symbol.png') }}" alt="BroadKaster" style="width:72px;height:72px;object-fit:contain;margin-bottom:24px;filter:drop-shadow(0 0 20px rgba(249,115,22,0.3));">
                <h3 style="font-family:'Rajdhani',sans-serif;font-size:28px;font-weight:700;color:#f1f5f9;margin-bottom:10px;">What you get with BroadKaster</h3>
                <p style="font-size:14px;color:var(--slate);line-height:1.7;">
                    No self-registration. We personally onboard every client to ensure a smooth production experience from day one.
                </p>
                <ul class="demo-value-list">
                    @php
                        $perks = [
                            'Real-time OBS overlay system — 16+ animated screens',
                            'WebSocket-powered live stat updates in under 100ms',
                            'Director console to switch screens without touching OBS',
                            'Automated kill-point & placement ranking engine',
                            'Tournament Roadmap, Head-to-Head, Top Fraggers overlays',
                            'Full team & player management with logo support',
                            'Dedicated operator panel for tournament organizers',
                        ];
                    @endphp
                    @foreach ($perks as $perk)
                    <li class="demo-value-item">
                        <span class="demo-check">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        {{ $perk }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Right: Form -->
            <div>
                @if (session('demo_success'))
                    <div class="success-card">
                        <div class="success-icon">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#22c55e" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                        <div style="font-family:'Rajdhani',sans-serif;font-size:22px;font-weight:700;color:#f1f5f9;margin-bottom:8px;">Request Received!</div>
                        <p style="font-size:13px;color:var(--slate);line-height:1.6;">Thank you! We've received your demo request and will get back to you shortly.</p>
                    </div>
                @else
                    <form action="{{ route('demo.request') }}" method="POST" class="demo-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="demo_name" class="form-label">Full Name <span style="color:var(--orange)">*</span></label>
                                <input id="demo_name" type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}" placeholder="Sumin Shrestha" value="{{ old('name') }}" required>
                                @error('name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="demo_email" class="form-label">Email Address <span style="color:var(--orange)">*</span></label>
                                <input id="demo_email" type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="you@esports.com" value="{{ old('email') }}" required>
                                @error('email') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="demo_org" class="form-label">Organization</label>
                                <input id="demo_org" type="text" name="organization" class="form-input" placeholder="Esports Nepal (optional)" value="{{ old('organization') }}">
                            </div>
                            <div class="form-group">
                                <label for="demo_phone" class="form-label">Phone</label>
                                <input id="demo_phone" type="tel" name="phone" class="form-input" placeholder="+977 98XXXXXXXX (optional)" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="demo_message" class="form-label">What are you looking to broadcast?</label>
                            <textarea id="demo_message" name="message" class="form-textarea" placeholder="Tell us about your tournament — game title, team count, event scale...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="form-submit">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                            Send Demo Request
                        </button>
                        <p style="font-size:11px;color:#334155;text-align:center;">We typically respond within 1–2 business days.</p>
                    </form>
                @endif
            </div>
        </div>
    </section>

    <!-- ─── FOOTER ─── -->
    <footer>
        <div class="footer-inner">
            <div class="footer-brand">
                <img src="{{ asset('img/logo.png') }}" alt="BroadKaster">
            </div>
            <div class="footer-copy">&copy; {{ date('Y') }} BroadKaster. Built with ❤️ by <a href="https://suminshrestha.com.np" style="color:#f97316;text-decoration:none;">OxyZone</a></div>
            <div class="footer-build">SYS_BUILD: v{{ $systemVersion }}</div>
        </div>
    </footer>

</body>
</html>
