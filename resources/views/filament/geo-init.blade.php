<div x-data="geoReq({
    lat: @entangle('data.latitude').defer,
    lng: @entangle('data.longitude').defer,
})" x-init="init()"
    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-gray-900/70 backdrop-blur-sm shadow-sm space-y-4 p-4 sm:p-5">

    <!-- Header + Badge -->
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <span class="text-xl" aria-hidden="true">📍</span>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Lokasi</h3>
        </div>

        <template x-if="permissionState === 'granted'">
            <span
                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                <span aria-hidden="true">✅</span> Diizinkan
            </span>
        </template>
        <template x-if="permissionState !== 'granted'">
            <span
                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200">
                <span aria-hidden="true">⛔</span> Belum Aktif
            </span>
        </template>
    </div>

    <!-- Status banner -->
    <template x-if="statusMsg">
        <div
            class="rounded-lg border border-amber-300/80 dark:border-amber-800/80 bg-amber-50 dark:bg-amber-950/40 px-3 py-2.5 text-sm text-amber-900 dark:text-amber-200">
            <div x-html="statusMsg"></div>
        </div>
    </template>

    <!-- Action buttons -->
    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
        <!-- Gunakan lokasiku -->
        <button type="button" x-on:click="requestGeo"
            class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600"
            aria-label="Gunakan lokasiku">
            <span aria-hidden="true">🧭</span>
            <span>Gunakan lokasiku</span>
        </button>

        <!-- Coba lagi -->
        <button type="button" x-on:click="requestGeo"
            class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
            aria-label="Coba lagi">
            <span aria-hidden="true">🔄</span>
            <span>Coba lagi</span>
        </button>

        <!-- Pengaturan situs -->
        <button type="button" x-on:click="openSiteSettings"
            class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
            aria-label="Buka pengaturan lokasi">
            <span class="text-base" aria-hidden="true">⚙️</span>
            <span>Copy Pengaturan lokasi</span>
        </button>

        <!-- Petunjuk reset koneksi -->
        <button type="button" x-on:click="showNetworkGuide = !showNetworkGuide"
            class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
            aria-label="Petunjuk reset koneksi">
            <span aria-hidden="true">📶</span>
            <span>Petunjuk reset koneksi</span>
        </button>
    </div>

    <!-- Hint live -->
    <div class="text-xs text-gray-600 dark:text-gray-400 h-4" x-text="hint" aria-live="polite"></div>

    <!-- Steps collapsible -->
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/70">
        <button type="button" @click="showSteps = !showSteps"
            class="flex w-full items-center justify-between p-3 text-left font-medium text-gray-900 dark:text-gray-100 transition hover:bg-gray-100 dark:hover:bg-gray-800/50">
            <span class="flex items-center gap-2">
                <span aria-hidden="true">🪜</span>
                <span>Langkah Mengaktifkan Lokasi</span>
            </span>
            <svg class="h-5 w-5 shrink-0 transition-transform duration-300" :class="{ 'rotate-180': showSteps }"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="showSteps" x-collapse class="border-t border-gray-200 dark:border-gray-700">
            <div class="p-4 space-y-3 text-sm">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-gray-100"><span aria-hidden="true">🔔</span> 1.
                        Izinkan Akses</p>
                    <p class="mt-0.5 text-gray-600 dark:text-gray-400">
                        Tekan <b>🧭 Gunakan lokasiku</b>. Saat browser meminta izin, pilih <b>Allow / Izinkan</b>.
                    </p>
                </div>

                <div x-show="permissionState === 'denied'">
                    <p class="font-semibold text-gray-900 dark:text-gray-100"><span aria-hidden="true">⚙️</span> 2. Jika
                        Akses Terblokir</p>
                    <p class="mt-0.5 text-gray-600 dark:text-gray-400">
                        Tekan <b>⚙️ Pengaturan lokasi</b> untuk meng-copy link pengaturan situs. kemudian paste di
                        browser. Ubah izin <b>Lokasi</b>
                        menjadi <b>Izinkan</b>, lalu muat ulang halaman.
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-gray-900 dark:text-gray-100"><span aria-hidden="true">🛰️</span> 3.
                        Aktifkan Layanan Perangkat</p>
                    <p class="mt-0.5 text-gray-600 dark:text-gray-400">
                        Pastikan layanan lokasi (GPS) di perangkat Anda aktif dan mode presisi (Precise) dinyalakan.
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-gray-900 dark:text-gray-100"><span aria-hidden="true">📶</span> 4. Jika
                        muncul <i>“Position update is unavailable”</i></p>
                    <ul class="mt-0.5 text-gray-600 dark:text-gray-400 list-disc list-inside space-y-0.5">
                        <li>Matikan & nyalakan <b>internet</b> (Wi-Fi/Data seluler) lalu coba lagi.</li>
                        <li>Aktif/nonaktifkan cepat <b>Mode Pesawat</b> selama 5 detik.</li>
                        <li>Tutup tab ini, buka lagi, atau tekan <b>Reload</b>.</li>
                        <li>Tekan <b>🔄 Coba lagi</b> setelah reset koneksi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Petunjuk reset koneksi -->
    <div x-show="showNetworkGuide" x-collapse
        class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50/70 dark:bg-blue-950/30 p-3 text-sm text-blue-900 dark:text-blue-200">
        <p class="font-semibold flex items-center gap-2"><span aria-hidden="true">📶</span> Petunjuk Reset Koneksi</p>
        <ul class="mt-1 list-disc list-inside space-y-0.5">
            <li><b>HP:</b> Matikan & nyalakan Wi-Fi/Data, atau hidupkan Mode Pesawat 5–10 detik lalu matikan.</li>
            <li><b>Laptop/PC:</b> Putuskan sambungan Wi-Fi lalu sambungkan lagi, atau matikan/nyalakan adapter jaringan.
            </li>
            <li>Setelah itu, klik <b>🔄 Coba lagi</b> untuk meminta lokasi ulang.</li>
        </ul>
    </div>

    <!-- Koordinat terisi -->
    <template x-if="Number(model.lat||0) || Number(model.lng||0)">
        <div class="text-sm text-gray-800 dark:text-gray-300 pt-1">
            <span class="font-medium">Koordinat terisi:</span>
            <code class="ml-1 text-gray-700 dark:text-gray-400 text-xs"
                x-text="`${Number(model.lat).toFixed(6)}, ${Number(model.lng).toFixed(6)}`"></code>
        </div>
    </template>
