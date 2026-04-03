<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Account Setup — NLAH</title>
    <meta http-equiv="refresh" content="60">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #fff;
            font-family: 'Instrument Sans', 'Segoe UI', sans-serif;
            color: #18181b;
            display: flex;
            flex-direction: column;
        }

        /* ── Top bar ── */
        .topbar {
            padding: 20px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e4e4e7;
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cross {
            width: 28px; height: 28px;
            position: relative;
            flex-shrink: 0;
        }
        .cross::before, .cross::after {
            content: '';
            position: absolute;
            background: #18181b;
            border-radius: 2px;
        }
        .cross::before { width: 8px; height: 28px; left: 10px; top: 0; }
        .cross::after  { width: 28px; height: 8px; left: 0; top: 10px; }
        .brand-name {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .02em;
            color: #18181b;
        }
        .topbar-clock {
            font-size: 12px;
            font-weight: 600;
            color: #71717a;
            letter-spacing: .04em;
        }

        /* ── Main layout ── */
        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
        }
        .container {
            width: 100%;
            max-width: 880px;
        }

        /* ── Two-column grid ── */
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: start;
        }
        @media (max-width: 680px) {
            .grid { grid-template-columns: 1fr; gap: 40px; }
            .divider-col { display: none; }
        }

        /* ── Left column ── */
        .eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #71717a;
            margin-bottom: 16px;
        }
        .heading {
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 700;
            letter-spacing: -.03em;
            line-height: 1.05;
            color: #18181b;
            margin-bottom: 16px;
        }
        .subtext {
            font-size: 16px;
            font-weight: 500;
            color: #71717a;
            line-height: 1.65;
            max-width: 340px;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 28px;
            padding: 8px 16px;
            background: #fafafa;
            border: 1px solid #e4e4e7;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            color: #52525b;
        }
        .status-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #f0b626;
            animation: blink 1.8s ease-in-out infinite;
        }

        /* ── Divider ── */
        .divider-col {
            display: flex;
            justify-content: center;
        }
        .v-divider {
            width: 1px;
            height: 100%;
            border-left: 1px dashed #d4d4d8;
        }

        /* ── Right column — steps ── */
        .steps-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: #a1a1aa;
            margin-bottom: 20px;
        }
        .steps { display: flex; flex-direction: column; gap: 0; }

        .step {
            display: flex;
            gap: 16px;
            padding-bottom: 28px;
            position: relative;
        }
        .step:last-child { padding-bottom: 0; }

        /* Vertical connector line */
        .step:not(:last-child) .step-line {
            position: absolute;
            left: 13px;
            top: 28px;
            width: 1px;
            bottom: 0;
            background: #e4e4e7;
        }
        .step.active:not(:last-child) .step-line {
            background: linear-gradient(to bottom, #18181b, #e4e4e7);
        }

        .step-node {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 2px solid #e4e4e7;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 11px;
            font-weight: 700;
            color: #a1a1aa;
            background: #fff;
            position: relative;
            z-index: 1;
        }
        .step.done .step-node {
            background: #18181b;
            border-color: #18181b;
            color: #fff;
        }
        .step.active .step-node {
            border-color: #18181b;
            color: #18181b;
        }

        .step-body { padding-top: 3px; }
        .step-title {
            font-size: 14px;
            font-weight: 700;
            color: #18181b;
            margin-bottom: 3px;
        }
        .step.waiting .step-title { color: #a1a1aa; }
        .step-desc {
            font-size: 12px;
            font-weight: 500;
            color: #a1a1aa;
            line-height: 1.5;
        }
        .step.active .step-desc { color: #71717a; }

        /* ── HR note ── */
        .hr-note {
            margin-top: 32px;
            padding: 18px 20px;
            background: #fafafa;
            border: 1px solid #e4e4e7;
            border-radius: 12px;
        }
        .hr-note p {
            font-size: 12px;
            font-weight: 500;
            color: #71717a;
            line-height: 1.7;
        }
        .hr-note strong { color: #3f3f46; font-weight: 700; }

        /* ── Footer ── */
        .footer {
            padding: 20px 32px;
            border-top: 1px solid #e4e4e7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: gap;
            gap: 12px;
        }
        .footer-left {
            font-size: 12px;
            font-weight: 500;
            color: #a1a1aa;
        }
        .logout-btn {
            font-size: 12px;
            font-weight: 600;
            color: #71717a;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            letter-spacing: .02em;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        .logout-btn:hover { color: #18181b; }

        @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:.25; } }
    </style>
</head>
<body>

    {{-- ── Top bar ── --}}
    <header class="topbar">
        <div class="topbar-brand">
            <div class="cross"></div>
            <span class="brand-name">Northern Luzon Adventist Hospital</span>
        </div>
        <span class="topbar-clock" id="clock">--:--</span>
    </header>

    {{-- ── Main ── --}}
    <main class="main">
        <div class="container">
            <div class="grid">

                {{-- Left ── --}}
                <div>
                    <p class="eyebrow">Account Setup</p>
                    <h1 class="heading">We're getting<br>things ready.</h1>
                    <p class="subtext">
                        Your registration is complete. HR is currently reviewing your profile and
                        configuring your system access.
                    </p>
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        HR review in progress
                    </div>
                </div>

                {{-- Divider ── --}}
                <div class="divider-col">
                    <div class="v-divider"></div>
                </div>

                {{-- Right ── --}}
                <div>
                    <p class="steps-label">Progress</p>

                    <div class="steps">

                        <div class="step done">
                            <div class="step-line"></div>
                            <div class="step-node">
                                <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6l3 3 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="step-body">
                                <p class="step-title">Registration complete</p>
                                <p class="step-desc">Account created &amp; email verified</p>
                            </div>
                        </div>

                        <div class="step active">
                            <div class="step-line"></div>
                            <div class="step-node">2</div>
                            <div class="step-body">
                                <p class="step-title">HR review</p>
                                <p class="step-desc">Department assignment &amp; role configuration — usually 1–2 business days</p>
                            </div>
                        </div>

                        <div class="step waiting">
                            <div class="step-node">3</div>
                            <div class="step-body">
                                <p class="step-title">Access granted</p>
                                <p class="step-desc">Portal unlocked — you'll be notified</p>
                            </div>
                        </div>

                    </div>

                    <div class="hr-note">
                        <p>
                            <strong>Need help?</strong> Visit the HR Office at the Ground Floor, Main Building,
                            or call the hospital landline. Please have your
                            <strong>Employee Number</strong> ready.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ── Footer ── --}}
    <footer class="footer">
        <span class="footer-left">Artacho, Sison, Pangasinan &nbsp;·&nbsp; This page refreshes automatically.</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Sign out</button>
        </form>
    </footer>

    <script>
        (function tick() {
            const el = document.getElementById('clock');
            if (el) el.textContent = new Date().toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' });
            setTimeout(tick, 1000);
        })();
    </script>

</body>
</html>
