@php($audioMp3 = config('brand.audio.gayatri'))
@php($audioFallback = config('brand.audio.gayatri_fallback'))
<div
    class="fixed bottom-5 right-5 z-50 no-print"
    data-mantra-player
    data-mantra-src-mp3="{{ $audioMp3 }}"
    data-mantra-src-ogg="{{ $audioFallback }}"
>
    <audio data-mantra-audio preload="none" playsinline></audio>
    <div class="w-[min(20rem,calc(100vw-2rem))] rounded-2xl bg-[#2a0d09]/95 text-white shadow-2xl border border-amber-300/20 overflow-hidden backdrop-blur">
        <div class="flex items-center gap-3 px-4 py-3">
            <button type="button" data-mantra-toggle class="h-11 w-11 shrink-0 rounded-full temple-gradient flex items-center justify-center divine-glow hover:brightness-110 transition" aria-label="Play Gayatri Mantra" aria-pressed="false">
                <i data-mantra-icon class="fa-solid fa-play"></i>
            </button>
            <div class="min-w-0 flex-1">
                <span class="block text-sm font-bold leading-tight">Gayatri Mantra</span>
                <span data-mantra-status class="block text-xs text-orange-100/75">calm ambience</span>
            </div>
            <button type="button" data-mantra-mute class="h-9 w-9 rounded-full bg-white/10 hover:bg-white/20 transition" aria-label="Mute mantra" aria-pressed="false">
                <i data-mantra-mute-icon class="fa-solid fa-volume-low text-xs"></i>
            </button>
        </div>
        <div class="px-4 pb-3 flex items-center gap-3">
            <span class="text-[10px] uppercase tracking-[0.18em] text-orange-100/60">Volume</span>
            <input data-mantra-volume type="range" min="0" max="0.36" step="0.01" value="0.14" class="w-full accent-amber-400" aria-label="Mantra volume">
        </div>
    </div>
