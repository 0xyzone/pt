<x-base title="OBS MASTER VIEW">
    <style>
        /* ══════════════════════════════════════════════════════
           DOUBLE-BUFFER CROSSFADE LAYER SYSTEM
           ══════════════════════════════════════════════════════ */
        #obs-layer-a,
        #obs-layer-b {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            transition: opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: opacity;
        }

        /* ══════════════════════════════════════════════════════
           GLOBAL EXIT TRANSITIONS
           ══════════════════════════════════════════════════════ */

        .exit-active .slide-down {
            animation: slideDownExit 0.42s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .slide-left,
        .exit-active .slide-from-left {
            animation: slideLeftExit 0.42s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .slide-right,
        .exit-active .slide-from-right {
            animation: slideRightExit 0.42s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .slide-up {
            animation: slideUpExit 0.38s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .slide-up-footer {
            animation: slideUpFooterExit 0.34s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .card-reveal {
            animation: cardRevealExit 0.42s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .logo-reveal {
            animation: logoRevealExit 0.42s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .badge-drop {
            animation: badgeDropExit 0.38s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .stat-reveal {
            animation: statRevealExit 0.34s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }
        .exit-active .badge-pop {
            animation: badgePopExit 0.34s cubic-bezier(0.4, 0, 1, 1) both !important;
            animation-delay: 0s !important;
        }

        /* ══════════════════════════════════════════════════════
           EXIT KEYFRAMES — fast, decisive
           ══════════════════════════════════════════════════════ */
        @keyframes slideDownExit {
            from { opacity: 1; transform: translateY(0); }
            to   { opacity: 0; transform: translateY(-60px); }
        }
        @keyframes slideLeftExit {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to   { opacity: 0; transform: translateX(-80px) scale(0.96); }
        }
        @keyframes slideRightExit {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(80px); }
        }
        @keyframes slideUpExit {
            from { opacity: 1; transform: translateY(0); }
            to   { opacity: 0; transform: translateY(36px); }
        }
        @keyframes slideUpFooterExit {
            from { opacity: 1; transform: translateY(0); }
            to   { opacity: 0; transform: translateY(48px); }
        }
        @keyframes cardRevealExit {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to   { opacity: 0; transform: translateY(80px) scale(0.95); }
        }
        @keyframes logoRevealExit {
            from { opacity: 1; transform: scale(1) rotate(0deg); }
            to   { opacity: 0; transform: scale(0.6) rotate(-10deg); }
        }
        @keyframes badgeDropExit {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to   { opacity: 0; transform: translateY(-30px) scale(0.75); }
        }
        @keyframes statRevealExit {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to   { opacity: 0; transform: translateY(24px) scale(0.8); }
        }
        @keyframes badgePopExit {
            from { opacity: 1; transform: scale(1); }
            to   { opacity: 0; transform: scale(0.6); }
        }
    </style>

    {{-- Double-buffer: Layer A (starts active) and Layer B (starts hidden) --}}
    <div id="obs-wrapper" class="relative w-full h-full overflow-hidden bg-[#020617]">
        <div id="obs-layer-a" style="opacity:1; z-index:2;"></div>
        <div id="obs-layer-b" style="opacity:0; z-index:1;"></div>
    </div>

    <script type="module">
        window.isObsMaster = true;
        let currentViewName = 'empty';
        let isTransitioning  = false;
        let activeLayer      = 'a'; // 'a' or 'b'

        const getLayer = (id) => document.getElementById('obs-layer-' + id);
        const other    = (id) => id === 'a' ? 'b' : 'a';

        /* ─── Resolve URL ───────────────────────────────────── */
        function resolveUrl(viewType) {
            const uid = '{{ $user->id }}';
            const map = {
                postmatch:      '{{ route("screens.postmatch",      ["user_id" => $user->id]) }}',
                overallranking: '{{ route("screens.overallranking", ["user_id" => $user->id]) }}',
                headtohead:     '{{ route("screens.headtohead",     ["user_id" => $user->id]) }}',
                topfraggers:    '{{ route("screens.topfraggers",    ["user_id" => $user->id]) }}',
                mappool:        '{{ route("screens.mappool",        ["user_id" => $user->id]) }}',
                pointsystem:    '{{ route("screens.pointsystem",    ["user_id" => $user->id]) }}',
            };
            return map[viewType] || null;
        }

        /* ─── Fetch HTML from server ────────────────────────── */
        function fetchHtml(viewType) {
            const url = resolveUrl(viewType);
            if (!url) return Promise.resolve('');
            return fetch(url, { cache: 'no-store', headers: { 'Cache-Control': 'no-cache' } })
                .then(r => r.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    return doc.querySelector('body')?.innerHTML ?? '';
                })
                .catch(() => '');
        }

        /* ─── Inject body HTML including <style> tags ──────── */
        function injectHtml(layer, html) {
            layer.innerHTML = html;
            // Re-execute any <script> tags embedded in injected HTML
            layer.querySelectorAll('script').forEach(old => {
                const s = document.createElement('script');
                s.type = old.type || 'text/javascript';
                if (old.src) { s.src = old.src; }
                else { s.textContent = old.textContent; }
                old.replaceWith(s);
            });
        }

        /* ─── Main transition routine ───────────────────────── */
        function loadView(viewType) {
            if (isTransitioning) return;

            // Same view — silent in-place refresh with no animation
            if (viewType === currentViewName) {
                fetchAndRenderInPlace(viewType);
                return;
            }

            isTransitioning = true;
            currentViewName = viewType;

            const currentL = getLayer(activeLayer);
            const nextL    = getLayer(other(activeLayer));

            // Step 1 — play exit on the currently visible layer
            currentL.classList.add('exit-active');

            // Step 2 — simultaneously fetch new HTML (parallel with exit)
            const fetchPromise = viewType === 'empty'
                ? Promise.resolve('')
                : fetchHtml(viewType);

            // Step 3 — after exit animation (420ms), crossfade
            const EXIT_MS = 420;
            const exitTimer = new Promise(res => setTimeout(res, EXIT_MS));

            Promise.all([fetchPromise, exitTimer]).then(([html]) => {
                // Inject into the HIDDEN layer (no flicker — it's invisible)
                nextL.classList.remove('exit-active');
                injectHtml(nextL, html);

                // Force a reflow so CSS transitions register correctly
                nextL.getBoundingClientRect();

                // Bring next layer to front and crossfade
                nextL.style.zIndex = '2';
                currentL.style.zIndex = '1';
                nextL.style.opacity  = '1';
                currentL.style.opacity = '0';

                // After crossfade completes, clean up old layer
                setTimeout(() => {
                    currentL.innerHTML = '';
                    currentL.classList.remove('exit-active');
                    activeLayer = other(activeLayer);
                    isTransitioning = false;
                }, 480); // matches layer transition duration
            });
        }

        /* ─── In-place refresh (same view, data update) ─────── */
        function fetchAndRenderInPlace(viewType) {
            if (viewType === 'empty') return;
            fetchHtml(viewType).then(html => {
                const currentL = getLayer(activeLayer);
                injectHtml(currentL, html);
            });
        }

        /* ─── Legacy alias used by background data events ───── */
        function fetchAndRender(viewType) {
            fetchAndRenderInPlace(viewType);
        }

        /* ─── Echo listeners ────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function () {
            Echo.channel('user-screens.{{ $user->id }}')
                .listen('.ObsViewSwitched', (e) => {
                    console.log('ObsViewSwitched received:', e);
                    if (e.viewName === 'refresh') {
                        loadView(currentViewName);
                    } else {
                        loadView(e.viewName);
                    }
                })
                .listen('.MatchStatsUpdated', (e) => {
                    console.log('MatchStatsUpdated received (User Channel):', e);
                    fetchAndRender(currentViewName);
                })
                .listen('.TournamentMatchUpdated', (e) => {
                    console.log('TournamentMatchUpdated received:', e);
                    fetchAndRender(currentViewName);
                })
                .listen('.RefreshScreens', () => {
                    console.log('Force refresh received...');
                    window.location.reload();
                });

            @if($activeMatch?->id)
            Echo.channel('active-match.{{ $activeMatch->id }}')
                .listen('.MatchStatsUpdated', (e) => {
                    console.log('MatchStatsUpdated received (Active Match Channel):', e);
                    if (currentViewName !== 'empty') fetchAndRender(currentViewName);
                });
            @endif
        });
    </script>
</x-base>