</div>

<script>
    function geoReq(model) {
        return {
            model,
            showSteps: false,
            showNetworkGuide: false,
            hint: '',
            statusMsg: '',
            permissionState: 'prompt', // prompt | granted | denied
            backoffKey: 'geo_backoff_until',
            backoffHours: 12,

            get origin() {
                return window.location.origin;
            },
            get firstSettingsUrl() {
                const ua = navigator.userAgent.toLowerCase();
                if (ua.includes('arc')) return `arc://settings/content/siteDetails?site=${this.origin}`;
                if (ua.includes('edg')) return `edge://settings/content/siteDetails?site=${this.origin}`;
                return `chrome://settings/content/siteDetails?site=${this.origin}`;
            },

            async init() {
                // permission state
                if (navigator.permissions?.query) {
                    try {
                        const p = await navigator.permissions.query({
                            name: 'geolocation'
                        });
                        this.permissionState = p.state;
                        p.onchange = () => {
                            this.permissionState = p.state;
                            this.updateBanner();
                        };
                    } catch {}
                }
                this.updateBanner();

                // auto request (jika kosong & bukan denied & tidak backoff)
                const empty = !(Number(this.model.lat || 0) || Number(this.model.lng || 0));
                const inBackoff = Date.now() < Number(localStorage.getItem(this.backoffKey) || 0);
                if (empty && this.permissionState !== 'denied' && !inBackoff) this.requestGeo();
            },

            updateBanner() {
                if (this.permissionState === 'denied') {
                    this.statusMsg =
                        '🚫 <b>Akses lokasi diblokir.</b> Izinkan melalui pengaturan situs, lalu muat ulang halaman.';
                } else if (this.permissionState === 'prompt') {
                    this.statusMsg = '🔔 Izinkan akses lokasi untuk mengisi koordinat secara otomatis.';
                } else {
                    this.statusMsg = '';
                }
            },

            // fallback: watchPosition untuk “membangunkan” service lokasi
            watchFallback(opts = {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }) {
                return new Promise((resolve) => {
                    if (!navigator.geolocation) return resolve(null);
                    let done = false;
                    const tid = setTimeout(() => {
                        if (!done) {
                            done = true;
                            navigator.geolocation.clearWatch(wid);
                            resolve(null);
                        }
                    }, opts.timeout ?? 15000);
                    const wid = navigator.geolocation.watchPosition(
                        (pos) => {
                            if (!done) {
                                done = true;
                                clearTimeout(tid);
                                navigator.geolocation.clearWatch(wid);
                                resolve(pos);
                            }
                        },
                        () => {
                            if (!done) {
                                done = true;
                                clearTimeout(tid);
                                navigator.geolocation.clearWatch(wid);
                                resolve(null);
                            }
                        },
                        opts
                    );
                });
            },

            requestGeo() {
                this.hint = '⏳ Mencari lokasi...';
                if (!('geolocation' in navigator)) {
                    this.hint = 'Browser Anda tidak mendukung Geolocation.';
                    return;
                }

                const onSuccess = (pos) => {
                    const lat = pos.coords.latitude,
                        lng = pos.coords.longitude;

                    // SET ke state map (Filament) + sinkron model
                    @this.set('data.locations', {
                        lat,
                        lng,
                        minZoom: 1,
                        maxZoom: 23,
                        zoom: 18,
                        ext: 'png'
                    });
                    this.model.lat = lat;
                    this.model.lng = lng;

                    this.hint = '✅ Lokasi berhasil ditemukan! Silahkan Refresh halaman web jika maps belum sesuai';
                    this.statusMsg = '';
                    localStorage.removeItem(this.backoffKey);

                    // “sentilan” kecil agar center tegas di beberapa env
                    setTimeout(() => {
                        @this.set('data.locations', {
                            lat,
                            lng,
                            minZoom: 1,
                            maxZoom: 23,
                            zoom: 18,
                            ext: 'png'
                        });
                    }, 80);
                };

                const onFailure = async (err) => {
                    const code = err?.code;
                    const msg = err?.message || 'Gagal mengambil lokasi.';

                    // coba fallback dulu dengan watchPosition
                    const watched = await this.watchFallback();
                    if (watched) return onSuccess(watched);

                    // jika posisi tidak tersedia → tampilkan instruksi reset koneksi
                    const isUnavailable = code === (err?.POSITION_UNAVAILABLE ?? 2) ||
                        /Position update is unavailable/i.test(msg);
                    if (isUnavailable) {
                        this.statusMsg = [
                            '📶 <b>Position update is unavailable.</b>',
                            'Silakan <b>matikan & nyalakan internet</b> (Wi-Fi/Data) atau aktifkan <b>Mode Pesawat</b> 5–10 detik, lalu klik <b>🔄 Coba lagi</b>.'
                        ].join(' ');
                        this.hint = '⚠️ Coba reset koneksi lalu ulangi.';
                        this.showNetworkGuide = true;
                        return;
                    }

                    // denied → backoff agar tidak ganggu user
                    if (code === (err?.PERMISSION_DENIED ?? 1)) {
                        localStorage.setItem(this.backoffKey, String(Date.now() + this.backoffHours * 3600 *
                            1000));
                        this.permissionState = 'denied';
                        this.updateBanner();
                        this.hint = '🚫 Akses ditolak. Izinkan di pengaturan situs, lalu muat ulang.';
                        return;
                    }

                    // timeout / lainnya
                    this.hint = `❌ ${msg}`;
                };

                navigator.geolocation.getCurrentPosition(
                    onSuccess,
                    onFailure, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            },

            async openSiteSettings() {
                const ua = navigator.userAgent.toLowerCase();
                const targets = [];
                if (ua.includes('arc')) targets.push(`arc://settings/content/siteDetails?site=${this.origin}`,
                    'arc://settings/content/location');
                if (ua.includes('edg')) targets.push(`edge://settings/content/siteDetails?site=${this.origin}`,
                    'edge://settings/content/location');
                targets.push(`chrome://settings/content/siteDetails?site=${this.origin}`,
                    'chrome://settings/content/location');

                for (const url of targets) {
                    const w = window.open(url, '_blank');
                    if (w && !w.closed) {
                        this.hint = '🔎 Cek tab baru yang terbuka untuk mengatur izin.';
                        return;
                    }
                }
                await this.copyTextWithFeedback(this.firstSettingsUrl);
            },

            async copySettingsUrl() {
                await this.copyTextWithFeedback(this.firstSettingsUrl);
            },

            async copyTextWithFeedback(text) {
                try {
                    await navigator.clipboard.writeText(text);
                    this.hint = '📎 URL pengaturan disalin ke clipboard.';
                } catch {
                    this.hint = `🔗 Gagal menyalin. Salin manual: ${text}`;
                }
            },
        }
    }
</script>