</div>
<script>
    (() => {
        const root = document.querySelector('[data-mantra-player]');
        if (!root) return;

        const audio = root.querySelector('[data-mantra-audio]');
        const toggle = root.querySelector('[data-mantra-toggle]');
        const mute = root.querySelector('[data-mantra-mute]');
        const volume = root.querySelector('[data-mantra-volume]');
        const status = root.querySelector('[data-mantra-status]');
        const icon = root.querySelector('[data-mantra-icon]');
        const muteIcon = root.querySelector('[data-mantra-mute-icon]');

        const keys = {
            intent: 'vedMantraIntent',
            muted: 'vedMantraMuted',
            volume: 'vedMantraVolume',
            time: 'vedMantraTime',
        };

        let loaded = false;
        let fadeFrame = null;
        let loopSoftening = false;
        let desiredVolume = Math.min(0.36, Math.max(0, Number(localStorage.getItem(keys.volume) || volume.value || 0.14)));
        let muted = localStorage.getItem(keys.muted) === '1';

        volume.value = desiredVolume;
        audio.volume = 0;
        audio.muted = muted;

        const ensureLoaded = () => {
            if (loaded) return;
            loaded = true;

            const mp3 = document.createElement('source');
            mp3.src = root.dataset.mantraSrcMp3;
            mp3.type = 'audio/mpeg';
            audio.appendChild(mp3);

            if (root.dataset.mantraSrcOgg) {
                const ogg = document.createElement('source');
                ogg.src = root.dataset.mantraSrcOgg;
                ogg.type = 'audio/ogg';
                audio.appendChild(ogg);
            }

            audio.load();
        };

        const paint = () => {
            const playing = !audio.paused;
            toggle.setAttribute('aria-pressed', playing ? 'true' : 'false');
            mute.setAttribute('aria-pressed', muted ? 'true' : 'false');
            toggle.setAttribute('aria-label', playing ? 'Pause Gayatri Mantra' : 'Play Gayatri Mantra');
            mute.setAttribute('aria-label', muted ? 'Unmute mantra' : 'Mute mantra');
            icon.className = playing ? 'fa-solid fa-pause' : 'fa-solid fa-play';
            muteIcon.className = muted || desiredVolume === 0 ? 'fa-solid fa-volume-xmark text-xs' : 'fa-solid fa-volume-low text-xs';
            status.textContent = playing ? (muted ? 'muted' : 'playing softly') : 'tap to play';
        };

        const fadeTo = (target, duration = 1800) => {
            cancelAnimationFrame(fadeFrame);
            const start = audio.volume;
            const startedAt = performance.now();
            const boundedTarget = Math.min(0.36, Math.max(0, target));

            const step = (now) => {
                const progress = Math.min(1, (now - startedAt) / duration);
                const eased = 1 - Math.pow(1 - progress, 3);
                audio.volume = start + ((boundedTarget - start) * eased);

                if (progress < 1) {
                    fadeFrame = requestAnimationFrame(step);
                }
            };

            fadeFrame = requestAnimationFrame(step);
        };

        const storePosition = () => {
            if (!Number.isFinite(audio.currentTime)) return;
            localStorage.setItem(keys.time, String(Math.max(0, audio.currentTime - 0.25)));
        };

        const play = async (rememberIntent = true) => {
            ensureLoaded();
            audio.loop = false;
            audio.muted = muted;

            const savedTime = Number(localStorage.getItem(keys.time) || 0);
            if (savedTime > 0 && Number.isFinite(audio.duration) && savedTime < Math.max(0, audio.duration - 2)) {
                audio.currentTime = savedTime;
            }

            try {
                status.textContent = 'starting softly';
                await audio.play();
                if (rememberIntent) localStorage.setItem(keys.intent, 'play');
                fadeTo(muted ? 0 : desiredVolume, 3600);
            } catch (error) {
                status.textContent = 'tap to play';
            }

            paint();
        };

        const pause = (rememberIntent = true) => {
            storePosition();
            if (rememberIntent) localStorage.setItem(keys.intent, 'pause');
            fadeTo(0, 650);
            setTimeout(() => {
                audio.pause();
                paint();
            }, 700);
        };

        toggle.addEventListener('click', () => {
            if (audio.paused) {
                play(true);
            } else {
                pause(true);
            }
        });

        mute.addEventListener('click', () => {
            muted = !muted;
            audio.muted = muted;
            localStorage.setItem(keys.muted, muted ? '1' : '0');
            if (!audio.paused) fadeTo(muted ? 0 : desiredVolume, 900);
            paint();
        });

        volume.addEventListener('input', () => {
            desiredVolume = Math.min(0.36, Math.max(0, Number(volume.value)));
            muted = desiredVolume === 0;
            audio.muted = muted;
            localStorage.setItem(keys.volume, String(desiredVolume));
            localStorage.setItem(keys.muted, muted ? '1' : '0');
            if (!audio.paused) fadeTo(muted ? 0 : desiredVolume, 450);
            paint();
        });

        audio.addEventListener('timeupdate', () => {
            storePosition();

            if (!Number.isFinite(audio.duration) || audio.duration <= 0) return;
            const remaining = audio.duration - audio.currentTime;

            if (remaining < 2.4 && !loopSoftening) {
                loopSoftening = true;
                fadeTo(muted ? 0 : Math.min(desiredVolume, 0.035), 1300);
            }
        });

        audio.addEventListener('ended', async () => {
            loopSoftening = false;
            localStorage.setItem(keys.time, '0');
            audio.currentTime = 0;
            try {
                await audio.play();
                fadeTo(muted ? 0 : desiredVolume, 2600);
            } catch (error) {
                paint();
            }
        });

        audio.addEventListener('play', paint);
        audio.addEventListener('pause', paint);
        audio.addEventListener('loadedmetadata', () => {
            const savedTime = Number(localStorage.getItem(keys.time) || 0);
            if (savedTime > 0 && savedTime < Math.max(0, audio.duration - 2)) {
                audio.currentTime = savedTime;
            }
        });
        window.addEventListener('beforeunload', storePosition);

        window.addEventListener('load', () => {
            const intent = localStorage.getItem(keys.intent);
            if (intent === 'pause') {
                paint();
                return;
            }

            setTimeout(() => play(intent === 'play'), 850);
        }, { once: true });

        paint();
    })();
</script>
